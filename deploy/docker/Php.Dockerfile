# Используем базовый образ PHP на Alpine
FROM php:8.4-fpm-alpine3.19 as base

# Обновление индекса пакетов и установка зависимостей
RUN apk update && apk add --no-cache \
    libzip-dev \
    zip \
    cronie \
    supervisor \
    gzip \
    wget \
    linux-headers \
    autoconf \
    g++ \
    make \
    npm \
    && rm -rf /var/cache/apk/*

# Установка PHP расширений через скрипт install-php-extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions pdo_mysql zip sockets intl imap pcntl

# Установка PHP Redis расширения через PECL
RUN install-php-extensions redis


# Попытка установить Xdebug через PECL, в случае ошибки установим из исходников
ENV PHP_IDE_CONFIG 'serverName=debug.php'

RUN set -eux; \
    if ! install-php-extensions xdebug; then \
        wget https://xdebug.org/files/xdebug-3.3.2.tgz \
        && tar -xzf xdebug-3.3.2.tgz \
        && cd xdebug-3.3.2 \
        && phpize \
        && ./configure --enable-xdebug \
        && make \
        && make install; \
        echo "zend_extension=xdebug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройки cron и прав доступа
RUN touch /var/log/cron.log \
    && chmod 0644 /var/log/cron.log \
    && printf '* * * * * www-data cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1\n#' >> /etc/cron.d/laravel-cron \
    && crontab /etc/cron.d/laravel-cron \
    && mkdir -p /var/log/supervisor /var/log/php-fpm /var/log/xdebug \
    && touch /var/log/xdebug.log \
    && chmod 0755 /var/log/xdebug.log

# Настройки PHP для Laravel
RUN echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/settings.ini

# Копирование конфигурации Supervisor
COPY deploy/supervisor/conf.d/* /etc/supervisor/conf.d/

# Рабочий каталог для Laravel
WORKDIR /var/www
# Старт через Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Очистка после установки
RUN apk del autoconf g++ make \
    && rm -rf /tmp/* /var/cache/apk/*