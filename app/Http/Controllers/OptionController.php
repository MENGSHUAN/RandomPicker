<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    /**
     * 顯示所有選項
     */
    public function index()
    {
        $options = Option::all();
        return response()->json($options);
    }

    /**
     * 顯示所有選項的視圖
     */
    public function view()
    {
        $options = Option::orderBy('is_drawn', 'desc')
                        ->orderBy('id', 'asc')
                        ->get();
        return view('options.index', compact('options'));
    }

    /**
     * 儲存新選項
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // 處理 checkbox 的值，未勾選時設為 false
        $validated['is_drawn'] = $request->has('is_drawn');

        $option = Option::create($validated);

        if ($request->expectsJson()) {
            return response()->json($option, 201);
        }

        return redirect()->route('options.view')->with('success', '選項已新增');
    }

    /**
     * 顯示特定選項
     */
    public function show(Option $option)
    {
        return response()->json($option);
    }

    /**
     * 更新選項
     */
    public function update(Request $request, Option $option)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
        ]);

        // 處理 checkbox 的值，未勾選時設為 false
        $validated['is_drawn'] = $request->has('is_drawn');

        $option->update($validated);
        return redirect()->route('options.view')->with('success', '選項已更新');
    }

    /**
     * 刪除選項
     */
    public function destroy(Option $option)
    {
        $option->delete();
        return redirect()->route('options.view')->with('success', '選項已刪除');
    }
} 