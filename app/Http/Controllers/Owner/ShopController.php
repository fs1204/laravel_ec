<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Models\Shop;
use App\Service\ImageService;
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
        });
    }

    public function index()
    {
        $shops = Shop::where('owner_id', Auth::id())->get();
        return view('owner.shops.index', compact('shops'));
        // 1つのownerに対して、1つのshopなので、shopとしたほうがよいのでは？？？
    }

    public function edit($id)   // ルートパラメータの値が引数に入ってくる。
    {
        $shop = Shop::findOrFail($id);
        return view('owner.shops.edit', compact('shop'));
    }


    public function update(UploadImageRequest $request, $id) // 関数の引数にルートパラメータの値が入ってくる。
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'information' => ['required', 'string', 'max:1000'],
            'is_selling' => ['required'],
        ]); // 3つの属性に関してここでバリデーションがかかる ファイル（image）に関しては、フォームリクエストでバリデーションをかけている

        $imageFile = $request->file('image');
        if (!is_null($imageFile) && $imageFile->isValid()) {
            $fileNameToStore = ImageService::upload($imageFile, 'shops');
        }

        // バリデーションがかかったら、ファイルをアップロードしている。アップロードした後あたりに、まとめて書いていく
        // アップロードの処理の書き方は、OwnersController.phpのupdateで書いていたものと同じような書き方となる。

        $shop = Shop::findOrFail($id);    // 入ってきたidを元にEloquentで情報を取得する。
        $shop->name = $request->name;  // フォームから入ってくる値をモデルに代入する。
        $shop->information = $request->information;
        $shop->is_selling = $request->is_selling;

        if (!is_null($imageFile) && $imageFile->isValid()) {
            $shop->filename = $fileNameToStore; // 作成したファイル名を保存する
        }

        $shop->save();  // 保存する

        return redirect()->route('owner.shops.index')->with([
            'message' => '店舗情報を更新',
            'status' => 'info',
        ]);
        // OwnersControllerのupdateから持ってくる

    }
}
