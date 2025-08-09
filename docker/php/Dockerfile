FROM php:8.2-fpm

# Using args from compose.yml
ARG UID
ARG GID
# Creating env vars to use later
ENV GID=${GID}
ENV UID=${UID}
# Adding symfony group and user with same rights as local
RUN addgroup --gid ${GID} --system symfony
RUN adduser --system --disabled-password --shell /bin/bash --uid ${UID} symfony
RUN adduser symfony symfony
# Then change www-data user and group by previously created symfony
RUN sed -i "s/user = www-data/user = symfony/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = symfony/g" /usr/local/etc/php-fpm.d/www.conf

# PHP / Symfony libs
RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    acl \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc
# alias to shorten php bin/console

# XDebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo 'date.timezone = "Europe/Paris"\n' > /usr/local/etc/php/conf.d/timezone.ini

# React
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g yarn

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

# MySQL
RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring

# Composer + rights
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
     && mkdir /var/www/.composer \
     && mkdir /var/www/.yarn \
     && mkdir /var/www/.cache \
     && touch /var/www/.yarnrc

# Custom php.ini
#COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/symfony

# Default command to launch when container start
CMD ["php-fpm"]