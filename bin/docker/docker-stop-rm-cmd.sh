#!/usr/bin/env bash

echo "running container :"
running=$(docker ps -a -f STATUS=running |grep -i _run_ |awk '{print $1}')
if [[ ! -z $running ]]; then
    docker stop $running
fi

echo "exited container :"
exited=$(docker ps -a -f STATUS=exited |grep -i _run_ |awk '{print $1}')
if [[ ! -z $exited ]]; then
    docker rm $exited
fi
