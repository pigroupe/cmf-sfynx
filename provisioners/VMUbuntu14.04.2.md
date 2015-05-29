Source : http://qanuq.com/creer-environnement-developpement-vagrant-virtualbox/

set VM_NAME='nosBelIdees-dev'
set HOMEOS="."
OS="ubuntu-14.04.2-server-amd64.iso"

# creation de la VM
VBoxManage createvm --name "$VM_NAME" --register
VBoxManage modifyvm "$VM_NAME" --ostype Ubuntu_64
VBoxManage modifyvm "$VM_NAME" --memory 8000
VBoxManage modifyvm "$VM_NAME" --boot1 dvd
VBoxManage modifyvm "$VM_NAME" --boot2 disk
VBoxManage modifyvm "$VM_NAME" --boot3 none
VBoxManage modifyvm "$VM_NAME" --boot4 none
VBoxManage modifyvm "$VM_NAME" --nic1 bridged
VBoxManage modifyvm "$VM_NAME" --bridgeadapter1 en0
VBoxManage modifyvm "$VM_NAME" --cableconnected1 on
VBoxManage modifyvm "$VM_NAME" --acpi on --ioapic on
VBoxManage modifyvm "$VM_NAME" --rtcuseutc on
VBoxManage modifyvm "$VM_NAME" --usb off
VBoxManage modifyvm "$VM_NAME" --bioslogodisplaytime 1
VBoxManage createhd --filename "$HOME/VirtualBox VMs/$VM_NAME/$VM_NAME.vdi" --size 15000
VBoxManage storagectl "$VM_NAME" --name "SATA" --add sata
VBoxManage storageattach "$VM_NAME" --storagectl "SATA" --port 0 --device 0 --type hdd --medium "$HOME/VirtualBox VMs/$VM_NAME/$VM_NAME.vdi"
VBoxManage storageattach "$VM_NAME" --storagectl "SATA" --port 1 --device 0 --type dvddrive --medium $HOMEOS/$OS
VBoxManage startvm "$VM_NAME"

Sur la VM :
sudo apt-get update
sudo apt-get install -y vim




sudo visudo
ajout de la ligne :
vagrant ALL=(ALL) NOPASSWD: ALL

Sur la machine hote :
ssh-keygen -b 2048 -t rsa -f ~/.ssh/my_vagrant_key
cat ~/.ssh/my_vagrant_key.pub | ssh vagrant@'ip de la VM' "mkdir -p -m 700 ~/.ssh; cat >> ~/.ssh/authorized_keys; chmod 600 ~/.ssh/authorized_keys"

VBoxManage storageattach "$VM_NAME" --storagectl "SATA" --port 1 --device 0 --type dvddrive --medium additions

Sur la VM :
sudo apt-get install -y dkms linux-headers-$(uname -r) build-essential
sudo mount -t iso9660 -o ro /dev/dvd /mnt
sudo /mnt/VBoxLinuxAdditions.run
sudo umount /mnt
sudo ln -s /opt/VBoxGuestAdditions-4.3.10/lib/VBoxGuestAdditions/ /usr/lib/

Sur la machine hote :
ssh -i ~/.ssh/my_vagrant_key vagrant@'ip de la VM' "rm -f .viminfo .bash_history; sudo rm -rf /root/.viminfo /root/.aptitude; sudo apt-get clean; sudo halt"
VBoxManage storageattach "$VM_NAME" --storagectl "SATA" --port 1 --device 0 --type dvddrive --medium emptydrive

Empaquetage de la VM:
vagrant package --base "$VM_NAME" --output "$VM_NAME".box


