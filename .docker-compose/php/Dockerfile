FROM php:8.1.0-fpm-alpine3.15

ARG GID
ARG UID
ARG PHP_INI_FILE

ENV GID=${GID}
ENV UID=${UID}
ENV PHP_INI_FILE=${PHP_INI_FILE:-laravel.ini}

RUN apk update && apk add \
        libzip-dev \
        libxml2-dev \
        curl \
        wget \
        git \
        bash \
        zip \
        zlib-dev \
        libpng \
        libpng-dev \
        postgresql-dev \
        supervisor \
        busybox-suid \
        openssh-client\
        make \
        && docker-php-ext-install opcache pdo_pgsql pgsql gd zip

# Устанавливаем composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Crond и Supervisor
RUN mkdir /etc/supervisor.d
COPY supervisord /etc/supervisor.d/supervisord.conf
COPY schedule /home/admin/schedule.sh
COPY $PHP_INI_FILE /usr/local/etc/php/conf.d/laravel.ini

# Создаем юзера от которого будут работать все процессы
RUN addgroup --gid $GID admin
RUN adduser \
    --disabled-password \
    --gecos "" \
    --home /home/admin \
    --ingroup admin \
    --uid $UID \
    admin

# Указываем рабочую директорию для PHP
WORKDIR /var/www

RUN chown admin:admin /home/admin/schedule.sh && \
    chmod 744 /home/admin/schedule.sh

RUN chown -R admin:admin /var/www

ENV HOME /home/admin

USER admin

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor.d/supervisord.conf"]
