FROM php:7.3.8-apache
RUN apt-get update && apt-get upgrade -y && apt-get install -y zlib1g-dev libicu-dev g++ nano
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && a2enmod rewrite headers \
    && sed -ri -e 's/^([ \t]*)(<\/VirtualHost>)/\1\tHeader set Access-Control-Allow-Origin "*"\n\1\2/g' /etc/apache2/sites-available/*.conf
ENV TERM xterm
EXPOSE 80
