#!/bin/sh

wget https://get.docker.io/builds/Linux/x86_64/docker-latest -O /usr/bin/docker
curl -o /etc/init.d/docker https://raw.githubusercontent.com/dotcloud/docker/master/contrib/init/sysvinit-debian/docker
chmod +x /usr/bin/docker /etc/init.d/docker
#
addgroup docker
update-rc.d -f docker defaults
#
cat << EOF > /etc/default/docker
DOCKER_OPTS="-H 127.0.0.1:4243 -H unix:///var/run/docker.sock"
EOF
#
sudo service docker restart
#
docker info
