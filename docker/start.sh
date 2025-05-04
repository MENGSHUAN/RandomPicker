#!/bin/bash

# 設定環境變數
export PORT=${PORT:-80}
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# 建立必要的目錄
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs

# 設定權限（使用 chmod 而不是 chown）
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

# 生成加密金鑰
php artisan key:generate --force

# 清除快取
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 啟動 PHP-FPM
php-fpm &

# 等待 PHP-FPM 啟動
sleep 5

# 啟動 Nginx
nginx -g 'daemon off;'
