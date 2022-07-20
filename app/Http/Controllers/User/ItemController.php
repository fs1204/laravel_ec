<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');

        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('item'); // parameters配列のキーがitemの値を取り出す。
            if (!is_null($id)) { // このidが空でなければという判定をかける
                                // 表示できる商品レコードの中で、$idに一致するレコードが存在するか
                if (!Product::availableItems()->where('products.id', $id)->exists()) {
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    // view側で設定した値をコントローラ側で受け取る必要がある。
    public function index(Request $request)
    {
        // $products = Product::availableItems()->get();
        $products = Product::availableItems()
        ->sortOrder($request->sort) // リクエストのsortの値次第で並び替えられる。
        // ->get();
        // ->paginate($request->pagination);   // 20か50か100 という数字が入ってくる。
        ->paginate($request->pagination ?? 20);   // デフォルトは15なので、20に指定する 最初に画面に表示されるときは先頭の20の項目が表示される

        return view('user.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');
        // dd($quantity);  // "731341" （string）

        if ($quantity > 9) {
            $quantity = 9;
        }
        // dd($quantity);  // 9 （integer）

        return view('user.show', compact('product', 'quantity'));
    }

}
