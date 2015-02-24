FROM tutum/nginx:latest

# Install packages
RUN apt-get update
RUN apt-get install -y supervisor curl wget php5-fpm php5-mysql php5-mcrypt php5-gd php5-memcached php5-curl php5-xdebug

# Install HHVM
RUN wget -O - http://dl.hhvm.com/conf/hhvm.gpg.key | apt-key add -
RUN echo deb http://dl.hhvm.com/ubuntu trusty main | tee /etc/apt/sources.list.d/hhvm.list
RUN apt-get update
RUN apt-get install -y hhvm

# Create required directories
RUN mkdir -p /var/log/supervisor
RUN mkdir -p /etc/nginx
RUN mkdir -p /var/run/php5-fpm
RUN mkdir -p /var/run/hhvm

# Add configuration files
ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ADD php.ini /etc/php5/fpm/conf.d/40-custom.ini

# Add and run the jackrabbit installer
ADD . /app
RUN /app/src/installer-jackrabbit.sh

# Let's run the jackrabbit
VOLUME ["/app/jackrabbit"]
WORKDIR /app
EXPOSE 8888
ENTRYPOINT ["/usr/bin/java"]
CMD ["-jar", "/app/jackrabbit-standalone.jar"]

# Expose volumes
VOLUME ["/var/www", "/etc/nginx/sites-enabled"]

EXPOSE 80 9000

CMD ["/usr/bin/supervisord"]
