<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Models\Image;
use App\Service\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    // ShopController.php の __construct() をコピー
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('image');
            if (!is_null($id)) {
                $imagesOwnerId = Image::findOrFail($id)->owner->id;  // owner_id を取得
                $imageId = (int)$imagesOwnerId;
                if ($imageId !== Auth::id()) {
                    abort(404);
                }
            }

            return $next($request);
        });
    }

    // ShopController.php の index() をコピー
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // phpinfo();
        // $ownerId = Auth::id();
        // $shops = Shop::where('owner_id', $ownerId)->get();

        $images = Image::where('owner_id', Auth::id())->orderBy('updated_at', 'desc')->paginate(20);

        return view('owner.images.index', compact('images'));
        // 1つのownerに対して、1つのshopなので、shopとしたほうがよいのでは？？？
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        $imageFiles = $request->file('files');

        if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile){
                                                                    // 第2引数はフォルダ名
                $fileNameToStore = ImageService::upload($imageFile, 'products');
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                ]);
            }
        }

        // ShopController.php の create から抜粋
        return redirect()->route('owner.shops.index')
                ->with([
                    'message' => '店舗情報を更新',
                    'status' => 'info',
                ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // ShopContoroller の edit を参考に貼り付ける。
    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return view('owner.images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // ShopContoroller の update を参考に貼り付ける。
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['string', 'max:255'],
            // nullableなので、requiredは不要。
        ]);

        $image = Image::findOrFail($id);    // 入ってきたidを元にEloquentで情報を取得する。
        $image->title = $request->title;

        $image->save();  // 保存する

        return redirect()->route('owner.images.index')->with([
            'message' => '画像情報を更新しました。',
            'status' => 'info',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
