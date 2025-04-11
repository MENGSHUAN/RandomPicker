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
        $count = $request->input('count', 40);
        $prizes = range(1, $count);
        $index = random_int(0, count($prizes) - 1);
        return response()->json([
            'result' => $prizes[$index],
            'index' => $index
        ]);
    }

    public function setOptions(Request $request)
    {
        $count = $request->input('count', 40);
        $prizes = range(1, $count);
        $index = random_int(0, count($prizes) - 1);
        return response()->json([
            'result' => $prizes[$index],
            'index' => $index
        ]);
    }
} 