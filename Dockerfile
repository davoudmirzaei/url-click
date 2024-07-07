FROM php:8.2-fpm-alpine

WORKDIR /var/www

RUN apk add --update --no-cache \
    build-base \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    vim \
    unzip \
    git \
    curl \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    redis

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure zip

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN apk add gmp-dev

RUN docker-php-ext-install pdo pdo_mysql gmp bcmath

RUN rm -rf /var/cache/apk/*

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 ira && adduser -u 1000 -G ira -s /bin/sh -D ira

USER ira

COPY --chown=ira:ira . .

RUN chmod -R ug+rwx storage bootstrap/cache

RUN chmod -R 664 /var/www/composer.json

# Expose port 9000 and start php-fpm server
EXPOSE 9000

RUN chmod 755 ./docker/entrypoint*

ENTRYPOINT ["./docker/entrypoint-app.sh"]
