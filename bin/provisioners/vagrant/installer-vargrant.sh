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

# Before attempting to run this be sure that the current running Kernel headers are installed on your system. If you don't you will receive an error indicating that you need to install them or use the --kernelsource option to point to said headers.
sudo apt-get -y install dkms
sudo apt-get -y install virtualbox-dkms
#sudo apt-get -y install linux-headers-$(uname -r)
sudo apt-get -y install linux-headers-generic 
sudo dpkg-reconfigure virtualbox-dkms
sudo dpkg-reconfigure virtualbox

# In order to do the first, you must first remove the incompatible vb mod.  Then, follow Oracle's instructions to get the module loaded.
sudo modprobe vboxdrv

# install adapter nfs
sudo apt-get install nfs-common nfs-kernel-server

# Install the VirtualBox guest additions plugin
vagrant plugin install vagrant-vbguest

# Vagrant and local tmp folder write permissions
sudo chmod 1777 /


sudo dpkg --configure -a


# liste of all vm
## vagrant global-status
# destroy a vm by id
## vagrant destroy <id>

# list of all vagrant box
## vagrant box list
# delete vagrant box
## vagrant box --clean <BoxName>

# list of all virtualbox vms
## VBoxManage list vms
# delete virtualbox vm
## VBoxManage unregistervm --delete "Vagrant"

# vagrant up --debug
## vagrant reload --provision

# create box from existing vm
## vagrant package --base SPECIFIC_NAME_FOR_VM --output /yourfolder/OUTPUT_BOX_NAME.box

# add ubuntu box
## vagrant package –-base Ubuntu-14.04-64-Desktop  # Create Vagrant Base Box
## vagrant box add Ubuntu-14.04-64-Desktop package.box # install vagrant box
## vagrant init Ubuntu-14.04-64-Desktop

# chown -R <USERNAME> /<YOUR-WEBSITES-DIRECTORY>/.vagrant/machines/
# chown -R <USERNAME> /<YOUR-HOME-DIRECTORY>/.vagrant.d
# rm  /<YOUR-HOME-DIRECTORY>/.vagrant.d/data/lock.fpcollision.lock

# http://stackoverflow.com/questions/25652769/should-vagrant-require-sudo-for-each-command
# https://github.com/Varying-Vagrant-Vagrants/VVV/issues/261
# http://stackoverflow.com/questions/27670076/permission-denied-error-for-vagrant


# rm /home/etienne/.vagrant.d/data/lock.fpcollision.lock
# find /home/etienne/.vagrant.d -exec ls -al {} \;
# rm -rf /home/etienne/.vagrant.d

# SSH
## vagrant plugin install vagrant-vbguest
## sometime error like this
##Running provisioner: file...
##Failed to upload a file to the guest VM via SCP due to a permissions
##error. This is normally because the SSH user doesn't have permission
##to write to the destination location. Alternately, the user running
##Vagrant on the host machine may not have permission to read the file.
## solution>>>>  vagrant ssh =>  sudo chmod -R 777 /tmp => exit
