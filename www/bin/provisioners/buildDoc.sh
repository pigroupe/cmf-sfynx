#!/bin/sh

#$1 where the documentation will be built
targetDirectory=$1
#$2 where sphinx find source
path=$2

mkdir -p $targetDirectory

if [ -z $targetDirectory ] || [ -z $path ]
then
echo "missing parameters"
else
for namespace in $(grep -R "namespace" ${path}* | grep php:namespace | awk -F " " '{print $2}' | uniq | sed "s/;//g")
do
	sphpdox process --output="${targetDirectory}" "${namespace}" ${path}
done
fi
