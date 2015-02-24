# Introduction

We give you everything you need for developing PHP applications locally. The idea came from the need of having an OS-agnostic and virtualized alternative to the great [MNPP](http://getmnpp.org/) stack as regular LAMP stacks quite simply can't keep up with the Nginx + PHP-FPM/HHVM combo in terms of performance. 
I hope you'll find it as useful an addition to your dev-arsenal as I've found it!

## What's inside

* [CoreOS](https://coreos.com/) (optional)
* [Shipyard](http://shipyard-project.com/)
* [Nginx](http://nginx.org/)
* [MySQL](http://www.mysql.com/)
* [MongoDB](http://www.mongodb.org/)
* [PHP-FPM](http://php-fpm.org/)
* [HHVM](http://www.hhvm.com/)
* [Memcached](http://memcached.org/)
* [Redis](http://redis.io/)
* [Elasticsearch](http://www.elasticsearch.org/)

## Getting started

### Requirements

* [Docker](https://docker.com/)
* [Fig](http://orchardup.github.io/fig/)
* [Vagrant](http://www.vagrantup.com/) (optional)
* [VirtualBox](https://www.virtualbox.org/) (optional)

### Install Docker
```sh
# Install Docker
$ sudo apt-get update
$ sudo apt-get install docker.io

# To verify that everything has worked as expected:
$ sudo apt-get update
$ sudo apt-get install docker.io
```

### Install Fig
```sh
# Install Docker
$ curl -L https://github.com/docker/fig/releases/download/1.0.1/fig-`uname -s`-`uname -m` > /usr/local/bin/fig; chmod +x /usr/local/bin/fig
```

### Install Vagrant
```sh
# Install dpkg and Virtual box 
$ sudo apt-get install dpkg-dev virtualbox-dkms

#  Check for the latest release
$ wget https://dl.bintray.com/mitchellh/vagrant/vagrant_1.7.2_x86_64.deb

# Install the package
$ dpkg -i vagrant_1.7.2_x86_64.deb

# Install kernel headers
$ sudo apt-get install linux-headers-$(uname -r)

# Reconfigure the VirtualBox DKMS 
$ sudo dpkg-reconfigure virtualbox-dkms

```

### Instructions

```sh
# Clone the repository (using hub)
$ git clone kasperisager/phpstack
$ cd phpstack

# (Optional) Boot up a CoreOS box
$ vagrant up

# Boot up the Docker containers
$ fig up
```

Once everything is up and running, you can access Shipyard at `http://<ip>:8080`. The default user credentials are `admin:shipyard`. Once logged in, you will need to add an engine with an address of `http://<ip>:2375` to see the local containers.

The IP is in both cases either `127.0.0.1` or your Vagrant IP (`192.168.33.10` by default).
