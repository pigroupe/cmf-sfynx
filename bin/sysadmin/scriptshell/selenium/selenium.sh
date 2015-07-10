#!/bin/bash

case "${1:-''}" in
    'start')
        if test -f /tmp/selenium.pid
        then
            echo "Selenium is already running."
        else
            #export DISPLAY=localhost:99.0 && java -jar /usr/lib/selenium/selenium-server-standalone.jar -port 5555 -trustAllSSLCertificates > /var/log/selenium/output.log 2> /var/log/selenium/error.log & echo $! > /tmp/selenium.pid

            # First lets start xvfb
            sudo /etc/init.d/xvfb stop
            sudo /etc/init.d/xvfb start

            # Now we have to set the DISPLAY env variable so Firefox and Chrome know where to open the browser.
            export DISPLAY=localhost:99.0

            # For chrome we also need to specify the Chrome driver location.:
            #java -jar -Dwebdriver.chrome.driver=/usr/lib/chromium-browser/chromedriver /usr/lib/selenium/selenium-server-standalone.jar -port 5555 -htmlSuite *googlechrome http://yoursite.com "/path/to/your/tests/Suite.html" "/tmp/chrome-results.html"
            java -jar -Dwebdriver.chrome.driver=/usr/lib/chromium-browser/chromedriver /usr/lib/selenium/selenium-server-standalone.jar -port 5555 -trustAllSSLCertificates > /var/log/selenium/output.log 2> /var/log/selenium/error.log & echo $! > /tmp/selenium.pid
            
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