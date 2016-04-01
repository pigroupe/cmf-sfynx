FROM hub.alterway.fr/php:5.6-apache
MAINTAINER Alterway <iac@alterway.fr>

ENV PMA_VERSION 4.5.2

ADD https://files.phpmyadmin.net/phpMyAdmin/$PMA_VERSION/phpMyAdmin-$PMA_VERSION-all-languages.tar.gz /var/www/phpMyAdmin.tar.gz

RUN tar -xof phpMyAdmin.tar.gz && \
    rm -rf phpMyAdmin.tar.gz

COPY config.inc.php /var/www/phpMyAdmin-$PMA_VERSION-all-languages/
RUN chmod 755 /var/www/phpMyAdmin-$PMA_VERSION-all-languages/config.inc.php

ENV HTTPD__DocumentRoot /var/www/phpMyAdmin-$PMA_VERSION-all-languages/
ENV HTTPD_a2enmod rewrite

ENV PHP_php5enmod mbstring mysqli opcache
ENV PHP__upload_max_filesize 10G
ENV PHP__post_max_size 10G
