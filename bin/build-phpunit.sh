#! /bin/bash

#
# Exit on error
#
set -o errexit

#
# Setting up our variables ...
#
# PLUGINS_FOLDER="/Users/arobbins/www/wpstest/public/wp-content/plugins/"
# PLUGIN_PATH="/Users/arobbins/www/wpstest/public/wp-content/plugins/wp-shopify/"
# BUILD_FOLDER="/Users/arobbins/www/wpstest/assets/_build/wp-shopify"

GREEN='\033[0;32m'
NC='\033[0m'


#
# Clear out old tmp wp folders
#
sudo rm -rf /tmp/wordpress-tests-lib && rm -rf /tmp/wordpress
printf "${GREEN}Success: ${NC}Removed /tmp/wordpress and /tmp/wordpress-tests-lib\n"

#
# First drop testing database if it exists
#
mysqladmin -u root -p drop wps_unit_testing
printf "${GREEN}Success: ${NC}Removed testing database\n"

#
# Create the testing database
#
bash ./bin/install-wp-tests.sh wps_unit_testing root 'qp05ofilterZ!@' 127.0.0.1 latest
printf "${GREEN}Success: ${NC}Installed testing database\n"

#
# Import the test sql data
#
mysql -u root -p wps_unit_testing < ./tests/mock-data/_db/unit-testing-db-bootstrap.sql
printf "${GREEN}Success: ${NC}Imported test data\n"

#
# Create the database structure
#
mkdir /tmp/wordpress-tests-lib/data/
printf "${GREEN}Success: ${NC}Created test data folder structure\n"

#
# Change permissions
#
find /tmp/wordpress-tests-lib -type d -exec chmod 755 {} + && find /tmp/wordpress-tests-lib -type f -exec chmod 644 {} +
printf "${GREEN}Success: ${NC}Updated permissions\n"

#
# Checkout the wordpress testing data folder
#
svn co https://develop.svn.wordpress.org/tags/4.9.8/tests/phpunit/data/ /tmp/wordpress-tests-lib/data/
printf "${GREEN}Success: ${NC}Checked out data folder from SVN\n"

#
# Creates empty uploads folder if absent
#
sudo mkdir /tmp/wordpress/wp-content/uploads
printf "${GREEN}Success: ${NC}Created uploads folder\n"

#
# Creates empty uploads folder if absent
#
sudo sed -i.bak s/localhost/127.0.0.1/g /tmp/wordpress-tests-lib/wp-tests-config.php
printf "${GREEN}Success: ${NC}Replaced localhost with 127.0.0.1\n"
