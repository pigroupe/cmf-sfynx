# -*- mode: ruby -*-
# vi: set ft=ruby :
 
# Load settings
personalization = File.expand_path("../provisioners/parameters", __FILE__)
load personalization
 
# Requierements
Vagrant.require_version ">= 1.6.0"
VAGRANTFILE_API_VERSION = "2"
 
#
# Vagrant configure
#
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

    #if Vagrant.has_plugin? 'vagrant-omnibus'
    #  # Set Chef version for Omnibus
    #  config.omnibus.chef_version = :latest
    #else
    #  raise Vagrant::Errors::VagrantError.new,
    #    "vagrant-omnibus missing, please install the plugin:\n" +
    #    "vagrant plugin install vagrant-omnibus"
    #end

    # Setup box
    config.vm.box = $box_name
    config.vm.box_url = $box_url

    # Set Hostname
    config.vm.host_name = $vm_hostname

    # Set the default project share
    config.vm.synced_folder ".", $vm_mount_point, :create => true,
                                                  type: "rsync",
                                                  rsync__exclude: [
                                                    ".buildpath",
                                                    ".git",
                                                    ".project",
                                                    ".settings"
                                                  ]
    config.vm.synced_folder "./", "/var/www", create: true, group: "www-data", owner: "www-data"
 
    # Configure Virtualbox
    config.vm.provider "virtualbox" do |v|
      v.gui = $vm_gui
      v.name = $vm_name   
      v.customize ["modifyvm", :id, "--groups",          $vm_group,
                                    "--cpuexecutioncap", $vm_cpu_cap,
                                    "--memory",          $vm_memory,
                                    "--cpus",            $vm_cpus]
    end
 

    # Create a forwarded port mapping which allows access to a specific port within the machine from a port on the host machine.
    # Forward MySql port on 33066, used for connecting admin-clients to localhost:33066
    config.vm.network :forwarded_port, guest: $pf_mysql, host: $pf_mysql_localhost,  auto_correct: true

    # Forward http port on 8080, used for connecting web browsers to localhost:8585
    config.vm.network :forwarded_port, guest: $pf_http, host: $pf_http_localhost,  auto_correct: true

    # Forward https port on 443, used for connecting web browsers to localhost:443
    #config.vm.network :forwarded_port, guest: $pf_https, host: $pf_https_localhost,  auto_correct: true

    # Create a private network, which allows host-only access to the machine
    # using a specific IP.
    config.vm.network :private_network, ip: $vm_ip
     
    #This next bit fixes the 'stdin is not a tty' error when shell provisioning Ubuntu boxes
    #if there a line that only consists of 'mesg n' in /root/.profile, replace it with 'tty -s && mesg n'
    config.vm.provision :shell,    
    :inline => "(grep -q -E '^mesg n$' /root/.profile && sed -i 's/^mesg n$/tty -s \\&\\& mesg n/g' /root/.profile && echo 'Ignore the previous error about stdin not being a tty. Fixing it now...') || exit 0;"

    #config.vm.provision :shell, :inline => "sed -i 's/^mesg n$/tty -s \\&\\& mesg n/g' /root/.profile"

    # Shell provisioning
    config.vm.provision "shell" do |s|
        s.path = "provisioners/shell/bootstrap.sh"
        s.args = [$vm_mount_point, $box_distrib]
    end

    #config.vm.provision :shell, :inline => "sh /vagrant/provisioners/shell/pc/installer-pc.sh; sh /vagrant/provisioners/shell/lemp/installer-lemp.sh;"

end

# liste of all vm
## vagrant global-status

# destroy a vm by id
## vagrant destroy <id>

# list of all box
## vagrant box list

## vagrant up
## vagrant reload --provision

# create box from existing vm
## vagrant package --base SPECIFIC_NAME_FOR_VM --output /yourfolder/OUTPUT_BOX_NAME.box

# add ubuntu box
## vagrant package –-base Ubuntu-14.04-64-Desktop  # Create Vagrant Base Box
## vagrant box add Ubuntu-14.04-64-Desktop package.box # install vagrant box
## vagrant init Ubuntu-14.04-64-Desktop


# chown -R <USERNAME> /<YOUR-WEBSITES-DIRECTORY>/.vagrant/machines/
# chown -R <USERNAME> /<YOUR-HOME-DIRECTORY>/.vagrant.d
# rm  /<YOUR-HOME-DIRECTORY>/.vagrant.d/data/lock.fpcollision.lock

# http://stackoverflow.com/questions/25652769/should-vagrant-require-sudo-for-each-command
# https://github.com/Varying-Vagrant-Vagrants/VVV/issues/261
# http://stackoverflow.com/questions/27670076/permission-denied-error-for-vagrant


# rm /home/etienne/.vagrant.d/data/lock.fpcollision.lock
# find /home/etienne/.vagrant.d -exec ls -al {} \;
# rm -rf /home/etienne/.vagrant.d