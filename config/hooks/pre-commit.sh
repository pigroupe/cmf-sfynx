#!/bin/bash
#
# PHP CodeSniffer pre-commit hook for Git
# See README.md for the Drupal-Code Sniffer configuration/setup instructions.
#
# Author: Gerald Z. Villorente
# Co-author: Nikolaos Dimopoulos
# Co-author: Engr. Ranel O. Padon
# Co-Author: Dan Helyar
#
# This project is made possible also through the collaborative support of the CNN Travel team:
#   Senior Web Development Manager:
#   Brent A. Deverman
#
#   Senior Software Engineer:
#   Adrian Christopher B. Cruz
#
#   Senior QA Analyst:
#   Jonathan A. Jacinto
#
# Special Credits to: Nikolaos Dimopoulos for his great work: http://www.niden.net/2011/11/git-pre-commit-another-check-to-ensure.html

# Define a function to exit which can reset $IFS.
clean_exit(){
  IFS=$IFSBACK
  exit 1
}

# Define a function which returns 1 if file must be analyzed otherwise it returns 0
must_skip_file() {
  for folder in ${enable_folders[@]}
  do
  	local reg="^$folder/*"
  	if [[ $1 =~ $reg ]]; then
  		return 1;
  	fi
  done
  return 0;
}

echo
echo "************************************************************************"
echo "*                                                                      *"
echo "*   GIT PRE-COMMIT HOOK FOR SYMFONY                                     *"
echo "*                                                                      *"
echo "*   In order to commit your changes, it must pass the four filters:    *"
echo "*   I. Syntax checking using PHP Linter                                *"
echo "*   II. Coding standards checking using PHP Code Sniffer               *"
echo "*   III. Blacklisted functions checking/validation.                    *"
echo "*   IV. Encoding files checking                                        *"
echo "*                                                                      *"
echo "************************************************************************"
echo


# Get hook directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Build the list of PHP blacklisted functions
checks[1]="\<var_dump("
checks[2]="\<print_r("
checks[3]="\<die("

# Blacklist Drupal's built-in debugging function
checks[4]="\<debug("

# Blacklist Devel's debugging functions
checks[5]="\<dpm("
checks[6]="\<krumo("
checks[7]="\<dpr("
checks[8]="\<dsm("
checks[9]="\<dd("
checks[10]="\<ddebug_backtrace("
checks[11]="\<dpq("
checks[12]="\<dprint_r("
checks[13]="\<drupal_debug("
checks[14]="\<dsm("
checks[15]="\<dvm("
checks[16]="\<dvr("
checks[17]="\<kpr("
checks[18]="\<kprint_r("
checks[19]="\<kdevel_print_object("
checks[20]="\<kdevel_print_object("

# Blacklist code conflicts resulting from Git merge.
checks[21]="<<<<<<<"
checks[22]=">>>>>>>"

