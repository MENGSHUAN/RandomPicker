# Laravel 樂透抽籤系統

這是一個基於 Laravel 框架開發的樂透抽籤系統，提供兩種不同的抽籤方式：轉盤抽籤和拉霸機抽籤。

## 功能特點

### 轉盤抽籤
- 精美的轉盤動畫效果
- 隨機抽選結果
- 流暢的轉動動畫
- 即時顯示抽籤結果

### 拉霸機抽籤
- 支援多台拉霸機同時運作（最多4台）
- 可自定義抽籤選項
- 選項管理功能（新增、編輯、刪除）
- 防止重複抽中同一選項
- 可重置所有選項狀態

## 系統需求

- PHP >= 8.1
- Composer
- MySQL 或 MariaDB
- Node.js 和 NPM（用於前端資源）

## 安裝步驟

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

6. 設定資料庫
- 在 `.env` 檔案中設定資料庫連線資訊
- 執行資料庫遷移和種子資料
```bash
php artisan migrate --seed
```

7. 編譯前端資源
```bash
npm run build
```

8. 啟動開發伺服器
```bash
php artisan serve
```

## 使用說明

1. 轉盤抽籤
   - 訪問 `/wheel` 路徑
   - 點擊「開始抽籤」按鈕進行抽籤

2. 拉霸機抽籤
   - 訪問 `/slot` 路徑
   - 使用控制面板調整拉霸機數量（1-4台）
   - 點擊「開始抽籤」按鈕進行抽籤
   - 點擊「管理選項」可進入選項管理頁面

3. 選項管理
   - 在拉霸機頁面點擊「管理選項」或直接訪問 `/options` 路徑
   - 可新增、編輯、刪除抽籤選項
   - 可查看選項是否已被抽中
   - 可重置所有選項的抽中狀態

## 技術架構

- 後端框架：Laravel
- 前端技術：HTML, CSS, JavaScript, Tailwind CSS
- 資料庫：MySQL/MariaDB
- 動畫效果：CSS3, JavaScript

## 授權

本專案採用 MIT 授權條款。
