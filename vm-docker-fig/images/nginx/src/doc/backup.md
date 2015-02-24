Backup/Restoring Jackrabbit Container
=====================================
If you want to backup or restore your jackrabbit container it's quite easy because all data is stored in a volume (/app/jackrabbit).

Backup
------

On the first run, you should give the container a name
```bash
docker run -name="jackrabbit" -d rootlogin/jackrabbit
```
After this you can mount his volumes in another container and make a backup to your host
```bash
docker run --rm -v $(pwd):/backup --volumes-from jackrabbit busybox tar cvf /backup/backup.tar /app/jackrabbit
```
This will create a backup.tar in your currently working directory.

Restore
-------
You only have to mount it again to busybox and restore the backup tar.
```bash
docker run --rm -v $(pwd):/backup --volumes-from jackrabbit busybox tar xvf /backup/backup.tar
```