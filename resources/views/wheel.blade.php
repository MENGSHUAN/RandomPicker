<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>轉盤抽籤</title>
    <link rel="icon" type="image/png" href="{{ asset('icons/pic.png') }}">
    <style>
        body {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color:rgb(230, 230, 239);
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1400px;
            height: 100vh;
            padding: 20px;
        }

        .left-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .right-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        #wheel-container {
            position: relative;
            width: 500px;
            height: 500px;
        }

        #wheel {
            width: 100%;
            height: 100%;
            border: 15px solid #ccc;
            border-radius: 50%;
            position: relative;
            transition: transform 4s cubic-bezier(0.33, 1, 0.68, 1);
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        .prize-text {
            position: absolute;
            left: 250px;
            top: 250px;
            transform-origin: 0 0;
            font-size: 15px;
            font-weight: bold;
            color: #333;
            white-space: nowrap;
            pointer-events: none;
            text-align: center;
        }

        #center-circle {
            position: absolute;
            width: 35px;
            height: 35px;
            background: white;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
        }

        #pointer {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translate(-50%, -80%) rotate(0deg);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 35px solid red;
            z-index: 1;
        }

        #pointer-bottom {
            position: absolute;
            left: 50%;
            top: 100%;
            transform: translate(-50%, 80%) rotate(180deg);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 35px solid red;
            z-index: 1;
        }

        #pointer-right {
            position: absolute;
            top: 50%;
            left: 100%;
            transform: translate(80%, -50%) rotate(180deg);
            width: 0;
            height: 0;
            border-top: 20px solid transparent;
            border-bottom: 20px solid transparent;
            border-left: 35px solid red;
            z-index: 1;
        }

        #pointer-left {
            position: absolute;
            top: 50%;
            left: 0%;
            transform: translate(-80%, -50%) rotate(180deg);
            width: 0;
            height: 0;
            border-top: 20px solid transparent;
            border-bottom: 20px solid transparent;
            border-right: 35px solid red;
            z-index: 1;
        }

        #spin-btn {
            padding: 0 30px;
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s;
            height: 52px;
            line-height: 52px;
        }

        #spin-btn:hover {
            background-color: #45a049;
        }

        #spin-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        #result {
            margin-bottom: 40px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-align: center;
            min-height: 36px;
        }

        .input-group {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 40px;
        }

        #prize-count {
            font-size: 18px;
            border: 2px solid #ccc;
            border-radius: 5px;
            width: 100px;
            text-align: center;
            height: 52px; /* 確保與按鈕等高 */
        }

        #prize-count:focus {
            outline: none;
            border-color: #4CAF50;
        }
    </style>
