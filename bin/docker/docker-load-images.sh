#!/usr/bin/env bash

TMP_DIR=$1
if [ -z "$TMP_DIR" ]; then
    TMP_DIR="~/tmp"
fi
mkdir -p $TMP_DIR

# http://www.jamescoyle.net/how-to/1512-export-and-import-a-docker-image-between-nodes
# https://github.com/docker/docker/issues/3877
echo "running container :"
images=$(grep -rn "$TMP_DIR" -e ".tar" |awk '{print $3}')
if [[ ! -z $images ]]; then
    for image in $images
    do
        docker load -i $image
    done
fi
