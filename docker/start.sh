#!/bin/sh

echo "[DEBUG] PORT is $PORT"

if [ -z "$PORT" ]; then
    export PORT=8080
fi

php-fpm &

sleep 2

# 用 template 動態產生真正的 nginx.conf
envsubst < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

nginx -g 'daemon off;'
