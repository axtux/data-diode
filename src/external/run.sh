#!/bin/bash
/etc/init.d/nginx start
/etc/init.d/php7.0-fpm start

cd /var/www/html
make config
/bin/bash

