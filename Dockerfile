# 建置階段
FROM composer:2.7 as vendor

WORKDIR /app

COPY composer.json ./
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# 前端建置階段
FROM node:20-alpine as frontend

WORKDIR /app

COPY package.json ./
RUN npm install

COPY vite.config.js ./
COPY resources/ ./resources/
RUN npm run build

# 應用程式階段
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# 安裝必要的 PHP 擴展
RUN apk add --no-cache \
    linux-headers \
    libzip-dev \
    zip \
    unzip \
    nginx \
    && docker-php-ext-install \
    pdo_mysql \
    zip

# 安裝 Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# 複製應用程式檔案
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# 設定權限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 設定環境變數
ENV APP_ENV=production
ENV APP_DEBUG=false

# 複製環境設定檔
COPY .env.example .env

# 生成應用程式金鑰
RUN php artisan key:generate

# 清理快取
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# 設定 Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# 設定環境變數
ENV PORT=8080

# 啟動 Nginx 和 PHP-FPM
CMD sh -c "nginx -g 'daemon off;' & php-fpm" 