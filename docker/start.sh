#!/bin/sh

echo "[DEBUG] PORT is $PORT"

# 確保 PORT 環境變數存在
if [ -z "$PORT" ]; then
    export PORT=8080
fi

# 啟動 PHP-FPM
php-fpm &

# 等待 PHP-FPM 啟動
sleep 2

# 用 envsubst 替換 Nginx 配置中的 __PORT__ 為 $PORT
envsubst < /etc/nginx/nginx.conf > /etc/nginx/nginx.conf.tmp
mv /etc/nginx/nginx.conf.tmp /etc/nginx/nginx.conf

# 啟動 Nginx
nginx -g 'daemon off;'