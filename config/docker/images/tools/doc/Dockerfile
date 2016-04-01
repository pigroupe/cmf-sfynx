FROM hub.alterway.fr/library/composer:1.0.0-alpha11-5.6

RUN apt-get update

#####################
# we install sphinx https://github.com/sonodar/docker-sphinx-build
#####################
# Install  Python, make
RUN apt-get install -y python-pip python-dev make
RUN pip install Sphinx sphinxcontrib-plantuml sphinxcontrib-blockdiag sphinxcontrib-seqdiag && \
    pip install sphinxjp.themes.basicstrap sphinxjp.themes.sphinxjp sphinx-rtd-theme sphinxjp.themes.dotted sphinxjp.themes.revealjs && \
    pip install sphinx-better-theme sphinx-readable-theme sphinxtheme.readability sphinxcontrib-phpdomain

#
RUN apt-get install -y libxml2-dev libxslt1-dev && \
    docker-php-ext-install dom

# Install XSL
RUN apt-get install -y libxslt-dev && \
    docker-php-ext-install xsl

# Install xmlrpc
RUN docker-php-ext-install xmlrpc

# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENV LOCALTIME Europe/Paris
ENV PHP_php5enmod mbstring pdo_mysql pcntl

#
RUN mkdir -p /www-doc
COPY composer.json /www-doc/composer.json
COPY docker-entrypoint.sh /entrypoint.sh

WORKDIR /www-doc

RUN /entrypoint.sh /usr/local/bin/composer.phar install --prefer-dist
RUN rm composer.json

RUN chmod a+rx /usr/local/bin/sphpdox
RUN chmod a+rx /usr/local/bin/apigen.php
RUN chmod a+rw -R $PHP_INI_DIR/conf.d/

ENTRYPOINT ["/entrypoint.sh"]
