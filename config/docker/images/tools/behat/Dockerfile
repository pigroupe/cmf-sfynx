FROM hub.alterway.fr/library/composer:1.0.0-alpha11-5.6

ENV LOCALTIME Europe/Paris
ENV PHP_php5enmod mbstring pdo_mysql pcntl

RUN mkdir -p /composer/behat
COPY composer.json /composer/behat/composer.json

WORKDIR /composer/behat

RUN /entrypoint.sh /usr/local/bin/composer.phar install --prefer-dist
RUN rm composer.json

RUN chmod a+rx /usr/local/bin/behat
RUN chmod a+rw -R $PHP_INI_DIR/conf.d/

ENTRYPOINT ["/entrypoint.sh", "/usr/local/bin/behat"]
