# Laravel 樂透抽籤系統

這是一個基於 Laravel 框架開發的樂透抽籤程式。

## 功能特點

### 轉盤抽籤
- 精美的轉盤動畫效果
- 隨機抽選結果
- 流暢的轉動動畫
- 即時顯示抽籤結果

## 系統需求

### 基本需求
- PHP >= 8.1
- Composer
- Node.js 和 NPM（用於前端資源）

### Docker 需求（選用）
- Docker
- Docker Compose

## 安裝步驟

### 傳統安裝方式

1. 複製專案
```bash
git clone [專案網址]
cd laravel-lottery
```

2. 安裝 PHP 依賴
```bash
composer install
```

3. 安裝前端依賴
```bash
npm install
```

4. 複製環境設定檔
```bash
cp .env.example .env
```

5. 生成應用程式金鑰
```bash
php artisan key:generate
```

6. 編譯前端資源
```bash
npm run build
```

7. 啟動開發伺服器
```bash
php artisan serve
```

### 使用 Laravel Sail 安裝

1. 複製專案
```bash
git clone [專案網址]
cd laravel-lottery
```

2. 複製環境設定檔
```bash
cp .env.example .env
```

3. 啟動 Sail 容器
```bash
./vendor/bin/sail up -d
```

4. 安裝依賴套件
```bash
./vendor/bin/sail composer install
./vendor/bin/sail npm install
```

5. 生成應用程式金鑰
```bash
./vendor/bin/sail artisan key:generate
```

6. 編譯前端資源
```bash
./vendor/bin/sail npm run build
```

## 使用說明

1. 轉盤抽籤
   - 訪問 `/wheel` 路徑
   - 點擊「開始抽籤」按鈕進行抽籤

## 專案結構

```
laravel-lottery/
├── app/                # 應用程式核心程式碼
├── bootstrap/          # 框架啟動檔案
├── config/            # 設定檔案
├── docker/            # Docker 相關設定
├── public/            # 公開目錄
├── resources/         # 前端資源
├── routes/            # 路由定義
├── storage/           # 儲存檔案
├── tests/             # 測試檔案
├── vendor/            # Composer 依賴
├── .env.example       # 環境設定範例
├── .gitignore         # Git 忽略檔案
├── composer.json      # Composer 設定
├── docker-compose.yml # Docker Compose 設定
├── Dockerfile         # Docker 映像檔設定
└── package.json       # NPM 套件設定
```

## 技術架構

- 後端框架：Laravel
- 前端技術：HTML, CSS, JavaScript, Tailwind CSS
- 動畫效果：CSS3, JavaScript
- 容器化：Laravel Sail

## 授權

本專案採用 MIT 授權條款。
