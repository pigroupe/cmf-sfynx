#!/bin/sh

#
MyUSER="root"
MyPASS="pacman"
HostName="localhost"
dbName="sonar"
dbUser="sonar"
dbPass="sonar"

# sudo wget -O /etc/yum.repos.d/sonar.repo http://downloads.sourceforge.net/project/sonar-pkg/rpm/sonar.repo
# sudo yum install sonar

# sudo deb http://downloads.sourceforge.net/project/sonarpkg/deb binary/
# sudo service sonar start
# sudo apt-get install sonar

sudo apt-get install unzip

echo "*** we download sonarqube  (http://www.sonarqube.org/downloads/) ***"
cd /etc
if [ ! -f sonarqube-5.1.1.zip ];
then
    sudo wget http://downloads.sonarsource.com/sonarqube/sonarqube-5.1.1.zip
    sudo unzip sonarqube-5.1.1.zip
    sudo ln -s sonarqube-5.1.1 sonarqube
    sudo ln -s /etc/sonarqube/bin/linux-x86-64/sonar.sh /etc/init.d/sonar
    sudo mkdir -p /var/log/sonar
    sudo ln -s /etc/sonarqube/logs/sonar.log /var/log/sonar/sonar.log
fi

echo "*** We install the Sonar runner ***"
cd /etc
if [ ! -f sonar-runner-dist-2.4.zip ];
then
    sudo wget http://repo1.maven.org/maven2/org/codehaus/sonar/runner/sonar-runner-dist/2.4/sonar-runner-dist-2.4.zip
    sudo unzip sonar-runner-dist-2.4.zip
    sudo ln -s sonar-runner-2.4 sonar-runner
    sudo touch /etc/profile.d/sonar-runner.sh
fi

echo "**** we add env variables ****"
sudo bash -c "cat << EOT > /etc/profile.d/sonar-runner.sh
#!/bin/bash

export SONAR_RUNNER_HOME=/etc/sonar-runner
export PATH=$PATH:$SONAR_RUNNER_HOME/bin

EOT"
. /etc/profile.d/sonar-runner.sh

echo "**** Create an alias to the script ****"
sudo rm  /usr/local/bin/sonar-runner
sudo ln -s /etc/sonar-runner/bin/sonar-runner /usr/local/bin/sonar-runner
sudo chmod a+x /usr/local/bin/sonar-runner

echo "**** Create a empty schema; Create user and grant permissions to create, update and delete from above schema. ****"
# Delete user account if already existed
mysql -u $MyUSER -h $HostName -p$MyPASS -Bse "DROP USER '${dbName}'@'${HostName}';"
# Delete the database if already existed
mysql -u $MyUSER -h $HostName -p$MyPASS -Bse "DROP DATABASE IF EXISTS ${dbName};"
# Create  database
mysql -u $MyUSER -h $HostName -p$MyPASS -Bse "CREATE DATABASE IF NOT EXISTS ${dbName} CHARACTER SET utf8 COLLATE utf8_general_ci;"
# Create the MySQL User change $password to a real password
mysql -u $MyUSER -h $HostName -p$MyPASS -Bse "CREATE USER '${dbUser}'@'${HostName}' IDENTIFIED BY '${dbPass}';"
# Grant proper permissions to the MySQL User
mysql -u $MyUSER -h $HostName -p$MyPASS -Bse "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER ON ${dbName}.* TO '${dbUser}'@'%' IDENTIFIED BY '${dbPass}';"
mysql -u $MyUSER -h $HostName -p$MyPASS -Bse "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER ON ${dbName}.* TO '${dbUser}'@'${HostName}' IDENTIFIED BY '${dbPass}';"
mysql -u $MyUSER -h $HostName -p$MyPASS -Bse "FLUSH PRIVILEGES;"

echo "*** We  edit the Sonar and Sonar-runner config files ***"
sudo rm /etc/sonarqube/conf/sonar.properties
sudo cp sonar.properties /etc/sonarqube/conf/sonar.properties
sudo rm /etc/sonar-runner/conf/sonar-runner.properties
sudo cp sonar-runner.properties /etc/sonar-runner/conf/sonar-runner.properties

echo "*** We start the SonarQube server ***"
#sudo service sonar console
sudo service sonar start

echo "*** We check sonar version ***"
sonar-runner -v

# step 0 - Go to http://localhost:9000 and cliquer sur "Log in".
#Login : admin
#Password : admin
# Go to Setting > System > Update center > Available pluginds > PHP plugin

# step 1 - create a sonarproject.properties file in directory root of your application
# step 2 - run in root directory the following command
#     $ /etc/sonar-runner/bin/sonar-runner
# step 3 - return to http://localhost:9000 and look at see the monitoring dashboard



