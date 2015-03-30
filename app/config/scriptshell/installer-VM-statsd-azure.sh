# os configuration
# Get-AzureVMImage | Select ImageName
# I wanted an ubuntu 14.10 VM:
$ImageName = "b39f27a8b8c64d52b05eac6a62ebad85__Ubuntu-14_10-amd64-server-20140625-alpha1-en-us-30GB"
 
# account configuration
$ServiceName = "your-service-here"
$SubscriptionName= "your azure subscription name here"
$StorageAccount = "your storage account name here"
$Location = "your location here"
 
# vm configuration - setting up ssh keys is better, username/pwd is easier.
$user = "username"
$pwd = "p@ssword"
 
# ports
## ssh
$SSHPort = 53401 #set something specific for ssh else powershell generates a random one
 
## statsd
$StatsDInputPort = 1234
$StatsDAdminPort = 5678
 
## elasticsearch
$ElasticSearchPort = 12345
 
Set-AzureSubscription -SubscriptionName $SubscriptionName `
                    -CurrentStorageAccountName $StorageAccount
Select-AzureSubscription -SubscriptionName $SubscriptionName
 
New-AzureVMConfig -Name $ServiceName -InstanceSize Small -ImageName $ImageName `
| Add-AzureProvisioningConfig –Linux -LinuxUser $user –Password $pwd -NoSSHEndpoint `
| New-AzureVM –ServiceName $ServiceName -Location $Location
 
Get-AzureVM -ServiceName $ServiceName -Name $ServiceName `
| Add-AzureEndpoint -Name "SSH" -LocalPort 22 -PublicPort $SSHPort -Protocol tcp `
| Add-AzureEndpoint -Name "StatsDInput" -LocalPort 8125 -PublicPort $StatsDInputPort -Protocol udp `
| Add-AzureEndpoint -Name "StatsDAdmin" -LocalPort 8126 -PublicPort $StatsDAdminPort -Protocol udp `
| Add-AzureEndpoint -Name "ElasticSearch" -LocalPort 9200 -PublicPort $ElasticSearchPort -Protocol tcp `
| Update-AzureVM
 
Write-Host "now run: ssh $serviceName.cloudapp.net -p $SSHPort -l $user"
