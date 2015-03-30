#!/bin/sh

#Il faut maintenant créer une image qui servira de base pour notre container. On part d'un debootstrap et on installe ce qu'il faut pour lancer le slave jenkins.
sudo apt-get install debootstrap
curl -o mkimage-debootstrap.sh https://raw.githubusercontent.com/dotcloud/docker/master/contrib/mkimage-debootstrap.sh
chmod +x mkimage-debootstrap.sh
./mkimage-debootstrap -s wheezy64 wheezy http://ftp.fr.debian.org/debian/

# docker images
#REPOSITORY          TAG                 IMAGE ID            CREATED              VIRTUAL SIZE
#wheezy64            wheezy              7867d3b51969        About a minute ago   116.7 MB

cat << EOF > Dockerfile
FROM wheezy64:wheezy

RUN apt-get install -y openssh-server openjdk-7-jre-headless
RUN useradd -m -s /bin/bash jenkins
RUN echo jenkins:jenkins | chpasswd
RUN mkdir -p /var/run/sshd
EXPOSE 22
CMD /usr/sbin/sshd -D
EOF
#
docker build -t wheezy64:jenkins .
#docker images
#REPOSITORY          TAG                 IMAGE ID            CREATED             VIRTUAL SIZE
#wheezy64            jenkins             77f27af6a9d5        4 minutes ago       333.4 MB
#wheezy64            wheezy              7867d3b51969        27 minutes ago      116.7 MB