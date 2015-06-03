#!/bin/bash

#Web interface for browsing PHPCR repositories, using Silex and AngularJS 
#https://github.com/marmelab/phpcr-browser

DIR=$1

echo "*****Install Web interface for browsing PHPCR repositories, using Silex and AngularJS"

mkdir -p $DIR/phpcr-browser

cd /tmp
echo "***** Clone the repository "
git clone git@github.com:marmelab/phpcr-browser.git
cd phpcr-browser
cp -r * $DIR/phpcr-browser

cd $DIR/phpcr-browser
echo "***** Install dependencies and configure the browser"
sudo make install

