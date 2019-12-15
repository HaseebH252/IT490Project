#!/usr/bin/env bash

#Backup DB machine username
vcUser="haseeb"

#Password to backup DB machine
vcPass="Today123$"

#server IP
#testing server
vcAddress="192.168.1.243"

#working server
#vcAddress="192.168.1.103"

#path dump on main db
originPath="/tmp/db_backup.sql"

#path to copy on backup db machine
destinationPath="/home/haseeb/Desktop"

if (systemctl is-active --quiet mysql.service);
then
    mysqldump -u root -ppassword Users > /tmp/db_backup.sql

    sshpass -p $vcPass scp -r $originPath $vcUser@$vcAddress:$destinationPath

    sshpass -p $vcPass ssh -T -o 'StrictHostKeyChecking no' $vcUser@$vcAddress << ENDHERE
        mysql -u test -pToday123$ Users < /home/haseeb/Desktop/db_backup.sql
ENDHERE
    echo "Backing up"
    echo "Backup Completed"
else
    echo "Main database is not active"
    echo "Backups stopped"
fi


