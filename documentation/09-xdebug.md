Xdebug config
=====

**Xdebug config**

sudo mkdir -p /tmp/profiledir
sudo nano /usr/local/etc/php/conf.d/xdebug.ini

```
zend_extension=xdebug.so
xdebug.remote_host=172.17.42.1
xdebug.remote_enable=on
xdebug.remote_port=9003
xdebug.idekey=netbeans-xdebug
xdebug.remote_connect_back=On
xdebug.remote_handler=dbgp
xdebug.profiler_enable=0
xdebug.profiler_output_dir="/tmp/profiledir"
```

and restart http server

**Configuration Netbeans**

Go to Properties > Run configuration > Advency
and insert these values:

HOST: localhost
PORT: 9015

Go to Tools > Options > PHP > Debugging
Debugging Port : 9003
netbeans-xdebug: netbeans-xdebug
Decoch "stop at first line"

**Use**
+ put several breakpoints
+ Ctrl+F5 to start the Xdebug
+ F8 to go to the following line
+ F7 to enter to the function
+ F5 to jump to a breakpoint to an another breakpoint

### PHP STorm

**Xdebug config**

sudo mkdir -p /tmp/profiledir
sudo nano /usr/local/etc/php/conf.d/xdebug.ini

```
zend_extension=xdebug.so
xdebug.remote_host=172.17.42.1
xdebug.remote_enable=on
xdebug.remote_port=9003
xdebug.idekey=PhpStorm
xdebug.remote_connect_back=On
xdebug.remote_handler=dbgp
xdebug.profiler_enable=0
xdebug.profiler_output_dir="/tmp/profiledir"
```
