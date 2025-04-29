FROM php:8.3-fpm

RUN apt-get update && apt-get install  -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libjpeg-dev\
    libfreetype6-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./docker/fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

EXPOSE 9000

CMD ["php-fpm"]
