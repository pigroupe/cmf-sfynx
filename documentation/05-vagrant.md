Execute vagrant VM
=====

### First step
copy ./config/vagrant/paramaters.dist to ./config/vagrant/paramaters

### Second step

Edit paramaters fil with good value of path repository of the project :

```
$vm_www_point = "/var/www"
$vm_www_project_name = "dirisi"
$vm_dir_project = "/var/www/dirisi"
...
$vm_memory = "1024"
$vm_cpus = "1"
$vm_cpu_cap = "100"
$vm_ip = "192.168.33.60"
...
```

### THird step

If you have problem of ^M caracter in shell scripts, execute /bin/dos2unix from all vagrant provisioning files like this

sous linux : find . -name '*.sh' |xargs dos2unix
sous windows : 
        for /R %G in (*.sh) do dos2unix "%G"
        find /R %G in

Then, execute following commands:

```
vagrant up # to generate VM
vagrant ssh # to connect to the VM
```

List of commands
=====

**Execute vagrant file**
```
vagrant up
```

**Execute again the provisioning files**
```
vagrant reload --provision
```

**liste of all vm**
```
vagrant global-status
```

**destroy a vm by id**
```
vagrant destroy <id>
```

**list of all vagrant box**
```
vagrant box list
```

**delete vagrant box**
```
vagrant box --clean <BoxName>
```

**list of all virtualbox vms**
```
VBoxManage list vms
```

**delete virtualbox vm**
```
VBoxManage unregistervm --delete "Vagrant"
```

**box info**
```
VBoxManage showvminfo "BoxName"
```

create box from existing vm
=====

### Do like this

``` 
vagrant package --base SPECIFIC_NAME_FOR_VM --output /yourfolder/OUTPUT_BOX_NAME.box
vagrant box add OUTPUT_BOX_NAME /yourfolder/OUTPUT_BOX_NAME.box
vagrant init OUTPUT_BOX_NAME
``` 

### Exemple to add ubuntu box

``` 
vagrant package –-base Ubuntu-14.04-64-Desktop  # Create Vagrant Base Box
vagrant box add Ubuntu-14.04-64-Desktop package.box # install vagrant box
vagrant init Ubuntu-14.04-64-Desktop
``` 

permission
=====

``` 
chown -R <USERNAME> /<YOUR-WEBSITES-DIRECTORY>/.vagrant/machines/
chown -R <USERNAME> /<YOUR-HOME-DIRECTORY>/.vagrant.d
``` 

errors type
=====

**Links**
* http://stackoverflow.com/questions/25652769/should-vagrant-require-sudo-for-each-command
* https://github.com/Varying-Vagrant-Vagrants/VVV/issues/261
* http://stackoverflow.com/questions/27670076/permission-denied-error-for-vagrant

**Resolve**
* rm  /<YOUR-HOME-DIRECTORY>/.vagrant.d/data/lock.fpcollision.lock
* rm /home/<YOUR-HOME-DIRECTORY>/.vagrant.d/data/lock.fpcollision.lock
* find /home/<YOUR-HOME-DIRECTORY>/.vagrant.d -exec ls -al {} \;
* rm -rf /home/<YOUR-HOME-DIRECTORY>/.vagrant.d

SSH
=====

vagrant plugin install vagrant-vbguest
sometime error like this
Running provisioner: file...
Failed to upload a file to the guest VM via SCP due to a permissions
error. This is normally because the SSH user doesn't have permission
to write to the destination location. Alternately, the user running
Vagrant on the host machine may not have permission to read the file.
solution>>>>  vagrant ssh =>  sudo chmod -R 777 /tmp => exit

