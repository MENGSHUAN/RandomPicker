<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>拉霸機抽籤</title>
    <link rel="icon" type="image/png" href="{{ asset('icons/pic.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .slot-machine {
            width: 300px;
            height: 300px;
            border: 4px solid #333;
            border-radius: 10px;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .slot-window {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .slot-dividers {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
        }

        .slot-divider {
            position: absolute;
            width: 100%;
            height: 2px;
            background: #333;
        }

        .slot-divider:nth-child(1) {
            top: 33.33%;
        }

        .slot-divider:nth-child(2) {
            top: 66.66%;
        }

        .slot-options {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .slot-option {
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            background: white;
        }

        .indicator {
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-top: 15px solid transparent;
            border-bottom: 15px solid transparent;
            border-right: 20px solid red;
            border-left: none;
            z-index: 3;
        }

        .result {
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .result.show {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes spin {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-100%);
            }
        }

        .spinning {
            animation: spin 0.1s linear infinite;
        }

        .slot-machines-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            gap: 20px;
        }

        .slot-machine-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .control-panel {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .control-button {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            border: none;
            background: #4CAF50;
            color: white;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .control-button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .nav-button {
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
            background: #007bff;
            color: white;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .nav-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="control-panel">
        <button id="decreaseButton" class="control-button" onclick="updateMachineCount(-1)">-</button>
        <span id="machineCount" class="text-xl font-bold">1</span>
        <button id="increaseButton" class="control-button" onclick="updateMachineCount(1)">+</button>
        <a href="{{ route('options.view') }}" class="nav-button">
            管理選項
        </a>
    </div>

    <div id="slotMachinesContainer" class="slot-machines-container">
        <div class="slot-machine-wrapper">
            <div class="relative">
                <div class="slot-machine">
                    <div class="slot-dividers">
                        <div class="slot-divider"></div>
                        <div class="slot-divider"></div>
                    </div>
                    <div class="slot-window">
                        <div class="slot-options">
                            <div class="slot-option"></div>
                            <div class="slot-option"></div>
                            <div class="slot-option"></div>
                        </div>
                    </div>
                </div>
                <div class="indicator"></div>
            </div>
            <div class="mt-8 p-4 bg-gray-100 rounded-lg result">
                <p class="text-2xl font-bold text-center"></p>
            </div>
        </div>
    </div>

    <button id="spinButton" class="mt-8 px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 hover:scale-105 transition-all">
        開始抽籤
    </button>

    <script>
        const slotOptions = {!! json_encode($options ?? ['選項1', '選項2', '選項3', '選項4', '選項5']) !!};
        let isSpinning = false;
        let machineCount = 1;

        function createSlotMachine() {
            const wrapper = document.createElement('div');
            wrapper.className = 'slot-machine-wrapper';
            wrapper.innerHTML = `
                <div class="relative">
                    <div class="slot-machine">
                        <div class="slot-dividers">
                            <div class="slot-divider"></div>
                            <div class="slot-divider"></div>
                        </div>
                        <div class="slot-window">
                            <div class="slot-options">
                                <div class="slot-option"></div>
                                <div class="slot-option"></div>
                                <div class="slot-option"></div>
                            </div>
                        </div>
                    </div>
                    <div class="indicator"></div>
                </div>
                <div class="mt-8 p-4 bg-gray-100 rounded-lg result">
                    <p class="text-2xl font-bold text-center"></p>
                </div>
            `;
            return wrapper;
        }

        function updateMachineCount(change) {
            const newCount = machineCount + change;
            if (newCount >= 1 && newCount <= 4) {
                machineCount = newCount;
                document.getElementById('machineCount').textContent = machineCount;
                document.getElementById('decreaseButton').disabled = machineCount === 1;
                document.getElementById('increaseButton').disabled = machineCount === 4;
                updateSlotMachines();
            }
        }

        function updateSlotMachines() {
            const container = document.getElementById('slotMachinesContainer');
            container.innerHTML = '';
            
            for (let i = 0; i < machineCount; i++) {
                container.appendChild(createSlotMachine());
            }

            // 初始化所有拉霸機的選項
            const allSlotOptions = document.querySelectorAll('.slot-options');
            allSlotOptions.forEach(slotOption => {
                const options = generateOptions();
                updateSlotWindow(slotOption, options);
            });
        }

        function generateOptions() {
            const options = [];
            for (let i = 0; i < 3; i++) {
                const randomIndex = Math.floor(Math.random() * slotOptions.length);
                options.push(slotOptions[randomIndex]);
            }
            return options;
        }

        function updateSlotWindow(slotOptions, options) {
            const optionElements = slotOptions.querySelectorAll('.slot-option');
            options.forEach((option, index) => {
                optionElements[index].textContent = option;
            });
        }

        async function startSpinning() {
            if (isSpinning) return;
            
            isSpinning = true;
            const spinButton = document.getElementById('spinButton');
            spinButton.disabled = true;
            
            const allSlotOptions = document.querySelectorAll('.slot-options');
            const allResults = document.querySelectorAll('.result');
            
            allResults.forEach(result => result.classList.remove('show'));
            allSlotOptions.forEach(options => options.classList.add('spinning'));
            
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/slot/draw', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        count: machineCount
                    }),
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('抽籤請求失敗');
                }

                const results = await response.json();
                
                if (results.error) {
                    throw new Error(results.error);
                }
                
                setTimeout(() => {
                    results.forEach((data, index) => {
                        updateSlotWindow(allSlotOptions[index], data.options);
                        allSlotOptions[index].classList.remove('spinning');
                        
                        const resultText = allResults[index].querySelector('p');
                        resultText.textContent = `抽中：${data.result}`;
                        allResults[index].classList.add('show');
                    });
                    
                    isSpinning = false;
                    spinButton.disabled = false;
                }, 2000);
            } catch (error) {
                console.error('抽籤失敗:', error);
                allSlotOptions.forEach(options => options.classList.remove('spinning'));
                isSpinning = false;
                spinButton.disabled = false;
                alert(error.message || '抽籤失敗，請稍後再試');
            }
        }

        // 初始化按鈕狀態
        document.getElementById('decreaseButton').disabled = true;
        document.getElementById('spinButton').addEventListener('click', startSpinning);

        // 初始化第一個拉霸機
        updateSlotMachines();
    </script>
</body>
</html> 