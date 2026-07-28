ARG user
ARG uid

FROM php:8.4-fpm AS base

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libmagickwand-dev \
    pandoc \
    texlive-full \
    unzip \
    zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl sockets

RUN printf "\n" | pecl install imagick && \
    docker-php-ext-enable imagick

RUN printf "\n" | pecl install redis && \
    docker-php-ext-enable redis

RUN rm -rf /tmp/pear

# PHP's factory defaults (upload_max_filesize=2M, post_max_size=8M) reject a
# real phone photo -- matches the client_max_body_size bump in
# docker/nginx/config/nginx.conf. post_max_size stays a bit above
# upload_max_filesize since a single request can carry more than one file
# (multi-attachment forms, multi-photo shares).
RUN echo "upload_max_filesize=20M\npost_max_size=25M" > /usr/local/etc/php/conf.d/uploads.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
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

ARG user

USER root

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Bake the Playwright browser (+ its OS-level libs/fonts) into the image layer
# rather than leaving them only in the container's writable layer -- the
# latter doesn't survive `docker compose up --force-recreate`, since /var/www
# is bind-mounted from the host but $HOME/.cache/ms-playwright isn't.
# Installed from the committed package-lock.json's exact resolved version
# (not just `^1.x` from package.json) so the cached browser build matches
# whatever the bind-mounted node_modules/playwright resolves to at runtime --
# this layer only re-runs when that pinned version actually changes.
# Two installs are needed: `--with-deps` (root, for the apt-installed shared
# libs) downloads its own browser copy to /root's cache, which is discarded;
# the runtime user's own plain `install chromium` (no apt needed the second
# time) is the copy actually used when tests run as $user.
COPY package-lock.json /tmp/package-lock.json
RUN PLAYWRIGHT_VERSION=$(node -pe "require('/tmp/package-lock.json').packages['node_modules/playwright'].version") && \
    npm install --no-save --prefix /opt/playwright-cli playwright@$PLAYWRIGHT_VERSION && \
    chmod -R a+rX /opt/playwright-cli && \
    /opt/playwright-cli/node_modules/.bin/playwright install --with-deps chromium && \
    rm -rf /root/.cache/ms-playwright

USER $user

RUN /opt/playwright-cli/node_modules/.bin/playwright install chromium

USER root

RUN rm -rf /opt/playwright-cli /tmp/package-lock.json

USER $user
