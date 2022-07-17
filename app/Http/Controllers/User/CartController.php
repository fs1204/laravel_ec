<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products; // 多対多のリレーション $totalPrice = 0;
        $totalPrice = 0;

        foreach($products as $product){
            $totalPrice += $product->price * $product->pivot->quantity;
                            // 価格 * 数量
        }

        return view('user.cart', compact('products', 'totalPrice'));
    }

    public function add(Request $request)
    {
        $itemInCart = Cart::where('user_id', Auth::id()) // 'user_id'がログインしているuser
                    ->where('product_id', $request->product_id) // 'product_id'がrequestで渡ってくる$product_idと一致するものを検索
                    // and条件 ログインしているuserと$product_idをそれぞれを確認して、両方を満たすものを取得する
                    ->first();

        if($itemInCart){ //カートに商品があるか確認
            $itemInCart->quantity += $request->quantity; // 数量を追加
            $itemInCart->save();
        } else {
            Cart::create([ // なければ新規作成
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('user.cart.index');
    }
}
