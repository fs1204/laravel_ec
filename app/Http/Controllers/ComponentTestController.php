<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComponentTestController extends Controller
{
    //
    public function showComponent1() {
        $message = 'メッセージ';
        return view('tests.component1', compact('message'));
    }

    public function showComponent2() {
        return view('tests.component2');
    }
}
