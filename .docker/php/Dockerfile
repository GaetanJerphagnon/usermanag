FROM php:7-apache

MAINTAINER Gaetan <jerphagnon.gaetan@gmail.com>
 
WORKDIR /var/www/usermanag

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
 
RUN apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev;
 
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen
# Install gd library from generating image from imagefttext() function
RUN docker-php-ext-install gd
# Install composer
RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer && \
   chmod +x /usr/local/bin/composer

# Install nano
RUN apt-get install nano -y

# Install yarn
RUN apt-get update && apt-get install -y gnupg2
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install -y yarn

 
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar dom mbstring zip gd xsl
RUN pecl install apcu && docker-php-ext-enable apcu
 
