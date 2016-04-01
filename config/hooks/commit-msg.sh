#!/bin/bash

FILESIZE=$(stat -c%s "$1")
MAX_MSG_LENGTH=200
MIN_MSG_LENGTH=15

# Check max message length
#if [ $FILESIZE -gt $MAX_MSG_LENGTH ]; then
#	echo "Message length: "$FILESIZE
#	echo "Message too long... Must be less than $MAX_MSG_LENGTH characters"
#	exit 1;
#fi

# Check min message length
if [ $FILESIZE -lt $MIN_MSG_LENGTH ]; then
	echo "Message length: "$FILESIZE
	echo "Message too short... Must be more than $MIN_MSG_LENGTH characters"
	exit 1;
fi

# Check if commit message contains REF: #
FORMAT=$(grep -q -P "REF:\s+#\d+" $1; echo $?)
if [ $FORMAT -eq 1 ]; then
	echo "Bad format! Missing 'REF: #<REDMINE ID>'"
fi
