<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\SendThankMail;
use App\Mail\TestMail;
use App\Models\PrimaryCategory;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

    public function index(Request $request)
    {
        // 同期的に送信
        // Mail::to('test@example.com') //受信者の指定
        // ->send(new TestMail());

        // 非同期的に送信
        SendThankMail::dispatch();

        $categories = PrimaryCategory::with('secondary')->get();

        $products = Product::availableItems()
        ->selectCategory($request->category ?? '0')
        ->searchKeyword($request->keyword)
        ->sortOrder($request->sort)
        ->paginate($request->pagination ?? 20);

        return view('user.index', compact('products', 'categories'));
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
