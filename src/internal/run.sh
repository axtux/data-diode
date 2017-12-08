#!/bin/bash

# Start Nginx
/etc/init.d/nginx start

# Start php7 
/etc/init.d/php7.0-fpm start

# Start mysql
/etc/init.d/mysql start
#make config
#make tables

# Start bash, preventing docker from finishing its execution
#/bin/bash
cd /var/www/html/
make run
/bin/bash
