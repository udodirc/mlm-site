FROM php:8.3-fpm

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libjpeg-dev \
    libfreetype6-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Устанавливаем и включаем Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Настраиваем и устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        sockets

# Устанавливаем Redis (фиксированная версия для стабильности)
RUN pecl install redis-6.0.2 \
    && docker-php-ext-enable redis

# Копируем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копируем конфиг Xdebug
COPY ./docker/fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /api

EXPOSE 9000

CMD ["php-fpm"]
