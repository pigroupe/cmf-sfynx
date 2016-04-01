#!/usr/bin/env bash

# delete old backups based on a date in directory name
# script that deletes all backups older than X days
# build/yyyymmddhhmm // pattern
# build/201503200100 // backup from 20. 3. 2015 1:00

BACKUPS_BUILD=$1
DAYS=$2

BACKUPS_PATH="$(dirname ${BACKUPS_BUILD})"
if [ -z "$DAYS" ]; then
  DAYS="3 days ago"
  BACKUPS_PATH=build
fi
BACKUPS_CURRENT="$BACKUPS_PATH/current"
mkdir -p $BACKUPS_CURRENT

DELETE_OTHERS=no
THRESHOLD=$(date -d "${DAYS}" +%Y%m%d%H%M)

## Find all files in $BACKUPS_PATH. The -type f means only files
## and the -maxdepth 1 ensures that any files in subdirectories are
## not included. Combined with -print0 (separate file names with \0),
## IFS= (don't break on whitespace), "-d ''" (records end on '\0') , it can
## deal with all file names.
find ${BACKUPS_PATH} -maxdepth 1 -type d -print0  | while IFS= read -d '' -r dir
do
    ## Does this dir name match the pattern
    if [[ "$(basename "$dir")" =~ ^[0-9]{12}$ ]]
    then
        ## Delete the file if it's older than the $THR
        [ "$(basename "$dir")" -le "$THRESHOLD" ] && rm -r "$dir"
        #[ "$(basename "$dir")" -le "$THRESHOLD" ] && echo "$(basename "$dir")"
        ##echo "$(basename "$dir")"
    fi
done

rm -rf $BACKUPS_CURRENT
ln -s $BACKUPS_BUILD $BACKUPS_CURRENT
