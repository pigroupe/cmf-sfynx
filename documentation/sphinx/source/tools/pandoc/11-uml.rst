PHP UML
=======

Installation
------------

::

    pear install PHP_UML 

Generate code documentation
---------------------------

::

    phpuml src -f htmlnew -o doc/uml/htmlnew
    phpuml src -f html -o doc/uml/html
    phpuml src -f php -o doc/uml/php

Generate .xmi documentation
---------------------------

::

    phpuml src -n UMLMrMile -o doc/uml/xmi

PHUML
=====

-  https://github.com/jakobwesthoff/phuml
-  http://dasunhegoda.com/class-diagram-from-php-code-using-phuml/867/

Installation
------------

::

    sudo apt-get install graphviz
    git clone https://github.com/jakobwesthoff/phuml.git

Generate code documentation
---------------------------

::

    cd src/app/
    php phuml -r /var/www/php_app_folder -graphviz -createAssociations false -neato output_image.png

DIA logiciel
============

Install DIA
-----------

::

    sudo add-apt-repository ppa:dreibh/ppa
    sudo apt-get update
    sudo apt-get install dia

install uml2php5 dia plugin
---------------------------

::

    wget http://uml2php5.zpmag.com/DL/uml2php5-2.2.0.tar.gz
    tar -zxvf uml2php5-2.2.0.tar.gz
    sudo mv /usr/share/dia/xslt/stylesheets.xml  /usr/share/dia/xslt/stylesheets.xml.old
    sudo mv uml2php5-2.2.0/* /usr/share/dia/xslt
    sudo ln -s /usr/share/dia/xslt/TOOLS/php2uml /usr/local/bin/php2uml
    sudo rm -rf uml2php5-2.2.0.tar.gz
    sudo rm -rf uml2php5-2.2.0

Install Mono on Linux
---------------------

::

    sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 3FA7E0328081BFF6A14DA29AA6A19B38D3D831EF
    echo "deb http://download.mono-project.com/repo/debian wheezy main" | sudo tee /etc/apt/sources.list.d/mono-xamarin.list
    echo "deb http://download.mono-project.com/repo/debian wheezy-apache24-compat main" | sudo tee -a /etc/apt/sources.list.d/mono-xamarin.list
    sudo apt-get update

install uml2php
---------------

::

    git clone https://bitbucket.org/jordicabot/umltosql-umltophp-symfony-umltopython-django /var/www/uml2php

create your own XSLT template file to transform Dia UML to PHP5
---------------------------------------------------------------

First, copy the actuel template file and unzip this

::

    cd /usr/share/dia/xslt
    sudo cp /usr/share/dia/xslt/dia-uml2php5.zx /usr/share/dia/xslt/dia-uml2php5.7z
    sudo cp /usr/share/dia/xslt/dia-uml-classes.zx /usr/share/dia/xslt/dia-uml-classes.7z
    sudo 7z x /usr/share/dia/xslt/dia-uml2php5.7z
    sudo 7z x /usr/share/dia/xslt/dia-uml-classes.7z

Second, change template dia-uml2php5.xsl
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

::

    sudo nano /usr/share/dia/xslt/dia-uml2php5.xsl

Third, change this in /usr/share/dia/xslt/stylesheets.xml file

::

      <language name="UML-CLASSES-EXTENDED" stylesheet="dia-uml-classes.zx">
        <implementation name="PHP5" stylesheet="dia-uml2php5.zx"/>
        <implementation name="PHP5/WSDL/SOAP Webservices" stylesheet="dia-uml2phpsoap.zx"/>
      </language>

by this

::

      <language name="UML-CLASSES-EXTENDED" stylesheet="dia-uml-classes.xsl">
        <implementation name="PHP5" stylesheet="dia-uml2php5.xsl"/>
        <implementation name="PHP5/WSDL/SOAP Webservices" stylesheet="dia-uml2phpsoap.zx"/>
      </language>

::

    sudo nano /usr/share/dia/xslt/dia-uml-classes.xsl

Third, change configuration /usr/share/dia/xslt/dia-uml2php5.conf.xsl
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Change this

::

    <xsl:param name="AUTO_SETTERS_GETTERS"><xsl:text>OFF</xsl:text></xsl:param>

by this

::

    <xsl:param name="AUTO_SETTERS_GETTERS"><xsl:text>ON</xsl:text></xsl:param>

DiaCenter
=========

http://www.wspiegel.de/diacenter/index.html

Installation
------------

First, install python like this

::

    sudo apt-get -y install python-software-properties > /dev/null
    sudo apt-get -y install python-tk
    sudo apt-get -y install python-tix
    sudo apt-get -y install tix-dev
    sudo apt-get install python-pip
    sudo pip install graphviz

Second, execute this command

::

    perl -MCPAN -e shell

After installation finished, in the prompt, execute these commands

::

    >         o conf prerequisites_policy ask
    >         install Digest::MD5
    >         ........
    >         install Graph
    >         install GraphViz     (you may need to do a 'force install')

Third, we have to install all componants of diaCenter

::

    sudo mkdir -p /var/www/diacenter
    cd /var/www/diacenter
    wget http://www.wspiegel.de/diacenter/diaCenter.tar.gz
    wget http://prdownloads.sourceforge.net/dia2code/dia2code-0.8.3.tar.gz
    wget http://freecode.com/urls/dcbf371a792aef0152e2d3d3faa1150b dia2sqlpy.tgz
    wget http://cpan.metacpan.org/authors/id/T/TE/TEEJAY/Autodia-2.14.tar.gz
    wget http://cpan.org/modules/by-module/Template/Template-Toolkit-2.26.tar.gz

    tar -zxvf diaCenter.tar.gz
    tar -zxvf dia2code-0.8.3.tar.gz
    tar -zxvf Autodia-2.14.tar.gz
    tar -zxvf dia2sqlpy.tgz
    tar zxf Template-Toolkit-2.26.tar.gz

    cd Autodia
    perl Makefile.PL
    make
    make test
    make install

    cd ..
    cd dia2code
    ./configure
    make
    sudo make install

    cd ..
    cd Template-Toolkit-2.26
    perl Makefile.PL
    make
    make test
    sudo make install

    cd ..
    cd diaCenter
    python DiaCenter.pyw

AutoDia
-------

http://www.aarontrevena.co.uk/opensource/autodia/
https://github.com/hashbangperl/Autodia

To Install AutoDIAL:
~~~~~~~~~~~~~~~~~~~~

-  download the newest tar ball and un gzip it (ie tar -zxvf
   latest-version.tgz)
-  put it somewhere safe where it won't get sucked into @INC or anywhere
-  make sure you have Template Toolkit installed
-  If not, go to www.cpan.org or www.tt2.org and get the latest and
   follow the instructions on installing it.

To use AutoDIAL:
~~~~~~~~~~~~~~~~

-  find a perl script/module or a bunch of them.
-  type 'perl autodial.pl -i path/filename' or 'perl autodial.pl -i
   "fileA fileB FileC" -p /path/to/files/
-  you can specifiy the path with the -p option, the input files with
   the -i option and the output file with the -o function
-  you can just type autodial.pl fileA fileB fileC to use fileA, etc,
   but the other options won't work if you do that.
-  load autodial.out.xml in Dia and layout the diagram as you prefer
   (some simple layout is done by AutoDIAL).
-  Save or export the file (GIMP can read encapsulated postscript files
   which is useful).

Autodia now outputs the following formats :
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Graphviz (using dot to generate jpg, png, etc) dot vcg xvcg (using xvcg
to output postscript, etc) dia (using a new custom directed graph
algorithm to layout diagrams) HTML/XML/Anything (if you write your own
template) Experimental SpringGraph (native perl directed graphs similar
to graphviz) now included Experimental Umbrello XML/XMI (requires
fixing)

Autodia now parses the following forms of input
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Perl Python PHP Java (some issues with version 1.4) no longer fully
supported (it used to work, Java broke its API's now it doesn't, fixes
welcome) C++ Torque (XML DB schema) DBI (perl database interface
handles) SQL Umbrello (experimental)

commmands
~~~~~~~~~

"autodia ([-i filename [-p path] ] or [-d directory [-r] ]) [options]"
"autodia -i filename : use filename as input" "autodia -i 'filea fileb
filec' : use filea, fileb and filec as input" "autodia -i filename -p ..
: use ../filename as input file" "autodia -d directoryname : use *.pl/pm
in directoryname as input files" "autodia -d 'foo bar quz' : use
*\ pl/pm in directories foo, bar and quz as input files" "autodia -d
directory -r : use \*pl/pm in directory and its subdirectories as input
files" "autodia -d directory -F : use files in directory but only one
file per diagram" =item "autodia.pl -d directory -C : use files in
directory but skip CVS directories" "autodia -o outfile.xml : use
outfile.xml as output file (otherwise uses autodial.out.dia)" "autodia
-O : output to stdout" "autodia -l language : parse source as language
(ie: C) and look for appropriate filename extensions if also -d"
"autodia -t templatefile : use templatefile as template (otherwise uses
template.xml)" "autodia -l DBI -i "mysql:test:localhost" -U username -P
password : use test database on localhost with username and password as
username and password" "autodia -z : output via graphviz" "autodia -Z :
output via springgraph" "autodia -v : output via VCG " "autodia -s
skipfile : exclude files or packagenames matching those listed in file"
"autodia -D : ignore dependancies (ie do not process or display
dependancies)" "autodia -K : do not display packages that are not part
of input" "autodia -k : do not display superclasses that are not part of
input" "autodia -H : show only Public/Visible methods" "autodia -m :
show only Class methods" "autodia -M : do not show Class Methods"
"autodia -a : show only Class Attributes" "autodia -A : do not show
Class Attributes" "autodia -S : silent mode, no output to stdout except
with -O" "autodia -h : display this help message" "autodia -V : display
version and copyright message"

exemple with php language
-------------------------

::


    perl autodia.pl -l php -d /var/www/diacenter/output/Aareon/Actor/Presentation -r -o actor.presentation.dia
    perl autodia.pl -l php -d /var/www/diacenter/output/Aareon/Actor/Application -r -o actor.application.dia
    perl autodia.pl -l php -d /var/www/diacenter/output/Aareon/Actor/Domain -r -o actor.domain.dia
    perl autodia.pl -l php -d /var/www/diacenter/output/Aareon/Actor/Infrastructure -r -o actor.infrastructure.dia
    perl autodia.pl -l php -d /var/www/diacenter/output/Aareon/Actor/Infrastructure/Persistence -r -o actor.infrastructure.persistence.dia
