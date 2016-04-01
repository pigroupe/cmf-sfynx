#!/usr/bin/env bash

TMP_DIR=$1
if [ -z "$TMP_DIR" ]; then
    TMP_DIR="~/tmp"
fi
mkdir -p $TMP_DIR

# http://www.jamescoyle.net/how-to/1512-export-and-import-a-docker-image-between-nodes
# https://github.com/docker/docker/issues/3877
echo "running container :"
images=$(docker images |grep -v "<none>" |grep -v "REPOSITORY" |awk '{printf "%s:%s\n",$1, $2}')
if [[ ! -z $images ]]; then
    for image in $images
    do
        #docker save repo:tag
        echo $name
        name=$(echo $image |sed 's/:/-/g' |sed 's/\//-/g')
        docker save $image > $TMP_DIR/$name.tar
    done
fi



##!/bin/bash -ex
## Wrapper for 'docker save' fixing,
## https://github.com/dotcloud/docker/issues/3877
## In addition: this script will always save exactly one image (possibly
## multiple tags).
#
#IMAGE=$1
#TARGET=$2
#NAME=`echo $IMAGE | awk -F':' '{print $1}'`
#ID=`docker inspect $IMAGE | python -c "import sys,json; print json.load(sys.stdin)[0]['id']"`
#TAGS=`docker images --no-trunc | grep $ID | awk '{print $2}'`
#DIR=`mktemp -d --suffix=-docker-save`
#pushd $DIR
#
#docker save $ID > $TARGET
#
## Write the 'repositories' file containing all tags pointing to this image.
#echo $TAGS | python -c "import sys, json; tags = raw_input().split(); h = {'$NAME': {tag: '$ID' for tag in tags}}; print json.dumps(h)" > repositories
#cat repositories
#
## GNU tar fails, where python's tarfile succeeds.
## https://github.com/dotcloud/docker/issues/3877#issuecomment-37086616
#python -c "import tarfile; f=tarfile.open('$TARGET', 'a'); f.add('repositories'); f.close()"
#
#popd
#rm -rf $DIR
