# https://gist.github.com/alexbilbie/dcb112cdf4a6310878c5
# https://github.com/abiosoft/caddy-docker/blob/0.8.2-php/Dockerfile
# https://caddyserver.com/docs/caddyfile

FROM alpine:3.2

LABEL caddy_version="0.8.2" architecture="amd64"

RUN mkdir -p /etc/Caddyfile
RUN mkdir -p /var/log/caddy

RUN apk add --update openssh-client git tar

RUN curl --silent --show-error --fail --location \
      --header "Accept: application/tar+gzip, application/x-gzip, application/octet-stream" -o - \
      "https://caddyserver.com/download/build?os=linux&arch=amd64&features=git" \
    | tar --no-same-owner -C /usr/bin/ -xz caddy \
 && chmod 0755 /usr/bin/caddy \
 && printf "0.0.0.0\nbrowse" > /etc/Caddyfile/Caddyfile \
 && /usr/bin/caddy -version

EXPOSE 80 443 2015

ENTRYPOINT ["/usr/bin/caddy"]

CMD ["--conf", "/etc/Caddyfile/Caddyfile"]
