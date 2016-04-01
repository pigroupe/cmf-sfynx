# Generate a new SSH key

## Creates a new ssh key, using the provided email as a label
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"

# Add your SSH key to the ssh-agent:

## Ensure ssh-agent is enabled: start the ssh-agent in the background
eval "$(ssh-agent -s)"

## Add your SSH key to the ssh-agent:
ssh-add ~/.ssh/id_rsa

# Add your SSH key to your account

## Downloads and installs xclip. If you don't have `apt-get`, you might need to use another installer (like `yum`)
sudo apt-get install xclip

## Copies the contents of the id_rsa.pub file to your clipboard
xclip -sel clip < ~/.ssh/id_rsa.pub

# Add the copied key to GitHub in Add SSH Key

# Test the connection
ssh -T git@github.com

# Warning: Permanently added to the list of known hosts” message from Git
sudo echo "UserKnownHostsFile ~/.ssh/known_hosts" > ~/.ssh/config 