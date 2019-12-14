#!/usr/bin/env bash

echo "DMZ pull engaged"
echo "Enter version number you would like to pull"
read version

#pulling username - NOT USED
clientUser="po42"

#server username
vcUser="vc"
#vcUser="vc"

#server pass
vcPass="lokos13"
#vcPass=""

#server IP
#testing server
#vcAddress="192.168.1.252"

#working server
vcAddress="192.168.1.103"

#origin path of files on Version Control server
#test path
originPath="/home/vc/Desktop/dmz/V$version/"

#working path
#originPath="/home/vc/Desktop/front-end/V$version/"

#destination path on client machine
#test path
destinationPath="/var/www/html/"

#working path
#destinationPath="/var/www/html/"

mkdir -p $destinationPath
chmod -R 777 $destinationPath
sshpass -p $vcPass scp -r $vcUser@$vcAddress:$originPath $destinationPath
