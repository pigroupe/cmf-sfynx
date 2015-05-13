#!/bin/sh

export INSTALL_USERNAME=$(ps -o user= -p $$ | awk '{print $1}')

export INSTALL_USERGROUP="${INSTALL_USERNAME}"

export INSTALL_USERHOME="/home/${INSTALL_USERNAME}"

export INSTALL_USERWWW="${INSTALL_USERHOME}/www"