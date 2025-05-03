#!/bin/sh

echo "[DEBUG] PORT is $PORT"

if [ -z "$PORT" ]; then
    export PORT=8080
fi

# 啟動 PHP-FPM
php-fpm -D

# 等待 PHP-FPM 啟動
sleep 2

# 用 template 動態產生真正的 nginx.conf
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# 啟動 Nginx
nginx -g 'daemon off;'
