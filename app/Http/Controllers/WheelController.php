<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WheelController extends Controller
{
    private $prizes = ['Mark', 'John', 'Mary', 'Tom', 'Jerry', 'Lisa', 'Andy', 'Lily', 'Wendy', 'Peter',
    'Mark', 'John', 'Mary', 'Tom', 'Jerry', 'Lisa', 'Andy', 'Lily', 'Wendy', 'Peter',
    'Mark', 'John', 'Mary', 'Tom', 'Jerry', 'Lisa', 'Andy', 'Lily', 'Wendy', 'Peter',
    'Mark', 'John', 'Mary', 'Tom', 'Jerry', 'Lisa', 'Andy', 'Lily', 'Wendy', 'Peter'];

    public function wheel()
    {
        return view('wheel');
    }

    public function draw(Request $request)
    {
        $index = random_int(0, count($this->prizes) - 1);
        return response()->json([
            'result' => $this->prizes[$index],
            'index' => $index
        ]);
    }
} 