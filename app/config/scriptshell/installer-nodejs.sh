#!/bin/bash

# Node
echo "#### Installing node"
. ~/.bashrc
export "PATH=$HOME/local/bin:$PATH"
mkdir $HOME/local
mkdir $HOME/node-latest-install
 
pushd $HOME/node-latest-install
 curl http://nodejs.org/dist/node-latest.tar.gz | tar xz -strip-components=1
 ./configure -prefix=~/local
 make install
popd
 
## the path isn't always correct, so set up a symlink
sudo ln -s /usr/bin/nodejs /usr/bin/node

## nodemon
echo "#### npming nodemon"
sudo apt-get install npm -y
sudo npm install -g nodemon
