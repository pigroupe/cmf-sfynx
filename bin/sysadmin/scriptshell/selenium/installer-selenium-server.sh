#!/bin/sh

# log
sudo mkdir -p /var/log/selenium
sudo chmod a+w /var/log/selenium

# selenium sever upload
sudo mkdir -p /usr/lib/selenium
sudo cp -R bin/selenium/* /usr/lib/selenium
sudo chmod +x /usr/lib/selenium/selenium.sh

cd /usr/lib/selenium
sudo rm selenium-server-standalone.jar
if [ ! -f selenium-server-standalone-2.46.0.jar ]; then
     sudo wget http://selenium-release.storage.googleapis.com/2.46/selenium-server-standalone-2.46.0.jar
fi
sudo ln -s selenium-server-standalone-2.46.0.jar selenium-server-standalone.jar
sudo chmod a+x selenium-server-standalone.jar

# download chrome
wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | sudo apt-key add -
sudo sh -c 'echo deb http://dl.google.com/linux/chrome/deb/ stable main > /etc/apt/sources.list.d/google.list'

# install xvfb to start a virtual X window on a server where Selenium can start the browser in to run your tests.
sudo apt-get update && sudo apt-get install -y xfonts-100dpi xfonts-75dpi xfonts-scalable xfonts-cyrillic xvfb x11-apps  imagemagick firefox google-chrome-stable

# /usr/lib/chromium-browser/chromedriver
sudo apt-get install chromium-chromedriver
#wget -N http://chromedriver.storage.googleapis.com/2.10/chromedriver_linux64.zip -P ~/Downloads
#unzip ~/Downloads/chromedriver_linux64.zip -d ~/Downloads
#chmod +x ~/Downloads/chromedriver

# Create an alias to the script
sudo rm  /etc/init.d/selenium
sudo ln -s /usr/lib/selenium/selenium.sh /etc/init.d/selenium
sudo chmod 755 /etc/init.d/selenium

sudo rm  /etc/init.d/xvfb
sudo ln -s /usr/lib/selenium/xvfb.sh /etc/init.d/xvfb
sudo chmod 755 /etc/init.d/xvfb

# register selenium in the boot 
sudo update-rc.d selenium defaults
#sudo update-rc.d xvfb defaults

echo "You have to reboot the Ubuntu server and Selenium should be running fine"
sudo service selenium start