<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WheelController extends Controller
{
    public function wheel()
    {
        return view('wheel');
    }

    public function draw(Request $request)
    {
        $count = $request->input('count', 12);
        $prizes = range(1, $count);
        if ($count < 1) {
            return response()->json(['error' => '選項數量需大於0'], 400);
        }
        $index = array_rand($prizes, 1);
        $value = $prizes[$index];
        return response()->json([
            'index' => $index,
            'value' => $value
        ]);
    }

    public function setOptions(Request $request)
    {
        $count = $request->input('count', 12);
        $prizes = range(1, $count);
        $index = random_int(0, count($prizes) - 1);
        return response()->json([
            'result' => $prizes[$index],
            'index' => $index
        ]);
    }
} 