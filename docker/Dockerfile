FROM php:8.2-apache
WORKDIR /usr/src/app
ENV APACHE_DOCUMENT_ROOT /usr/src/app/public

RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libzip-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd
RUN docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . ./
RUN composer i
