<?php

namespace App\Service;

use App\Models\Cart;
use App\Models\Product;

class CartService {
    public static function getItemsInCart($items)
    {
        $products = []; //空の配列を準備

        // dd($items); データが渡ってきているかの確認 カートに商品を入れて購入するを押す
        // 認証済みのユーザーのカートに入っているレコード
        // ^ Illuminate\Database\Eloquent\Collection {#1444 ▼
        //     #items: array:1 [▼
        //       0 => App\Models\Cart {#1442 ▶}
        //     ]
        //     #escapeWhenCastingToString: false
        //   }

        foreach($items as $item){ // カート内の商品を一つずつ処理
            $p = Product::findOrFail($item->product_id);
            $owner = $p->shop->owner->select('name', 'email')->first()->toArray(); //オーナー情報
            $values = array_values($owner); //連想配列の値を取得    商品情報でもnameを使うため、このままでは被ってしまう
            $keys = ['ownerName', 'email'];
            $ownerInfo = array_combine($keys, $values); // オーナー情報のキーを変更

            // dd($ownerInfo);
            // ^ array:2 [▼
            //     "ownerName" => "test1"
            //     "email" => "test1@test.com"
            // ]

            $product = Product::where('id', $item->product_id)->select('id', 'name', 'price')->get()->toArray(); // 商品情報の配列
            // dd($product);
            // ^ array:1 [▼
            //     0 => array:3 [▼
            //         "id" => 104
            //         "name" => "江古田 香織"
            //         "price" => 93465
            //     ]
            // ]


            // $product = Product::findOrFail($item->product_id);
            // dd($product);
            // App\Models\Product

            // $product = Product::findOrFail($item->product_id)
            // ->select('id', 'name', 'price')->get(); // selectを追加すると、すべてのレコードを取得することになる findとしても同じ
            // select文を繋げるときはwhereでレコードの条件指定する
            // dd($product);
            // ^ Illuminate\Database\Eloquent\Collection {#1673 ▼
            //     #items: array:200 [▼
            //       0 => App\Models\Product {#1672 ▶}
            //       1 => App\Models\Product {#1709 ▶}
            //       2 => App\Models\Product {#1708 ▶}
            //       3 => App\Models\Product {#1707 ▶}
            //      ...
            //     199 => App\Models\Product {#1877 ▶}


            $quantity = Cart::where('product_id', $item->product_id)->select('quantity')->get()->toArray(); // 数量の配列
            // dd($quantity);
            // ^ array:1 [▼
            //     0 => array:1 [▼
            //         "quantity" => 1
            //     ]
            // ]

                            // $productと$quantityは配列の中に配列があるので、[0]で取り出す
            $result = array_merge($product[0], $ownerInfo, $quantity[0]); // 商品情報とオーナー情報と数量情報の3つをmergeして、必要な配列にする。

            // dd($result);
            // ^ array:6 [▼
            //     "id" => 104
            //     "name" => "江古田 香織"
            //     "price" => 93465
            //     "ownerName" => "test1"
            //     "email" => "test1@test.com"
            //     "quantity" => 1
            // ]

            array_push($products, $result); //配列に追加
        }

        // dd($products);
        // ^ array:1 [▼
        //     0 => array:6 [▼
        //         "id" => 104
        //         "name" => "江古田 香織"
        //         "price" => 93465
        //         "ownerName" => "test1"
        //         "email" => "test1@test.com"
        //         "quantity" => 1
        //     ]
        // ]
        
        return $products; // メール送信で必要な情報だけをまとめた新しい配列を返す
    }
}
