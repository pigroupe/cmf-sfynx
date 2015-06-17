#!/bin/bash
DIR=$1
source $DIR/provisioners/shell/env.sh

# MYSQL
echo "*** MYSQL ***"

# if apache2 does no exist
if [ ! -f /etc/apache2/apache2.conf ];
then

    # Pour que le mot de passe 'root' MySQL ne soit pas demandé
    # Mais pris depuis ce qui est configuré ici :
    #sh -c "echo mysql-server mysql-server/root_password select root | debconf-set-selections"
    #sh -c "echo mysql-server mysql-server/root_password_again select root | debconf-set-selections"
    sh -c "echo mysql-server mysql-server/root_password password pacman | debconf-set-selections"
    sh -c "echo mysql-server mysql-server/root_password_again password pacman | debconf-set-selections"

    echo "Installing MySQL"
    apt-get -y install mysql-server mysql-client

    # Active l'écoute autre qu'en local
    # => Le serveur MySQL sera accessible via le réseau (depuis la machine physique, par exemple, pour s'y connecter avec un client lourd)
    echo "Accessibility server via the network"
    sudo sed -i -e 's/^bind-address.*=.*127.0.0.1.*$/#bind-address = 127.0.0.1/' /etc/mysql/my.cnf
    echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' identified by 'pacman';" | mysql --user=root --password=pacman --host=localhost
    echo "\nUser password 'root' MySQL => 'pacman'\n"
fi
