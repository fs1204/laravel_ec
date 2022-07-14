<?php

namespace App\Http\Controllers\Owner;

use App\Constants\Common;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Image;
use App\Models\Owner;
use App\Models\PrimaryCategory;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('product');
            if (!is_null($id)) {
                $productsOwnerId = Product::findOrFail($id)->shop->owner->id;
                $productId = (int)$productsOwnerId;
                if ($productId !== Auth::id()) {
                    abort(404);
                }
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ログインしてるオーナーが所有しているproductを表示したい
        // $products = Owner::findOrFail(Auth::id())->shop->product; // n+1問題

        // 認証しているownerは1人なので、単数形
        $ownerInfo = Owner::with('shop.product.imageFirst')     // owner→shop→product→imageFisrt に渡って、まとめて取得する。
                    ->where('id', Auth::id())           // ログインしているオーナーの情報を取得できる。
                    ->get();

        // dd($ownerInfo);       Owner, Shop, Product, ImageFirst のモデルをすべて取得している
        // ^ Illuminate\Database\Eloquent\Collection {#1465 ▼
        //      #items: array:1 [▼
        //     0 => App\Models\Owner {#1483 ▼
        //       ...
        //       #relations: array:1 [▼
        //         "shop" => App\Models\Shop {#1477 ▼
        //           ...
        //           #relations: array:1 [▼
        //             "product" => Illuminate\Database\Eloquent\Collection {#1490 ▼
        //               #items: array:5 [▼
        //                 0 => App\Models\Product {#1488 ▼
        //                   ....
        //                   #relations: array:1 [▼
        //                     "imageFirst" => App\Models\Image {#1504 ▼
        //                     }
        //                   ]
        //                   ...
        //                 }
        //                 1 => App\Models\Product {#1513 ▶}
        //                 2 => App\Models\Product {#1512 ▶}
        //                 3 => App\Models\Product {#1511 ▶}
        //                 4 => App\Models\Product {#1510 ▶}
        //               ]
        //               ...
        //             }
        //           ]
        //           ...
        //         }
        //       ]
        //       ....
        //     }
        //   ]
        //   ...
        // }

        // dd($ownerInfo[0]);  // App\Models\Owner {#1483 ▼
        // dd($ownerInfo[1]);  // Undefined array key 1

        // view側でfilenameを取得する
        // foreach($ownerInfo as $owner) {  // $ownerInfo から 値を取り出す 今回は1つ
                    // dd($owner->shop->product);
                    // ^ Illuminate\Database\Eloquent\Collection {#1490 ▼
                    //     #items: array:5 [▼
                    //       0 => App\Models\Product {#1488 ▶}
                    //       1 => App\Models\Product {#1513 ▶}
                    //       2 => App\Models\Product {#1512 ▶}
                    //       3 => App\Models\Product {#1511 ▶}
                    //       4 => App\Models\Product {#1510 ▶}
                    //     ]
                    //     #escapeWhenCastingToString: false
                    //   }
                    // App\Models\Product が ５つあるので、foreach の中にさらに foreach をかける必要がある

            // foreach ($owner->shop->product as $product) {
            //     echo '<pre>';
            //     echo $product->imageFirst->filename . PHP_EOL;
            //     echo '</pre>';
            //     // sample1.jpg
            //     // sample2.jpg
            //     // sample3.jpg
            //     // sample3.jpg
            //     // sample4.jpg
            // }
        // }

        return view('owner.products.index', compact('ownerInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::where('owner_id', Auth::id())
            ->select('id', 'name')
            ->get();
        $images = Image::where('owner_id', Auth::id())
            ->select('id', 'title', 'filename')
            ->orderBy('updated_at', 'desc')
            ->get();
        $categories = PrimaryCategory::with('secondary')->get();
        return view('owner.products.create', compact('shops', 'images', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // OwnerController.php を参考にする
    public function store(ProductRequest $request)
    {
        try {
             // create.blade.phpを下に配置して中身を見ながらバリデーションをかける。
            DB::transaction(function () use($request) {
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'image2' => $request->image2,
                    'image3' => $request->image3,
                    'image4' => $request->image4,
                    'is_selling' => $request->is_selling,
                ]);

                Stock::create([
                    'product_id' => $product->id, // 作成した$productのidを取得できる
                    'type' => 1,
                    'quantity' => $request->quantity,
                ]);
            }, 2);  // 2回繰り返してくれる
        } catch(Throwable $e) { // 何かしらのエラーがあると、$eに入ってくる
        // } catch(\Throwable $e) { // ThrowableはPHP7の機能 useを使う場合と、 \を使う場合がある
            Log::error($e);
            throw $e;
            // ログを書いて、画面上に出す
        }

        return redirect()
                ->route('owner.products.index')
                ->with([
                    'message' => '商品登録しました。',
                    'status' => 'info',
                ]);
    }


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');

        // createからコピー
        $shops = Shop::where('owner_id', Auth::id())
            ->select('id', 'name')
            ->get();
        $images = Image::where('owner_id', Auth::id())
            ->select('id', 'title', 'filename')
            ->orderBy('updated_at', 'desc')
            ->get();
        $categories = PrimaryCategory::with('secondary')->get();

        return view('owner.products.edit', compact('product', 'quantity', 'shops', 'images', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        //ShopController.phpのupdateを参照
        $request->validate([
            'current_quantity' => 'required|integer'
            // editでは<input type="hidden" id="current_quantity" name="current_quantity" value="{{ $quantity }}" > がある
            // 悪意のあるユーザーがGoogle Chromeの開発ツールを開いて直接valueを書き換えることもあり得る 念の為バリデーションをかけておく
        ]);

        // updateは$idで1つの商品を指定することができる
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');  // ルートパラメータで指定した商品の在庫をquantityに入れることができる

            // editに表示しているquantity   updateを読み込む際に取得したquantity
        if ($request->current_quantity !== $quantity) {
            $id = $request->route()->parameter('product');
            return redirect()->route('owner.products.edit', ['product' => $id])
                    ->with([    // 急にリダイレクトすると、訳がわからないので、フラッシュメッセージをつける
                        'message' => '在庫数が変更されています。再度確認してください。',
                        'status' => 'alert',
                    ]); // フラッシュメッセージをedit側に追加する必要がある。
        } else {
            try {
                // create.blade.phpを下に配置して中身を見ながらバリデーションをかける。
               DB::transaction(function () use($request, $product) {
                    // ルートパラメータで指定した$productを更新する。createだと新規作成してしまう。
                    $product->name = $request->name;
                    $product->information = $request->information;
                    $product->price = $request->price;
                    $product->sort_order = $request->sort_order;
                    $product->shop_id = $request->shop_id;
                    $product->secondary_category_id = $request->category;
                    $product->image1 = $request->image1;
                    $product->image2 = $request->image2;
                    $product->image3 = $request->image3;
                    $product->image4 = $request->image4;
                    $product->is_selling = $request->is_selling;

                    $product->save();   //createで保存しない場合は必要

                    // if ($request->type ===  Common::PRODUCT_LIST['add']) {
                    if ($request->type ===  \Constant::PRODUCT_LIST['add']) {
                        $newQuantity = $request->quantity;
                    }
                    // if ($request->type ===  Common::PRODUCT_LIST['reduce']) {
                    if ($request->type ===  \Constant::PRODUCT_LIST['reduce']) {
                        $newQuantity = $request->quantity * -1;
                    }

                    Stock::create([ // Stockは毎回新規作成で作っていく
                        'product_id' => $product->id,
                        'quantity' => $newQuantity,
                        'type' => $request->type,
                    ]);
               }, 2);  // 2回繰り返してくれる
           } catch(Throwable $e) { // 何かしらのエラーがあると、$eに入ってくる
           // } catch(\Throwable $e) { // ThrowableはPHP7の機能 useを使う場合と、 \を使う場合がある
               Log::error($e);
               throw $e;
               // ログを書いて、画面上に出す
           }

           return redirect()
                   ->route('owner.products.index')
                   ->with([
                       'message' => '商品情報を更新しました。',
                       'status' => 'info',
                   ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()
                ->route('owner.products.index')
                ->with([
                    'message' => '商品を削除しました。',
                    'status' => 'alert',
                ]);
    }
}
