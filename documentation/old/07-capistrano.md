Deployment by capistrano
=====

## Before install ruby, then :

**Setup**
```
    gem install capistrano
    gem install capistrano_rsync_with_remote_cache
```

## Generate a new SSH key in server deployement to Gitlab/Github

**Creates a new ssh key, using the provided email as a label**
```
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
```

**Ensure ssh-agent is enabled: start the ssh-agent in the background**
```
eval "$(ssh-agent -s)"
```

**Add your SSH key to the ssh-agent**
```
ssh-add ~/.ssh/id_rsa
```

**Add the copied key**
+ Add the copied key to GitHub in "Add SSH Key"
+ Add your SSH key to gitlab in "Deploy Key"

**Test the connection**
```
ssh -T git@github.com
```

## Run ddeploy with capistrano and Docker container

**Run**
```
    cap STAGE=development|preprod|prod deploy
```

**If needed for exemple**
```
    cap STAGE=development|preprod|prod symfony:doctrine:schema:update
```
