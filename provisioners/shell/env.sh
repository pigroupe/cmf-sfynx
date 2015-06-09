#!/bin/sh

# export INSTALL_USERNAME=$(ps -o user= -p $$ | awk '{print $1}')
export INSTALL_USERNAME="vagrant"

export INSTALL_USERGROUP="www-data"

export INSTALL_USERHOME="/home/vagrant"