</head>
<body>
    {{-- <div style="position: absolute; top: 30px; left: 0; width: 100%; text-align: center; z-index: 10; font-size: 2.2rem; font-weight: bold; letter-spacing: 2px; color: #333;">
        轉盤抽籤 GDG 2025, 05, 21 DEMO
    </div> --}}
    <div class="container">
        <div class="left-section">
            <div id="wheel-container">
                <div id="pointer"></div>
                <div id="pointer-bottom"></div>
                <div id="pointer-right"></div>
                <div id="pointer-left"></div>
                <div id="wheel">
                    <div id="center-circle"></div>
                </div>
            </div>
        </div>
        <div class="right-section">
            <p id="result"></p>
            <div class="input-group">
                <input type="number" id="prize-count" min="1" max="100" value="12" placeholder="選項數量">
                <input type="number" id="draw-count" min="1" max="4" value="1" placeholder="抽出數量" style="font-size:18px;border:2px solid #ccc;border-radius:5px;width:100px;text-align:center;height:52px;">
                <button id="spin-btn">開始抽籤</button>
            </div>
        </div>
    </div>

    <script>
        const wheel = document.getElementById('wheel');
        const spinBtn = document.getElementById('spin-btn');
        const resultText = document.getElementById('result');
        const prizeCountInput = document.getElementById('prize-count');
        const drawCountInput = document.getElementById('draw-count');
        let prizes = [];
        const baseColors = [
            '#FF6B6B', // 珊瑚紅
            '#FFD93D', // 明亮黃
            '#6BCB77', // 薄荷綠
            '#B4B4B8', // 淺灰色
            '#3498DB'  // 天藍色
        ];
        let anglePerSlice = 360 / prizes.length;
        let currentRotation = 0;

        // 更新獎品列表
        function updatePrizes() {
            const count = parseInt(prizeCountInput.value) || 12;
            prizes = Array.from({length: count}, (_, i) => i + 1);
            anglePerSlice = 360 / prizes.length;
            initWheel();

            // 發送選項數量到後端
            fetch('/set-options', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ count })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Options set:', data);
            })
            .catch(error => {
                console.error('Error setting options:', error);
            });
        }

        // 獲取顏色的函數，確保循環使用顏色時不會相鄰
        function getColor(index) {
            const totalColors = baseColors.length;
            const offset = Math.floor(index / totalColors); // 每輪的偏移量
            const adjustedIndex = (index + offset) % totalColors;
            return baseColors[adjustedIndex];
        }

        // 初始化轉盤樣式
        function initWheel() {
            // 清除所有現有的文字元素
            const existingTexts = wheel.querySelectorAll('.prize-text');
            existingTexts.forEach(text => text.remove());

            // 設置扇形背景
            let gradient = '';
            prizes.forEach((prize, index) => {
                const startAngle = index * anglePerSlice;
                const endAngle = (index + 1) * anglePerSlice;
                gradient += `${getColor(index)} ${startAngle}deg ${endAngle}deg${index < prizes.length - 1 ? ',' : ''}`;

                // 創建並設置文字
                const text = document.createElement('div');
                text.className = 'prize-text';
                text.textContent = prize;

                // 計算文字位置和旋轉角度，將文字放在扇形的角平分線上
                const rotationAngle = startAngle + (anglePerSlice / 2);
                const radius = 220; // 調整文字到圓心的距離
                
                // 計算文字的位置（使用極座標轉換為笛卡爾座標）
                const angleInRadians = (rotationAngle - 90) * (Math.PI / 180);
                const x = Math.cos(angleInRadians) * radius;
                const y = Math.sin(angleInRadians) * radius;

                // 設置文字的變換，調整文字位置使其在扇形中央
                text.style.transform = `
                    translate(${x}px, ${y}px)
                    rotate(${rotationAngle + 90}deg)
                    translate(-50%, -50%)
                `;

                wheel.appendChild(text);
            });

            wheel.style.background = `conic-gradient(${gradient})`;
        }

        // 抽獎邏輯
        spinBtn.addEventListener('click', () => {
            spinBtn.disabled = true;
            resultText.textContent = '';

            // 移除轉盤的 transition，立即重置到初始位置
            wheel.style.transition = 'none';
            wheel.style.transform = `rotate(${currentRotation}deg)`;
            
            // 強制重繪
            wheel.offsetHeight;
            
            // 恢復 transition
            wheel.style.transition = 'transform 4s cubic-bezier(0.33, 1, 0.68, 1)';

            const drawCount = Math.max(1, Math.min(parseInt(drawCountInput.value) || 1, 4));

            fetch('/draw', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    count: parseInt(prizeCountInput.value) || 12,
                    draw_count: drawCount
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    resultText.textContent = data.error;
                    spinBtn.disabled = false;
                    return;
                }
                const n = prizes.length;
                const index1 = data.index;
                const anglePerSlice = 360 / n;
                const extraSpins = 5;
                const targetAngle = (360 * extraSpins) + (360 - index1 * anglePerSlice - anglePerSlice / 2);
                currentRotation = targetAngle % 360;
                wheel.style.transform = `rotate(${targetAngle}deg)`;

                // 指針對應 index 計算
                let indexes = [index1];
                if (drawCount >= 2) {
                    const offset = Math.round(180 / anglePerSlice);
                    indexes.push((index1 + offset) % n);
                }
                if (drawCount >= 3) {
                    const offset = Math.round(90 / anglePerSlice);
                    indexes.push((index1 + offset) % n);
                }
                if (drawCount >= 4) {
                    const offset = Math.round(270 / anglePerSlice);
                    indexes.push((index1 + offset) % n);
                }

                // 指針顯示控制
                document.getElementById('pointer').style.display = 'block';
                document.getElementById('pointer-bottom').style.display = (drawCount >= 2) ? 'block' : 'none';
                document.getElementById('pointer-right').style.display = (drawCount >= 3) ? 'block' : 'none';
                document.getElementById('pointer-left').style.display = (drawCount >= 4) ? 'block' : 'none';

                setTimeout(() => {
                    let resultStr = indexes.map((idx, i) => {
                        // if (i === 0) return `${prizes[idx]}（上）`;
                        // if (i === 1) return `${prizes[idx]}（下）`;
                        // if (i === 2) return `${prizes[idx]}（右）`;
                        // if (i === 3) return `${prizes[idx]}（左）`;
                        if (i === 0) return `${prizes[idx]}`;
                        if (i === 1) return `${prizes[idx]}`;
                        if (i === 2) return `${prizes[idx]}`;
                        if (i === 3) return `${prizes[idx]}`;
                        return prizes[idx];
                    }).join('、');
                    resultText.textContent = `抽中了：${resultStr}`;
                    spinBtn.disabled = false;
                }, 4000);
            })
            .catch(error => {
                console.error('Error:', error);
                resultText.textContent = '抽籤失敗，請重試';
                spinBtn.disabled = false;
            });
        });

        // 監聽輸入框變化
        prizeCountInput.addEventListener('change', updatePrizes);

        // 頁面載入時初始化轉盤
        window.onload = () => {
            prizeCountInput.value = 12; // 設定初始值為 12
            drawCountInput.value = 1; // 預設抽一個
            document.getElementById('pointer').style.display = 'block';
            document.getElementById('pointer-bottom').style.display = 'none';
            document.getElementById('pointer-right').style.display = 'none';
            document.getElementById('pointer-left').style.display = 'none';
            updatePrizes();
        };

        // 監聽抽出數量輸入框變化，動態顯示指針
        drawCountInput.addEventListener('change', () => {
            const drawCount = Math.max(1, Math.min(parseInt(drawCountInput.value) || 1, 4));
            drawCountInput.value = drawCount;
            document.getElementById('pointer').style.display = 'block';
            document.getElementById('pointer-bottom').style.display = (drawCount >= 2) ? 'block' : 'none';
            document.getElementById('pointer-right').style.display = (drawCount >= 3) ? 'block' : 'none';
            document.getElementById('pointer-left').style.display = (drawCount >= 4) ? 'block' : 'none';
        });
    </script>
</body>
</html> 