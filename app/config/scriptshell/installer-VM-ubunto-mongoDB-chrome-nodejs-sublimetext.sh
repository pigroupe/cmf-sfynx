#!/bin/sh
 
# http://www.marcusoft.net/2014/03/setting-up-complete-node-development.html
clear
echo "******************************************************************************"
echo "Don't go anywhere - I'm going to need your input shortly.."
read -p "[Enter to continue]"
 
### Set up dependencies
# Configure sources & repos
echo "** Updating apt-get"
sudo apt-get update -y > /dev/null
 
echo "** Installing prerequisites"
sudo apt-get install libexpat1-dev libicu-dev git build-essential curl software-properties-common python-software-properties -y > /dev/null
 
### deal with intereactive stuff first
## needs someone to hit "enter"
echo "** Adding a new repo ref - hit Enter"
sudo add-apt-repository ppa:webupd8team/sublime-text-2
 
echo "** Creating a new user; enter some details"
## needs someone to enter user details
sudo adduser developer
 
echo "******************************************************************************"
echo "OK! All done, now it's the unattended stuff. Go make coffee. Bring me one too."
read -p "[Enter to continue]"
 
### Now the unattended stuff can kick off
# For mongo db - http://docs.mongodb.org/manual/tutorial/install-mongodb-on-ubuntu/
echo "** More prerequisites for mongo and chrome"
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10 > /dev/null
sudo sh -c 'echo "deb http://downloads-distro.mongodb.org/repo/ubuntu-upstart dist 10gen" | sudo tee /etc/apt/sources.list.d/mongodb.list' > /dev/null
# For chrome - http://ubuntuforums.org/showthread.php?t=1351541
wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo apt-key add -
 
echo "** Updating apt-get again"
sudo apt-get update -y > /dev/null
 
## Go, go, gadget installations!
# chrome
echo "** Installing Chrome"
sudo apt-get install google-chrome-stable -y > /dev/null
 
# sublime-text
echo "** Installing sublimetext"
sudo apt-get install sublime-text -y > /dev/null
 
# mongo-db
echo "** Installing mongodb"
sudo apt-get install mongodb-10gen -y > /dev/null
 
# desktop!
echo "** Installing ubuntu-desktop"
sudo apt-get install ubuntu-desktop -y > /dev/null
 
# node - the right(?) way!
# http://www.joyent.com/blog/installing-node-and-npm
# https://gist.github.com/isaacs/579814
 
echo "** Installing node"
echo 'export "PATH=$HOME/local/bin:$PATH"' >> ~/.bashrc
. ~/.bashrc
mkdir ~/local
mkdir ~/node-latest-install
cd ~/node-latest-install
curl http://nodejs.org/dist/node-latest.tar.gz | tar xz --strip-components=1
./configure --prefix=~/local
make install
 
# other node goodies
sudo npm install nodemon > /dev/null
sudo npm install mocha > /dev/null
 
## shutdown message (need to start from VBox now we have a desktop env)
echo "******************************************************************************"
echo "**** All good - now quitting. Run *vagrant halt* then restart from VBox to go to desktop ****"
read -p "[Enter to shutdown]"
sudo shutdown 0
