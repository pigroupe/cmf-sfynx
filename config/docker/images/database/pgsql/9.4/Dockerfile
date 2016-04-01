FROM debian:jessie

RUN apt-get update && apt-get install -y --force-yes --no-install-recommends \
        postgresql-9.4 \
        postgresql-contrib-9.4

RUN sed -i "s/#listen_addresses = 'localhost'/listen_addresses = '*'/g" /etc/postgresql/9.4/main/postgresql.conf
RUN echo "host all all 0.0.0.0/0 md5" >> /etc/postgresql/9.4/main/pg_hba.conf

# Création des roles
COPY config/crea_roles.sql /tmp/crea_roles.sql
COPY config/crea_base.sql /tmp/crea_base.sql
COPY config/crea_droits.sql /tmp/crea_droits.sql
COPY config/grant_privileges.sh /tmp/grant_privileges.sh

USER postgres
RUN service postgresql start &&\
        psql -f /tmp/crea_roles.sql &&\
        createdb -O sfynx sfynx &&\
#       psql -f /tmp/crea_base.sql &&\
        bash -c "cd /tmp && sh grant_privileges.sh sfynx"


USER root
RUN rm /tmp/crea_* /tmp/grant_privileges.sh


COPY config/supervisor/postgresql.conf /etc/supervisor/conf.d/postgresql.conf
EXPOSE 5432


