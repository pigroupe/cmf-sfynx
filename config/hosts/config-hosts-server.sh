#!/bin/sh

IP=$1
hosts=$2
if [ -z "$hosts" ]; then
  echo "Given hosts container doesn't exist"
  exit 0
fi

echo "**** we add host in the /etc/hosts file ****"
if ! grep -q "$IP    $hosts" /etc/hosts; then
    sudo sed -i "/###DOCKER:::${hosts}/,/###ENDDOCKER/d" /etc/hosts

    echo "###DOCKER:::$hosts" |sudo tee --append /etc/hosts
    echo "$IP    $hosts" |sudo tee --append /etc/hosts
    echo "###ENDDOCKER   " |sudo tee --append /etc/hosts
fi
