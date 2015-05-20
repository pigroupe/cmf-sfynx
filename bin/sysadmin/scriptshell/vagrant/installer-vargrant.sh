#!/bin/bash

apt-get -y update > /dev/null
apt-get -y dist-upgrade > /dev/null

# install virtualbox
if sudo apt-get -qq install virtualbox; then
    echo "virtualbox package already install"
else
    sudo apt-get -y install virtualbox
    echo "Successfully installed virtualbox"    
fi

# install vargrant
if sudo apt-get -qq install vagrant; then
    echo "vagrant package already install"
else
    sudo apt-get -y install vagrant
    echo "Successfully installed vagrant"    
fi

sudo apt-get -y install dkms
sudo apt-get -y install virtualbox-dkms
sudo apt-get -y install linux-headers-$(uname -r)

# Before attempting to run this be sure that the current running Kernel headers are installed on your system. If you don't you will receive an error indicating that you need to install them or use the --kernelsource option to point to said headers.
sudo dpkg-reconfigure virtualbox-dkms
sudo dpkg-reconfigure virtualbox

# install adapter nfs
sudo apt-get install nfs-common nfs-kernel-server

# Install the VirtualBox guest additions plugin
vagrant plugin install vagrant-vbguest



# liste of all vm
## vagrant global-status

# destroy a vm by id
## vagrant destroy <id>

# list of all box
## vagrant box list

## vagrant up
## vagrant reload --provision

# create box from existing vm
## vagrant package --base SPECIFIC_NAME_FOR_VM --output /yourfolder/OUTPUT_BOX_NAME.box

# add ubuntu box
## vagrant package –-base Ubuntu-14.04-64-Desktop  # Create Vagrant Base Box
## vagrant box add Ubuntu-14.04-64-Desktop package.box # install vagrant box
## vagrant init Ubuntu-14.04-64-Desktop