# Get the total number of blacklisted functions.
element_count=${#checks[@]}
let "element_count += 1"

ROOT_DIR="$(pwd)/"

# Exclude Features-generated files because they should not be modified.
# Exclude contribs modules because they should not be modified.
# Exclude devel module because they contain debugging functions
# Exclude libraries because they should not be modified
filters_exclude[1]='Scenarios'
filters_exclude[2]='Resources'

# Exclude extensions we know should not be checked.
filters_exclude[7]='\.png$'
filters_exclude[8]='\.gif$'
filters_exclude[9]='\.jpg$'
filters_exclude[10]='\.ico$'
filters_exclude[11]='\.patch$'
filters_exclude[12]='\.ad$'
filters_exclude[13]='\.htaccess$'
filters_exclude[14]='\.sh$'
filters_exclude[15]='\.ttf$'
filters_exclude[16]='\.woff$'
filters_exclude[17]='\.eot$'
filters_exclude[18]='\.svg$'
filters_exclude[19]='\.xml$'

# Additional excludes specific to this project
# Exclude default_bundles files.
filters_exclude[20]='\.default_bundles.inc$'
filters_exclude[21]='Makefile'


# Join filters_include array into a single string for grep -e
sep="\\|"
grep_include=$(printf "${sep}%s" "${filters_include[@]}")
# Remove the separator from the start of the string
grep_include=${grep_include:${#sep}}
grep_include=".*\($grep_include\)"

# Join filters_exclude array into a single string for grep -v
# We use egrep for the exclude since it combines better with -v.
sep="|"
egrep_exclude=$(printf "${sep}%s" "${filters_exclude[@]}")
# Remove the separator from the start of the string
egrep_exclude=${egrep_exclude:${#sep}}
egrep_exclude=".*\($egrep_exclude\)"

LIST=$( git diff --name-only --cached --diff-filter=ACM | egrep -v "$egrep_exclude" )

skip_files[0]="Makefile"

enable_folders[0]="src"

# Display the list of files to be processed, for overview purposes.
echo
echo "File(s) to be processed/validated:"
echo

i=1

# IFS is a bash internal defines the separator for looping over a string.
IFSBACK=$IFS
IFS=$'\n'
for file in $LIST
do
  # Display the path of the file.
  # % 3 == 0 is used since the path of the file is outputted every 3rd token/line.
  echo $file
done

# This counter is used by the Code Sniffer for tracking errors.
sniffer_error_count=0

ERRORS_BUFFER=""

# PHP syntax-error free code
NO_SYNTAX_ERROR=0

# PHP syntax error code
SYNTAX_ERROR=255

# Code Sniffer error code
PHPCS_FAILED=1

# Code Sniffer success code
PHPCS_PASSED=0

for file in $LIST
do
  echo "+--------------------------------------------------------------+"
  if [[ " ${skip_files[@]} " =~ " ${file} " ]]; then
    echo "Skip file "$file
  else
    if must_skip_file ${file}; then
      echo "Skip file "$file
    else
      ##################################
      # Check for syntax error.
      ##################################

      echo
      echo
      echo
      echo "Validating: $file..."
      echo
      echo "I. Running the PHP Linter..."
      echo

      php -l $file >&2
      SYNTAX_CODE=$?

      if [ "$SYNTAX_CODE" -eq "$NO_SYNTAX_ERROR" ];then

        #################################
        # Run Drupal code sniffer
        #################################

        # Get the PHP Codesniffer bin path
        #PHPCS_BIN=$(which phpcs)
        PHPCS_BIN="./vendor/squizlabs/php_codesniffer/scripts/phpcs"

        # Default PHP error code
        PHP_CODE=0

        echo
        echo
        echo "II. Running the PHP Code Sniffer..."

        # Run the PHP Codesniffer validation
        $PHPCS_BIN --standard=Drupal $file >&2

        # Default PHPCS error code
        PHPCS_CODE=$?

        if [ "$PHPCS_CODE" == "$PHPCS_PASSED" ]; then
          echo
          echo "No formatting errors detected."

        elif [ "$PHPCS_CODE" == "$PHPCS_FAILED" ]; then
          let "sniffer_error_count += 1"

        else
          echo
          echo "Invalid operation."
          echo
          clean_exit
        fi

      elif [ "$SYNTAX_CODE" == "$SYNTAX_ERROR" ]; then
        echo
        echo "You have syntax error in your code. Please fix and commit your changes again."
        echo
        clean_exit

      else
        echo
        echo "Invalid operation."
        echo
        clean_exit
      fi

      #################################
      # Check for debugging functions
      #################################

      # Define allowed/possible file extensions that might contain debugging functions.
      EXTENSION=$(echo "$file" | egrep "\.install$|\.test$|\.inc$|\.module$|\.php$")

      if [ "$EXTENSION" != "" ]; then

          index=1
          while [ "$index" -lt "$element_count" ]
          do
              # Find the blacklisted functions in the current file.
              ERRORS=$(grep "${checks[$index]}" $ROOT_DIR$file >&1)
              if [ "$ERRORS" != "" ]; then
                  if [ "$ERRORS_BUFFER" != "" ]; then
                      ERRORS_BUFFER+="\n${checks[$index]} found in file: $file "

                  else
                      ERRORS_BUFFER="\n${checks[$index]} found in file: $file "
                  fi
              fi

              let "index += 1"
          done
      fi

      if [ "$sniffer_error_count" -gt "0" ]; then
          echo "Your commits failed to pass the PHP code sniffer validation."
          echo "Kindly fix the code sniffer notices."
          echo "Pour forcer le commit utilisez le paramètre --no-verify"
          echo
          clean_exit
      fi

      echo
      echo
      echo "III. Running the checker/validator for blacklisted functions..."

      if [ "$ERRORS_BUFFER" != "" ]; then
          echo
          echo "These errors were found in try-to-commit files: "
          echo -e $ERRORS_BUFFER
          echo
          echo "Can't commit your changes, fix the generated errors first."
          echo
          clean_exit

      else
          echo
          echo "No backlisted function(s) detected."
          echo
      fi

      echo
      echo
      echo "IV. Encoding files checking"
      ENCODING=$(file -ib $file)
      if [ "$ENCODING" != "text/plain; charset=utf-8" ]; then
        echo
        echo "This file is not encoded in UTF-8 : $file"
        clean_exit
      fi
    fi
  fi
done
