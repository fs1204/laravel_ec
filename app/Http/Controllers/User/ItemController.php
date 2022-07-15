<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // テストとして商品を表示させる。
        $products = Product::all();

        return view('user.index', compact('products'));
    }
}
