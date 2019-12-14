#!/usr/bin/env bash

echo "Pushing dmz code..."
echo "Enter version number you would like to push"
read version

#server username
vcUser="vc"
#vcUser=""

#server pass
vcPass="lokos13"
#vcPass=""

#server IP
#testing server
#vcAddress="192.168.1.252"

#working server
vcAddress="192.168.1.103"

#origin path - path to files you are pushing
#test path
originPath="/var/www/html/IT490Project"

#working path
#originPath="/var/www/html/IT490Project"

#destination path - path for files on version control server
#test path
destinationPath="/home/vc/Desktop/dmz/V$version/."

#working path
#destinationPath="/home/vc/Desktop/front-end/V$version/."

sshpass -p $vcPass ssh -T -o 'StrictHostKeyChecking no' $vcUser@$vcAddress << ENDHERE
    mkdir -p $destinationPath
    chmod -R 777 $destinationPath
ENDHERE
sshpass -p $vcPass scp -r $originPath $vcUser@$vcAddress:$destinationPath
