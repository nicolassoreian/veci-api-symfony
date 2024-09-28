FROM node:21.6-alpine AS node
FROM php:8.3.2-fpm-alpine

# persistent / runtime deps
RUN apk add --no-cache \
        acl \
        file \
        gettext \
        unzip

# Add dependencies
RUN apk add --update --no-cache --virtual .dd-build-deps oniguruma-dev zlib-dev libzip-dev libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev libgd libzip libpng libjpeg libpq libxml2 libxml2-dev supervisor openssl $PHPIZE_DEPS

# Install GD
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install -j "$(nproc)" gd

# Install APCU
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
        && pecl install apcu \
        && docker-php-ext-enable apcu

# Install php libraries
RUN docker-php-ext-install exif opcache xml soap mbstring pdo pdo_mysql zip sysvsem \
  && docker-php-ext-install bcmath

# Install Redis PHP
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
        && pecl install redis \
        && docker-php-ext-enable redis.so

# Add Xdebug
# RUN pecl install xdebug-3.3.1 \
  # && docker-php-ext-enable xdebug

# Clear
#RUN pecl clear-cache
#RUN apk del .dd-build-deps

# Install intl extension
RUN apk add --no-cache \
  icu-dev \
  && docker-php-ext-install -j$(nproc) intl \
  && docker-php-ext-enable intl \
  && rm -rf /tmp/*

# fix work iconv library with alphine
RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# Install GIT
RUN apk add --no-cache git

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN apk add --no-cache bash \
  && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash \
  && apk add symfony-cli
RUN mkdir /.symfony5 \
  && chown -R 501:0 /.symfony5

# COPY config
COPY ./conf/20-xdebug.ini $PHP_INI_DIR/20-xdebug.ini
COPY ./conf/php.ini $PHP_INI_DIR/php.ini
COPY ./conf/opcache.ini $PHP_INI_DIR/conf.d/opcache.ini
COPY ./cron/crontab /etc/crontabs/root

# Add php-fpm's config
RUN echo 'pm = dynamic' >> $PHP_INI_DIR/../php-fpm.d/www.conf && \
    echo 'pm.max_children = 100' >> $PHP_INI_DIR/../php-fpm.d/www.conf && \
    echo 'pm.start_servers = 32' >> $PHP_INI_DIR/../php-fpm.d/www.conf && \
    echo 'pm.min_spare_servers = 16' >> $PHP_INI_DIR/../php-fpm.d/www.conf && \
    echo 'pm.max_spare_servers = 32' >> $PHP_INI_DIR/../php-fpm.d/www.conf && \
    echo 'pm.max_requests = 200' >> $PHP_INI_DIR/../php-fpm.d/www.conf

# Add JPEGOptim and OptiPNG
RUN apk add jpegoptim optipng

# Supervisor settings
COPY ./conf/supervisor.ini /etc/supervisor.d/messenger-worker.ini

# Add NODE
COPY --from=node /usr/lib /usr/lib
COPY --from=node /usr/local/lib /usr/local/lib
COPY --from=node /usr/local/include /usr/local/include
COPY --from=node /usr/local/bin /usr/local/bin
RUN mkdir /.npm \
  && chown -R 501:0 /.npm

# Entrypoint
ADD ./docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh
ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["php-fpm"]

WORKDIR /var/www/project