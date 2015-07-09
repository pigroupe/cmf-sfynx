#!/bin/sh

# log
sudo mkdir -p /var/log/selenium
sudo chmod a+w /var/log/selenium

# selenium sever upload
sudo mkdir -p /usr/lib/selenium
cd /usr/lib/selenium

sudo rm selenium-server-standalone.jar
sudo wget http://selenium-release.storage.googleapis.com/2.46/selenium-server-standalone-2.46.0.jar
sudo ln -s selenium-server-standalone-2.46.0.jar selenium-server-standalone.jar
sudo chmod +x selenium-server-standalone.jar

# Create a service file
sudo cat >> /etc/init.d/selenium << 'EOF'
#!/bin/bash

case "${1:-''}" in
    'start')
        if test -f /tmp/selenium.pid
        then
            echo "Selenium is already running."
        else
            export DISPLAY=localhost:99.0 && java -jar /usr/lib/selenium/selenium-server-standalone.jar -port 5555 -trustAllSSLCertificates > /var/log/selenium/output.log 2> /var/log/selenium/error.log & echo $! > /tmp/selenium.pid
            echo "Starting Selenium..."

            error=$?
            if test $error -gt 0
            then
                echo "${bon}Error $error! Couldn't start Selenium!${boff}"
            fi
        fi
    ;;
    'stop')
        if test -f /tmp/selenium.pid
        then
            echo "Stopping Selenium..."
            PID=`cat /tmp/selenium.pid`
            kill -3 $PID
            if kill -9 $PID ;
                then
                    sleep 2
                    test -f /tmp/selenium.pid && rm -f /tmp/selenium.pid
                else
                    echo "Selenium could not be stopped..."
                fi
        else
            echo "Selenium is not running."
        fi
        ;;
    'restart')
        if test -f /tmp/selenium.pid
        then
            kill -HUP `cat /tmp/selenium.pid`
            test -f /tmp/selenium.pid && rm -f /tmp/selenium.pid
            sleep 1
            export DISPLAY=localhost:99.0 && java -jar /usr/lib/selenium/selenium-server-standalone.jar -port 4444 -trustAllSSLCertificates > /var/log/selenium/output.log 2> /var/log/selenium/error.log & echo $! > /tmp/selenium.pid
            echo "Reload Selenium..."
        else
            echo "Selenium isn't running..."
        fi
        ;;
    *)      # no parameter specified
        echo "Usage: $SELF start|stop|restart|reload|force-reload|status"
        exit 1
    ;;
esac
EOF

sudo chmod 755 /etc/init.d/selenium
sudo update-rc.d selenium defaults

echo "You have to reboot the Ubuntu server and Selenium should be running fine"