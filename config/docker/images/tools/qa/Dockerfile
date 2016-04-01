FROM hub.alterway.fr/library/composer:1.0.0-alpha11-5.6

RUN apt-get update && \
    apt-get install -y Graphviz && \
    rm -r /var/lib/apt/lists/*

ENV LOCALTIME Europe/Paris
ENV PHP_php5enmod mbstring pdo_mysql pcntl

RUN mkdir -p /www-phing
COPY composer.json /www-qa/composer.json

WORKDIR /www-qa

RUN /entrypoint.sh /usr/local/bin/composer.phar install --prefer-dist
RUN rm composer.json

RUN chmod a+rx /usr/local/bin/phploc
RUN chmod a+rx /usr/local/bin/phpmd
RUN chmod a+rx /usr/local/bin/phpdcd
RUN chmod a+rx /usr/local/bin/phpcpd
RUN chmod a+rx /usr/local/bin/phpcs
RUN chmod a+rx /usr/local/bin/php-cs-fixer
RUN chmod a+rx /usr/local/bin/phpmetrics
RUN chmod a+rw -R $PHP_INI_DIR/conf.d/

ENTRYPOINT ["/entrypoint.sh"]
