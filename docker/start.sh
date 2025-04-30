#!/bin/sh

# 確保 PORT 環境變數存在
if [ -z "$PORT" ]; then
    export PORT=8080
fi

# 啟動 PHP-FPM
php-fpm &

# 等待 PHP-FPM 啟動
sleep 2

# 啟動 Nginx
nginx -g 'daemon off;' 