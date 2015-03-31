#!/bin/sh

if [ $# -eq 0 ]; then # s'il n'y a pas de paramètres
    read value # on saisis la valeur ex: http://localhost:8080
else
    value=$1 # value récupère le contenue de $1, le premier paramètre
fi

#echo "$value"
# exit 1

# download client jenkins
wget ${value}/jnlpJars/jenkins-cli.jar

java -jar jenkins-cli.jar -s ${value}/ install-plugin greenballs

java -jar jenkins-cli.jar -s ${value}/ install-plugin htmlpublisher
java -jar jenkins-cli.jar -s ${value}/ install-plugin publish-over-ssh
java -jar jenkins-cli.jar -s ${value}/ install-plugin audit-trail
java -jar jenkins-cli.jar -s ${value}/ install-plugin email-ext
java -jar jenkins-cli.jar -s ${value}/ install-plugin instant-messaging
java -jar jenkins-cli.jar -s ${value}/ install-plugin jabber
java -jar jenkins-cli.jar -s ${value}/ install-plugin checkstyle
java -jar jenkins-cli.jar -s ${value}/ install-plugin cloverphp
java -jar jenkins-cli.jar -s ${value}/ install-plugin dry
java -jar jenkins-cli.jar -s ${value}/ install-plugin jdepend
java -jar jenkins-cli.jar -s ${value}/ install-plugin plot
java -jar jenkins-cli.jar -s ${value}/ install-plugin pmd
java -jar jenkins-cli.jar -s ${value}/ install-plugin tasks
java -jar jenkins-cli.jar -s ${value}/ install-plugin violations
java -jar jenkins-cli.jar -s ${value}/ install-plugin xunit
java -jar jenkins-cli.jar -s ${value}/ install-plugin phing
java -jar jenkins-cli.jar -s ${value}/ install-plugin postbuild-task
java -jar jenkins-cli.jar -s ${value}/ install-plugin build-keeper-plugin

java -jar jenkins-cli.jar -s ${value}/ install-plugin performance
java -jar jenkins-cli.jar -s ${value}/ install-plugin monitoring
java -jar jenkins-cli.jar -s ${value}/ install-plugin scm-sync-configuration
java -jar jenkins-cli.jar -s ${value}/ install-plugin svn-tag

#CI
java -jar jenkins-cli.jar -s ${value}/ install-plugin ldap
java -jar jenkins-cli.jar -s ${value}/ install-plugin mantis
java -jar jenkins-cli.jar -s ${value}/ install-plugin greenballs
java -jar jenkins-cli.jar -s ${value}/ install-plugin build-pipeline-plugin
java -jar jenkins-cli.jar -s ${value}/ install-plugin dashboard-view
java -jar jenkins-cli.jar -s ${value}/ install-plugin translation
java -jar jenkins-cli.jar -s ${value}/ install-plugin preSCMbuildstep
java -jar jenkins-cli.jar -s ${value}/ install-plugin groovy
java -jar jenkins-cli.jar -s ${value}/ install-plugin plot
java -jar jenkins-cli.jar -s ${value}/ install-plugin ansicolor
java -jar jenkins-cli.jar -s ${value}/ install-plugin simple-theme-plugin
java -jar jenkins-cli.jar -s ${value}/ install-plugin nested-view
java -jar jenkins-cli.jar -s ${value}/ install-plugin ansicolor
java -jar jenkins-cli.jar -s ${value}/ install-plugin anything-goes-formatter
java -jar jenkins-cli.jar -s ${value}/ install-plugin gitlab-merge-request-jenkins
java -jar jenkins-cli.jar -s ${value}/ install-plugin gitlab-hook
java -jar jenkins-cli.jar -s ${value}/ install-plugin sidebar-link
java -jar jenkins-cli.jar -s ${value}/ install-plugin scriptler
java -jar jenkins-cli.jar -s ${value}/ install-plugin groovy-postbuild
java -jar jenkins-cli.jar -s ${value}/ install-plugin clone-workspace-scm
java -jar jenkins-cli.jar -s ${value}/ install-plugin sitemonitor
java -jar jenkins-cli.jar -s ${value}/ install-plugin claim
java -jar jenkins-cli.jar -s ${value}/ install-plugin tap
java -jar jenkins-cli.jar -s ${value}/ install-plugin rich-text-publisher-plugin
java -jar jenkins-cli.jar -s ${value}/ install-plugin nodelabelparameter
java -jar jenkins-cli.jar -s ${value}/ install-plugin rebuild
java -jar jenkins-cli.jar -s ${value}/ install-plugin docker-build-step
java -jar jenkins-cli.jar -s ${value}/ install-plugin docker-plugin

java -jar jenkins-cli.jar -s ${value} safe-restart


#Green Balls : Changes Hudson to use green balls instead of blue for successful builds.
#HTML Publisher Plugin : This plugin publishes HTML reports.
#Publish Over SSH Plugin : Publish files and execute commands over SSH (SCP using SFTP)
#Audit Trail Plugin : Keep a log of who performed particular Jenkins operations, such as configuring jobs.
#Email-ext plugin : This plugin allows you to configure every aspect of email notifications.
#Instant Messaging Plugin : This plugin provides generic support for build notifications and a ‘bot’ via instant messaging protocols.
#Jabber Plugin : This plugin enables Jenkins to send build notifications via Jabber, as well as let users talk to Jenkins via a ‘bot’ to run commands, query build status etc..
#checkstyle : This plugin generates the trend report for Checkstyle, an open source static code analysis program.
#Clover PHP Plugin : This plugin allows you to capture code coverage reports from PHPUnit.
#DRY Plugin : This plugin generates the trend report for duplicate code checkers like CPD or Simian.
##JDepend Plugin : The JDepend Plugin is a plugin to generate JDepend reports for builds.
#Plot Plugin : This plugin provides generic plotting (or graphing) capabilities in Jenkins.
#PMD Plugin : This plugin generates the trend report for PMD, an open source static code analysis program.
#Task Scanner Plugin : This plugin scans the workspace files for open tasks and generates a trend report.
#Violations : This plug-in generates reports static code violation detectors such as checkstyle, pmd, cpd, findbugs, fxcop, stylecop and simian.
#xUnit Plugin : This plugin makes it possible to publish the test results of an execution of a testing tool in Hudson.
#Phing Plugin : This plugin allows you to use Phing to build PHP projects.
#Post build task : This plugin allows the user to execute a shell/batch task depending on the build log output.
#Build Keeper Plugin : Automatically keep every x builds to enable long term analysis trending when discarding old builds

#Performance Plugin : This plugin allows you to capture reports from JMeter and JUnit. Hudson will generate graphic charts with the trend report of performance and robustness.
#Monitoring : Monitoring of Hudson / Jenkins itself with JavaMelody.
#SCM Sync configuration plugin : Keep sync’ed your config.xml (and other ressources) jenkins/hudson files with a SCM repository
#WebSVN2 Plugin : This plugin integrates WebSVN Version 2 browser interface for Subversion with Hudson
#Subversion Tagging Plugin : This plugin automatically performs subversion tagging (technically speaking svn copy) on successful build.

