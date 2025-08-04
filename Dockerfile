FROM php:8.2-fpm-alpine3.19

# Set working directory
WORKDIR /var/www

# Install dependencies & PHP extensions
RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    zip \
    libzip-dev \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    bcmath \
    intl \
    gd \
    && rm -rf /var/cache/apk/*

# Install Composer (official image)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy source files
COPY . .

# Set file permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Expose FPM port
EXPOSE 9000

CMD ["php-fpm"]
