ARG user
ARG uid

# Pinned by digest rather than a floating `8.4-fpm-alpine` tag -- resolves to
# PHP 8.4.23 / Alpine 3.24.1 as of this pin. Re-pin deliberately when bumping.
FROM php:8.4-fpm-alpine@sha256:913ddd6934a805429618a16aa36da47cd8a8aec8b2f111c294936ba4003fded6 AS base

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# System dependencies. icu-dev is needed explicitly here even though the old
# Debian image never listed it directly -- it came along for free as a
# transitive dependency of texlive-full/pandoc there. Alpine's packages are
# much leaner and don't pull it in, so intl would silently fail to build
# without it.
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    imagemagick \
    imagemagick-dev \
    pandoc-cli \
    unzip \
    zip \
    texlive \
    texlive-latexextra \
    texlive-pictures \
    texlive-latexrecommended \
    texlive-binextra \
    texlive-fontutils \
    texlive-plaingeneric \
    # texlive-plaingeneric is what actually ships ulem.sty (filed under
    # TeX Live's "generic" category, not "latex extra"). texmf-dist-
    # fontsrecommended is the real URW Helvetica font files the preamble's
    # helvet + \renewcommand{\familydefault}{\sfdefault} needs -- without
    # it, helvet.sty itself still loads fine (from psnfss, bundled with
    # latexrecommended), but every glyph silently falls back to `nullfont`
    # (invisible) instead of erroring, since there's no coarse
    # texlive-fontsrecommended package on Alpine at all (texlive-fontutils
    # is font *tools*, not font *files* -- a naming trap). Both gaps only
    # surfaced by compiling this app's actual rendered templates, not just
    # a synthetic file with the same \usepackage list.
    texmf-dist-fontsrecommended

# Unlike Debian's php:8.4-fpm, Alpine's official PHP image ships no compiler
# at all -- even docker-php-ext-install (not just pecl) needs $PHPIZE_DEPS
# here. linux-headers is also required specifically for the sockets
# extension (references linux/sock_diag.h, not part of musl's own headers).
# Kept as one virtual package spanning both compilation steps below so it
# can be dropped in a single line afterwards without touching runtime deps.
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl sockets && \
    printf "\n" | pecl install imagick && \
    docker-php-ext-enable imagick && \
    printf "\n" | pecl install redis && \
    docker-php-ext-enable redis && \
    apk del .build-deps && \
    rm -rf /tmp/pear

# PHP's factory defaults (upload_max_filesize=2M, post_max_size=8M) reject a
# real phone photo -- matches the client_max_body_size/request_body bump in
# docker/caddy/Caddyfile. post_max_size stays a bit above upload_max_filesize
# since a single request can carry more than one file (multi-attachment
# forms, multi-photo shares).
# printf, not echo -- Alpine's busybox `echo` doesn't process \n by default
# the way Debian's dash did, so this silently produced one malformed line.
RUN printf "upload_max_filesize=20M\npost_max_size=25M\n" > /usr/local/etc/php/conf.d/uploads.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands. Alpine's busybox
# ships addgroup/adduser natively -- no separate `shadow` package needed,
# unlike Debian's useradd/usermod which required install first. Note
# adduser's -G only takes one group (repeating it just makes the last one
# win as primary, silently dropping the first) -- the two-arg `addgroup
# user group` form is what actually adds a supplementary membership.
RUN addgroup -g $uid $user && \
    adduser -D -u $uid -G $user -h /home/$user $user && \
    addgroup $user www-data
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user

# Dev-only variant, used by the profiled php-fpm-dev compose service -- adds
# Node.js so pest-plugin-browser's shell-out to node_modules/.bin/playwright
# has a real node binary to run. Kept out of the production/base image since
# nothing there needs Node at runtime.
FROM base AS dev

USER root

# Alpine ships nodejs/npm directly (no NodeSource repo dance needed like on
# Debian). Playwright's own downloaded browser binaries are glibc-only and
# refuse to run at all on Alpine/musl -- gcompat (a glibc-ABI shim) is what
# actually makes them work here, confirmed by running the real (not
# substituted) downloaded chromium through Pest's own browser tests.
#
# A PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH env var does not exist anywhere in
# playwright-core (checked directly) -- pest-plugin-browser's own launch
# call (BrowserFactory::launch()) never sends an executablePath option at
# all, so pointing it at Alpine's native chromium package instead (e.g. via
# a fake ~/.cache/ms-playwright symlink at the path the plugin's Registry
# expects) isn't a supported override surface. That approach did get past
# the initial "Executable doesn't exist" error and mostly ran, but one test
# then failed in a way that looked like broken viewport/device emulation --
# though that specific run turned out to be confounded by an unrelated bug
# (Vite's dev server wasn't actually running in the recreated nodejs
# container, so pages had no CSS at all), never re-isolated after fixing
# that. Moot either way: gcompat runs the real, correctly-versioned browser
# with no substitution risk, so there's no reason to revisit whether the
# substitution would have been fine too.
#
# `chromium` (Alpine's native package) is still installed too, purely for
# the shared-library dependency tree it pulls in (nss, mesa, alsa, etc.) --
# gcompat alone only shims the glibc ABI gap, not the rest of a browser's
# usual runtime libraries. Its own chromium *binary* goes unused.
RUN apk add --no-cache nodejs npm chromium gcompat

# Bake the real Playwright browser into the image layer (see the base
# stage's package-lock.json-pinned-version rationale for the Debian
# equivalent of this, which used the same approach) so it survives
# `docker compose up --force-recreate` instead of only living in the
# container's writable layer. Done as root, before the final USER switch,
# so the temp install directory can actually be cleaned up afterwards.
COPY package-lock.json /tmp/package-lock.json
RUN export HOME=/home/$user && \
    PLAYWRIGHT_VERSION=$(node -pe "require('/tmp/package-lock.json').packages['node_modules/playwright'].version") && \
    npm install --no-save --prefix /tmp/playwright-cli playwright@$PLAYWRIGHT_VERSION && \
    /tmp/playwright-cli/node_modules/.bin/playwright install chromium && \
    chown -R $user:$user /home/$user/.cache && \
    rm -rf /tmp/playwright-cli /tmp/package-lock.json

USER $user
