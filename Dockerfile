FROM php:8.2-alpine

ARG PHP_EXTS="bcmath ctype intl fileinfo mbstring pdo pdo_mysql pdo_pgsql pgsql gd pcntl zip"

WORKDIR /opt/apps/toko_ujikom

RUN apk add --no-cache \
        ${PHPIZE_DEPS} \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libzip-dev \
        oniguruma-dev \
        libxml2-dev \
        icu-dev \
        postgresql-dev \
        curl \
        git \
        nodejs \
        npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) ${PHP_EXTS} \
    && pecl install ${PHP_PECL_EXTS}

RUN { \
    echo "memory_limit=1024M"; \
    echo "upload_max_filesize=100M"; \
    echo "post_max_size=100M"; \
    echo "max_execution_time=300"; \
    echo "max_input_time=300"; \
    echo "max_file_uploads=50"; \
} > /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

USER www-data

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
