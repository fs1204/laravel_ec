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

        foreach ($products as $product) { //全てのカートに入っている商品の情報を取り出す
            // カートに入っている情報はlineItemsとstripeでは呼ばれている。
            // 商品情報はstripe側に渡すために、stripeで受け取れる形にして送る必要がある。
            // stripe側で用意しているパラメーターを使う。ドキュメントに書いてある。 https://stripe.com/docs/api/checkout/sessions/create
            // Stripeに情報を渡すためにセッションでパラメータを渡す。キーとバリューの連想配列の形になっている。
            // line_itemsというパラメータに設定する必要がある。
            // line_itemsの中の「show child parameters」をクリック。あらかじめパラーメータが設定されていて、ルールに沿って書いていく。
            $lineItems[] = [
                'price' => $product->price,
                'quantity' => $product->pivot->quantity,
            ];
        }
        // dd($lineItems);
        // ^ array:2 [▼ カートに商品が入った状態で、 http://localhost/cart/checkout にアクセス。
        //   0 => array:2 [▼
        //     "price" => 25621
        //     "quantity" => 3
        //   ]
        //   1 => array:2 [▼
        //     "price" => 43204
        //     "quantity" => 6
        //   ]
        // ]

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); // 秘密鍵

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => route('user.items.index'), // 支払いが成功したらitems一覧のページにリダイレクトがかかる
            'cancel_url' => route('cart.cart.index'), // 支払いが失敗したらcartに戻す
        ]);

        $publicKey = env('STRIPE_PUBLIC_KEY');
        // 公開鍵
        // 公開鍵と秘密鍵の2つを組み合わせて決済ができる仕組みになっている。

        return view('user.checkout', compact('session', 'publicKey'));
    }
}
