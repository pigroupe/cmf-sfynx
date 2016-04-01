FROM python:2.7.11

RUN apt-get update && \
    apt-get install -y php5-cli && \
    apt-get install -y python-tk python-imaging-tk && \
    rm -r /var/lib/apt/lists/*

RUN mkdir -p /var

WORKDIR /var

ADD http://cpan.metacpan.org/authors/id/T/TE/TEEJAY/Autodia-2.14.tar.gz Autodia-2.14.tar.gz
ADD http://www.wspiegel.de/diacenter/diaCenter.tar.gz diaCenter.tar.gz
#ADD http://prdownloads.sourceforge.net/dia2code/dia2code-0.8.3.tar.gz dia2code-0.8.3.tar.gz
ADD http://freecode.com/urls/dcbf371a792aef0152e2d3d3faa1150b dia2sqlpy.tgz
ADD http://cpan.org/modules/by-module/Template/Template-Toolkit-2.26.tar.gz Template-Toolkit-2.26.tar.gz

RUN tar -zxvf diaCenter.tar.gz
#RUN tar -zxvf dia2code-0.8.3.tar.gz
RUN tar -zxvf Autodia-2.14.tar.gz
RUN tar -zxvf dia2sqlpy.tgz
RUN tar -zxvf Template-Toolkit-2.26.tar.gz

RUN ls -al

#WORKDIR /var
#RUN python DiaCenter.pyw

RUN mv Autodia-2.14 autodia
#RUN mv dia2code-0.8.3 dia2code
RUN mv Template-Toolkit-2.26 Template-Toolkit

WORKDIR /var/autodia
RUN perl Makefile.PL && \
    make && \
#    make test && \
    make install

#WORKDIR /var/dia2code
#RUN ./configure && \
#    make && \
#    sudo make install

WORKDIR /var/Template-Toolkit
RUN perl Makefile.PL && \
    make && \
    make test && \
    sudo make install

COPY docker-entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www

ENTRYPOINT ["/entrypoint.sh"]
