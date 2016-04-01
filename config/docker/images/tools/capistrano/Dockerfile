FROM ruby:2.3.0

RUN apt-get -y -q update && \
	apt-get -y -q install --no-install-recommends ssh curl wget git vpnc openconnect net-tools vim --no-install-recommends && \
	rm -rf /var/lib/apt/lists/*

RUN gpg --keyserver ha.pool.sks-keyservers.net --recv-keys B42F6819007F00F88E364FD4036A9C25BF357DD4
RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates && rm -rf /var/lib/apt/lists/* \
	&& wget -O /usr/local/bin/gosu "https://github.com/tianon/gosu/releases/download/1.2/gosu-$(dpkg --print-architecture)" \
	&& wget -O /usr/local/bin/gosu.asc "https://github.com/tianon/gosu/releases/download/1.2/gosu-$(dpkg --print-architecture).asc" \
	&& gpg --verify /usr/local/bin/gosu.asc \
	&& rm /usr/local/bin/gosu.asc \
	&& chmod +x /usr/local/bin/gosu \
	&& apt-get purge -y --auto-remove ca-certificates

ENV HOME /home/deploy
WORKDIR $HOME

RUN groupadd -r deploy && useradd -r -d $HOME -g deploy deploy

COPY ./.gemrc $HOME
COPY ./Gemfile $HOME
RUN chown -R deploy:deploy $HOME

RUN gem install bundler
RUN bundle install

USER deploy

COPY ./docker-entrypoint.sh /
ENTRYPOINT ["/docker-entrypoint.sh"]