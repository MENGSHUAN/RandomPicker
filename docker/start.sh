#!/bin/sh

# 啟動 PHP-FPM
php-fpm &

# 等待 PHP-FPM 啟動
sleep 2

# 啟動 Nginx
nginx -g 'daemon off;' 