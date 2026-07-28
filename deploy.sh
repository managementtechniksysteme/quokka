#!/bin/sh
#
# Deploys the currently checked-out branch (normally invoked for `master`
# after CI has already run the test suite -- this script does not run tests
# itself). Intended to be the sole command a forced-command SSH deploy key
# is allowed to run; see the docker-alpine branch discussion for that setup.
#
# Assumes: this is an existing clone with .env and .env.docker already
# present (both gitignored, never touched by `git pull`) and the Docker
# images already built at least once. First-time server setup (clone,
# populate .env/.env.docker, `docker compose build`, `php artisan key:generate`)
# is a one-off manual bootstrap, not scripted here.
#
# POSIX sh, not bash -- this needs to run correctly under Alpine's default
# /bin/sh (busybox ash), which has no arrays, no [[ ]], and no `pipefail`.
set -eu

cd "$(dirname "$0")"

for required_file in .env .env.docker; do
    if [ ! -f "$required_file" ]; then
        echo "✗ Missing $required_file -- this looks like an unbootstrapped checkout, refusing to deploy." >&2
        exit 1
    fi
done

# The persistent prod service set -- deliberately excludes nodejs (assets are
# built as a one-off below, never run as a long-lived dev server in prod)
# and php-fpm-dev/mailpit (profiled, dev/test-only, never started at all here).
# A plain space-separated string, not an array (POSIX sh has none) --
# intentionally left unquoted everywhere it's used below, so it word-splits
# into separate arguments; safe since none of these names contain spaces.
PROD_SERVICES="php-fpm queue-worker schedule-worker caddy mariadb redis"

echo "→ Pulling latest code"
PREV_SHA=$(git rev-parse HEAD)
git pull --ff-only
NEW_SHA=$(git rev-parse HEAD)

changed() {
    ! git diff --quiet "$PREV_SHA" "$NEW_SHA" -- "$@"
}

# Infra changed (this Dockerfile/compose refactor is exactly the kind of
# change this branch covers) -- rebuild images before bringing services up,
# so `up -d` below recreates anything whose image actually changed.
if changed docker-compose.yml docker/; then
    echo "→ Docker infra changed, rebuilding images"
    docker compose build $PROD_SERVICES
fi

echo "→ Ensuring the prod service set is up"
docker compose up -d $PROD_SERVICES

# Composer deps only when they actually changed
if changed composer.lock composer.json; then
    echo "→ Composer dependencies changed, installing"
    docker compose exec -T php-fpm composer install --no-dev --optimize-autoloader --no-interaction
fi

# Frontend assets only when they actually changed -- one-off container, not
# a persistent nodejs service (that only runs for local dev's live Vite server).
if changed package-lock.json package.json resources/js resources/sass resources/css vite.config.mjs; then
    echo "→ Frontend assets changed, rebuilding"
    docker compose run --rm nodejs sh -c "npm ci && npm run build && npm run copy-assets"
fi

echo "→ Migrating"
docker compose exec -T php-fpm php artisan migrate --force

echo "→ Rebuilding caches (config + routes + views + events)"
docker compose exec -T php-fpm php artisan optimize

echo "→ Reloading php-fpm (flushes OPcache)"
docker compose exec -T php-fpm kill -USR2 1

# Both are long-running loops (queue:work / schedule:work), not one-shot
# processes re-forked per tick -- unlike a cron-driven schedule:run, this one
# actually needs restarting to pick up new code, same as the queue worker.
echo "→ Restarting queue and schedule workers (pick up new code)"
docker compose restart queue-worker schedule-worker

echo "✓ Deploy complete ($PREV_SHA → $NEW_SHA)"
