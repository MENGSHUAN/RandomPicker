#!/bin/sh

echo "[DEBUG] PORT is $PORT"

# 啟動 PHP-FPM
php-fpm &

# 等待 PHP-FPM 啟動
sleep 2

# 啟動 Nginx（不用再做 envsubst）
nginx -g 'daemon off;'
