<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;

class SlotController extends Controller
{
    public function slot()
    {
        $options = Option::pluck('name')->toArray();
        
        return view('slot', [
            'options' => $options
        ]);
    }

    public function draw(Request $request)
    {
        // 獲取拉霸機數量，預設為 1
        $machineCount = $request->input('count', 1);
        
        // 獲取所有未被抽過的選項
        $availableOptions = Option::where('is_drawn', false)->pluck('name')->toArray();
        
        // 檢查是否有足夠的選項可供抽取
        if ($machineCount > count($availableOptions)) {
            return response()->json([
                'error' => '拉霸機數量超過可用選項數量'
            ], 400);
        }

        // 隨機打亂選項陣列並取出需要的數量
        shuffle($availableOptions);
        $selectedOptions = array_slice($availableOptions, 0, $machineCount);

        // 為每個拉霸機生成前後文選項（確保顯示三個選項）
        $results = [];
        foreach ($selectedOptions as $mainOption) {
            // 從剩餘選項中隨機選擇前後文
            $remainingOptions = array_diff($availableOptions, [$mainOption]);
            $contextOptions = array_rand(array_flip($remainingOptions), 2);
            
            $slotOptions = [
                $contextOptions[0],
                $mainOption,
                $contextOptions[1]
            ];
            
            // 更新選項狀態為已抽過
            Option::where('name', $mainOption)->update(['is_drawn' => true]);
            
            $results[] = [
                'options' => $slotOptions,
                'result' => $mainOption
            ];
        }

        return response()->json($results);
    }

    public function resetOptions()
    {
        Option::query()->update(['is_drawn' => false]);
        return response()->json(['message' => '選項已重置']);
    }
}