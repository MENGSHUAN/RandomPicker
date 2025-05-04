# 建置階段
FROM composer:2.7 AS vendor

WORKDIR /app

COPY composer.json ./
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# 前端建置階段
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json ./
RUN npm install

COPY vite.config.js ./
COPY resources/ ./resources/
RUN npm run build

# 應用程式階段
FROM php:8.2-fpm

WORKDIR /var/www/html

# 安裝必要的 PHP 擴展
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    nginx \
    gettext \
    && docker-php-ext-install \
    zip

# 安裝 Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# 複製應用程式檔案
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# 建立並設定必要的目錄
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && touch /var/www/html/storage/logs/laravel.log

# 設定權限
RUN chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache \
    && chmod 666 /var/www/html/storage/logs/laravel.log

# 設定環境變數
ENV APP_ENV=production
ENV APP_DEBUG=true
ENV LOG_CHANNEL=stderr
ENV CACHE_DRIVER=file
ENV SESSION_DRIVER=file

# 複製環境設定檔
COPY .env.example .env

# 複製 Nginx 設定檔
COPY docker/nginx.conf.template /etc/nginx/nginx.conf.template
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# 設定工作目錄
WORKDIR /var/www/html

# 設定啟動命令
CMD ["/start.sh"]
