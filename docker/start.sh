#!/bin/bash

# 設定環境變數
export PORT=${PORT:-80}
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# 建立必要的目錄
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs

# 設定權限
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# 清除快取
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 啟動 PHP-FPM
php-fpm

# 啟動 Nginx
nginx -g 'daemon off;'
