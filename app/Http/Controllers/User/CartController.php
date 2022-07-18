<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Stock;
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
            Cart::create([  // なければ新規作成
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('user.cart.index');
    }

    public function delete($id)
    {
        Cart::where('product_id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->route('user.cart.index');
    }

    public function checkout()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;

        foreach ($products as $product) {   // この中でStockテーブルの情報も確認していくことになる

            $quantity = '';
            $quantity = Stock::where('product_id', $product->id)->sum('quantity');  // 商品の在庫

            // カート内の商品数 > 在庫 なら 変えないので、リダイレクトする。
            if ($product->pivot->quantity > $quantity) {
                return redirect()->route('user.cart.index');
            } else {
                $lineItems[] = [
                    'price' => $product->price,
                    'quantity' => $product->pivot->quantity,
                ];
            }
        }

        // 全て在庫チェックをして、買える状態にしてから、Stripeに渡す前に、在庫を減らしておく必要がある。

        foreach($products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['reduce'],    // Constantはファサード
                'quantity' => $product->pivot->quantity * -1,   // カートの中の在庫数
            ]);
        }

        // dd('test'); //マイナスの在庫処理ができているか確認。


        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); // 秘密鍵

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => route('user.items.index'),
            'cancel_url' => route('cart.cart.index'),
        ]);

        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('user.checkout', compact('session', 'publicKey'));
    }
}
