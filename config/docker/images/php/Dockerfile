FROM hub.alterway.fr/library/php:5.6-fpm-extra

RUN apt-get update

# ext curl
RUN apt-get install -y libcurl4-openssl-dev && \
    docker-php-ext-install curl

# Install mcrypt
RUN apt-get install -y libmcrypt-dev && \
    docker-php-ext-install mcrypt

# Install PDO
RUN docker-php-ext-install pdo && \
    docker-php-ext-install pdo_mysql && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql && \
    apt-get install -y libsqlite3-dev && \
    docker-php-ext-install pdo_sqlite

RUN apt-get install -y libxml2-dev libxslt1-dev && \
    docker-php-ext-install dom

# Install XSL
RUN apt-get install -y libxslt-dev && \
    docker-php-ext-install xsl

# Install xmlrpc
RUN docker-php-ext-install xmlrpc

# Install ftp and ZIP
RUN docker-php-ext-install ftp && \
    docker-php-ext-install zip

# Install intl
RUN apt-get install -y libicu-dev && \
    docker-php-ext-install intl

# Clean up vm
RUN apt-get autoremove -y && \
    apt-get clean all

# we delete the lists files
RUN rm -r /var/lib/apt/lists/*
