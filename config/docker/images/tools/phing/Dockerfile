FROM hub.alterway.fr/library/composer:1.0.0-alpha11-5.6

ENV LOCALTIME Europe/Paris
ENV PHP_php5enmod mbstring pdo_mysql pcntl

RUN mkdir -p /www-phing
COPY composer.json /www-phing/composer.json

WORKDIR /www-phing

RUN /entrypoint.sh /usr/local/bin/composer.phar install --prefer-dist
RUN rm composer.json

RUN chmod a+rx /www-phing/bin/phing
RUN chmod a+rw -R $PHP_INI_DIR/conf.d/

ENTRYPOINT ["/entrypoint.sh", "/www-phing/bin/phing"]
