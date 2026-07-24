FROM php:8.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
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
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

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
