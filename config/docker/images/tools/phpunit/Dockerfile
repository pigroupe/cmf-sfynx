FROM hub.alterway.fr/library/composer:1.0.0-alpha11-5.6

ENV LOCALTIME Europe/Paris
ENV PHP_php5enmod mbstring pdo_mysql pcntl xdebug

RUN mkdir -p /composer/phpunit
COPY composer.json /composer/phpunit/composer.json

WORKDIR /composer/phpunit

RUN /entrypoint.sh /usr/local/bin/composer.phar install --prefer-dist
RUN rm composer.json

RUN chmod a+rx /usr/local/bin/phpunit
RUN chmod a+rw -R $PHP_INI_DIR/conf.d/

ENTRYPOINT ["/entrypoint.sh", "/usr/local/bin/phpunit"]
