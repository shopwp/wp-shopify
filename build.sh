#! /bin/bash

#
# Exit on error
#
set -o errexit

#
# Setting up our variables ...
#
PLUGINS_FOLDER="/Users/arobbins/www/wpstest/public/wp-content/plugins/"
PLUGIN_PATH="/Users/arobbins/www/wpstest/public/wp-content/plugins/wp-shopify/"
BUILD_FOLDER="/Users/arobbins/www/wpstest/assets/_build/wp-shopify"

GREEN='\033[0;32m'
NC='\033[0m'
ENV='prod'

#
# Creating a temp _build folder
#
mkdir -p $BUILD_FOLDER
printf "${GREEN}Success: ${NC}Created build folder\n"

#
# Copy all the files and folders to our temp build folder
#
rsync -ar --exclude=node_modules --exclude=.happypack --exclude=.git --exclude=.gitignore $PLUGIN_PATH $BUILD_FOLDER
printf "${GREEN}Success: ${NC}Copied plugin to build folder\n"

#
# Go into the build folder and .zip up all the files + folders
#
cd $BUILD_FOLDER
cd ..
zip -rq $BUILD_FOLDER/wp-shopify.zip ./wp-shopify
printf "${GREEN}Success: ${NC}Created .zip\n"

#
# Delete all files and folders inside _build except our new .zip
#
find $BUILD_FOLDER/* ! -name 'wp-shopify.zip' -type f -exec rm -f {} +
find $BUILD_FOLDER/* ! -name 'wp-shopify.zip' -type d -exec rm -rf {} +
printf "${GREEN}Success: ${NC}Isolated .zip\n"

#
# Copy new .zip to server
#
scp $BUILD_FOLDER/wp-shopify.zip arobbins@162.243.170.76:~
ssh -t arobbins@162.243.170.76 "sudo rm /var/www/$ENV/html/live/latest/wp-shopify.zip && sudo mv wp-shopify.zip /var/www/$ENV/html/live/latest/"
printf "${GREEN}Success: ${NC}Transfered new .zip to server\n"

#
# Remove temp build folder
#
cd $BUILD_FOLDER
cd ../../
rm -rf _build/
printf "${GREEN}Success: ${NC}Removed temp build folder\n"
printf "${GREEN}Done!\n"
