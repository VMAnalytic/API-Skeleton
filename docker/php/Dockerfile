FROM php:7.2-fpm

RUN apt-get update \
    && apt-get install -y \
        git \
        libpq-dev \
        zlib1g-dev \
        cron \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
        bcmath \
        mbstring \
        zip \
        opcache \
        pdo pdo_pgsql

RUN yes | pecl install apcu xdebug-beta \
        && echo "extension=apcu.so" > /usr/local/etc/php/conf.d/apcu.ini \
        && echo "apc.enable_cli=1" >> /usr/local/etc/php/conf.d/apcu.ini \
        && echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/xdebug.ini \
        && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
        && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
        && echo "xdebug.remote_connect_back=on" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN apt-get update \
 && apt-get install -y cron \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /app
CMD ["bash", "bin/boot"]

COPY docker/php/config/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY . /app/
