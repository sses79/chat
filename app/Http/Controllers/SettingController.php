<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function lines()
    {

        $lines_array = [
            'vic' => 'victoria',
            'cen' => 'central',
            'jub' => 'jubilee',
        ];

        return view('front');

    }
}
