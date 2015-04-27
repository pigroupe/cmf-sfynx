#!/bin/bash

# install virtualbox
virtualbox="virtualbox"
if sudo apt-get -qq install $virtualbox; then
    echo "$virtualbox package already install"
else
    sudo apt-get install $virtualbox
    echo "Successfully installed $virtualbox"    
fi

# install vargrant
vargrantversion="vagrant_1.6.3_x86_64.deb"
if sudo dpkg -s vagrant; then
    echo "vagrant is already install"
else
    sudo wget https://dl.bintray.com/mitchellh/vagrant/vagrant_1.6.3_x86_64.deb
    sudo dpkg -i vagrant_1.6.3_x86_64.deb
    echo "Successfully installed $vargrantversion"
fi

# install adapter nfs
sudo apt-get install nfs-common nfs-kernel-server

# Install the VirtualBox guest additions plugin
vagrant plugin install vagrant-vbguest

