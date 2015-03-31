#!/bin/sh

#On teste le container en le lançant et en essayant de se connecter avec l'user jenkins.
jenkins=$(docker run -d -p 0.0.0.0:2222:22 -t -i wheezy64:jenkins)

ssh jenkins@localhost -p 2222
#jenkins@localhost's password: 
#Linux ci 3.2.0-4-amd64 #1 SMP Debian 3.2.54-2 x86_64
#
#The programs included with the Debian GNU/Linux system are free software;
#the exact distribution terms for each program are described in the
#individual files in /usr/share/doc/*/copyright.
#
#Debian GNU/Linux comes with ABSOLUTELY NO WARRANTY, to the extent
#permitted by applicable law.
#
#jenkins@2eb438b131f4:~$ exit
#logout
#Connection to localhost closed.