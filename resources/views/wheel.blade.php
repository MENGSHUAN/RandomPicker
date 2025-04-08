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
            background-color: #f0f0f0;
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
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 35px solid red;
            z-index: 1;
        }

        #spin-btn {
            margin-top: 40px;
            padding: 15px 30px;
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div id="wheel-container">
                <div id="pointer"></div>
                <div id="wheel">
                    <div id="center-circle"></div>
                </div>
            </div>
        </div>
        <div class="right-section">
            <p id="result"></p>
            <button id="spin-btn">開始抽籤</button>
        </div>
    </div>

    <script>
        const wheel = document.getElementById('wheel');
        const spinBtn = document.getElementById('spin-btn');
        const resultText = document.getElementById('result');
        const prizes = ['Mark', 'John', 'Mary', 'Tom', 'Jerry', 'Lisa', 'Andy', 'Lily', 'Wendy', 'Peter',
                        'Mark', 'John', 'Mary', 'Tom', 'Jerry', 'Lisa', 'Andy', 'Lily', 'Wendy', 'Peter',
                        'Mark', 'John', 'Mary', 'Tom', 'Jerry', 'Lisa', 'Andy', 'Lily', 'Wendy', 'Peter',
                        'Mark', 'John', 'Mary', 'Tom', 'Jerry', 'Lisa', 'Andy', 'Lily', 'Wendy', 'Peter'];
        const baseColors = [
            //'#4169E1', // 皇家藍
            '#FF6B6B', // 珊瑚紅
            '#FFD93D', // 明亮黃
            '#6BCB77', // 薄荷綠
            '#B4B4B8', // 淺灰色
            //'#9B59B6', // 紫羅蘭
            '#3498DB'  // 天藍色
        ]; // 基本顏色
        const anglePerSlice = 360 / prizes.length;
        let currentRotation = 0;

        // 獲取顏色的函數，確保循環使用顏色時不會相鄰
        function getColor(index) {
            const totalColors = baseColors.length;
            // 使用交錯的方式選擇顏色
            // 第一輪：0,1,2,3,4,5,6
            // 第二輪：1,2,3,4,5,6,0
            // 第三輪：2,3,4,5,6,0,1
            // 這樣確保了每個顏色都不會與前後相鄰
            const offset = Math.floor(index / totalColors); // 每輪的偏移量
            const adjustedIndex = (index + offset) % totalColors;
            return baseColors[adjustedIndex];
        }

        // 初始化轉盤樣式
        function initWheel() {
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

                // 計算文字位置和旋轉角度
                // 將文字放在扇形的角平分線上
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

            fetch('/draw', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                const prizeIndex = data.index;
                const extraSpins = 5; // 轉幾圈
                const targetAngle = (360 * extraSpins) + (360 - prizeIndex * anglePerSlice - anglePerSlice / 2);
                
                // 計算新的目標角度
                currentRotation = targetAngle % 360;
                
                // 旋轉轉盤
                wheel.style.transform = `rotate(${targetAngle}deg)`;

                setTimeout(() => {
                    resultText.textContent = `抽中了：${data.result}`;
                    spinBtn.disabled = false;
                }, 4000);
            })
            .catch(error => {
                console.error('Error:', error);
                resultText.textContent = '抽籤失敗，請重試';
                spinBtn.disabled = false;
            });
        });

        // 頁面載入時初始化轉盤
        window.onload = initWheel;
    </script>
</body>
</html> 