<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;  // QueryBuilder
use App\Models\Owner;               // Eloquent Model
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Throwable;

class OwnersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $owners = Owner::all(); とすると、不要な情報も取得できてしまう
        // $owners = Owner::select('id', 'name', 'email', 'created_at')->get();
        $owners = Owner::select('id', 'name', 'email', 'created_at')->paginate(3);
        return view('admin.owners.index', compact('owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
                        // メソッドインジェクション
                        // formで入力された値がRequestクラスになって、それをインスタンス化する
                        // formで入力した値が$requestで入ってくる形になる
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:owners'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            DB::transaction(function () use($request) {
                $owner = Owner::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                Shop::create([
                    'owner_id' => $owner->id, // 作成した$ownerのidを取得できる
                    'name' => '店名を入力してください',
                    'information' => '',
                    'filename' => '',
                    'is_selling' => true,
                ]);
            }, 2);  // 2回繰り返してくれる
        } catch(Throwable $e) { // 何かしらのエラーがあると、$eに入ってくる
        // } catch(\Throwable $e) { // ThrowableはPHP7の機能 useを使う場合と、 \を使う場合がある
            Log::error($e);
            throw $e;
            // ログを書いて、画面上に出す
        }

        return redirect()
                ->route('admin.owners.index')
                ->with([
                    'message' => 'オーナー登録を実施しました。',
                    'status' => 'info',
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $owner = Owner::findOrFail($id);
        // dd($owner);
        return view('admin.owners.edit', compact('owner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
                        // フォームに入力された値は$requestに入っている
    public function update(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);    // 入ってきたidを元にEloquentで情報を取得する。
        $owner->name = $request->name;  // フォームから入ってくる値をモデルに代入する。
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
                ->route('admin.owners.index')
                ->with([
                    'message' => 'オーナー情報を更新',
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
        Owner::findOrFail($id)->delete(); // ソフトデリート

        return redirect()
                ->route('admin.owners.index')
                ->with([
                    'message' => 'オーナー情報を削除しました。',
                    'status' => 'alert',
                ]);
    }

    public function expiredOwnerIndex() {
        $expiredOwners = Owner::onlyTrashed()->get();   // 削除したモデルを取得
        return view('admin.expired-owners', compact('expiredOwners'));
    }

    public function expiredOwnerDestroy($id) {
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();   // 削除したモデルを完全削除
        return redirect()->route('admin.expired-owners.index');
    }
}

