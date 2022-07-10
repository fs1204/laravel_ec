<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ShopController extends Controller
{
    // Admin/OwnersController を参照する

    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            // http://localhost/owner/shops/edit/2
            // dd($request);
            // ^ Illuminate\Http\Request {#42 ▼
            //      .....
            //   }

            // dd($request->route());
            // http://localhost/owner/shops/edit/2 でアクセス
            // ^ Illuminate\Routing\Route {#283 ▼
            //      ...
            //     +parameters: array:1 [▼
            //             "shop" => "2"
            //         ]
            //              Route::get('edit/{shop}', [ShopController::class, 'edit'])->name('shops.edit');
            //              {shop} が parameters の key に当たる。 2
            //      ...
            //   }

            // dd($request->route()->parameter('shop'));   // "2"  // 文字列で返ってくる
            // dd(Auth::id()); // 1 数値で帰ってくる    ownerのid

            // http://localhost/owner/shops/index とすると、、、
            // dd($request->route()->parameter('shop')); は null となる

            // まず null かどうか判定して、 null でなければ、
            // urlに書いてある数字を確認して、ログインしているownerが作ったものかどうかチェックする
            // 違ったら、エラーページを出す

            $id = $request->route()->parameter('shop'); // shop の id取得
            if (!is_null($id)) {    // null判定
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;  // owner_id を取得
                $shopId = (int)$shopsOwnerId;   // キャスト 文字列→数値 に型変換
                $ownerId = Auth::id();  // 認証済みのownerのid
                if ($shopId !== $ownerId) { // 同じでなかったら、、、
                    abort(404);
                }
            }

            return $next($request);

            // http://localhost/owner/shops/edit/2
            // 404 NOT FOUND
            // 複数のownerが商品や店舗の情報を持っているときは、urlを直接変更する可能性がある
        });
    }

    public function index()
    {
        // phpinfo();
        // $ownerId = Auth::id();
        // $shops = Shop::where('owner_id', $ownerId)->get();

        $shops = Shop::where('owner_id', Auth::id())->get();

        return view('owner.shops.index', compact('shops'));
    }

    public function edit($id)   // ルートパラメータの値が引数に入ってくる。
    {
        $shop = Shop::findOrFail($id);
        return view('owner.shops.edit', compact('shop'));
    }


    // フォームに入力された値は$requestに入っている
    public function update(UploadImageRequest $request, $id)
    {
        $imageFile = $request->file('image');
        // $imageFile = $request->image;    //動的プロパティを使ってもいい

        if (!is_null($imageFile) && $imageFile->isValid()) {
            // この条件がないと、nullのときも上書きしてしまうことになる

            // Storage::putFile('public/shops', $imageFile); リサイズなしの場合
                                //  storage/app/public/shops

            // リサイズする場合
            $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
                // アップロードされた画像をInterventionImage::makeで入れて、その後にresizeでサイズを設定してからエンコードをかけている。
            // dd($imageFile, $resizedImage);
            // ^ Illuminate\Http\UploadedFile {#1336 ▼
            //     -test: false
            //     -originalName: "ダウンロード.png"
            //     -mimeType: "image/png"
            //      ....
            //   }
            // Intervention\Image\Image {#1332 ▼
                //  ....
            //     +encoded: b" Ï Ó\x00\x10JFIF\x00\x01\x01\x01\x00`\x00`\x00\x00 ■\x00;CREATOR: gd-jpeg v1.0 (using IJG JPEG v80), quality = 90\n                  █\x00C\x00\x03\x02\x02\x03\x02\x02\x03\x03\x .............
            //   }
            $fileName = uniqid(rand().'_'); // ランダムなファイル名を作成
            $extension = $imageFile->extension();   // アップロードされたファイルの拡張子を取得する
            $fileNameToStore = $fileName. '.' . $extension; // 作ったファイル名と拡張子をくっつけて、保存するためのファイル名
            Storage::put('public/shops/' . $fileNameToStore, $resizedImage);

        }

        return redirect()->route('owner.shops.index');
    }
}
