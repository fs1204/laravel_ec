<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';   // userがログインしたら/dashboardを開く
                                        // userの場合はこれでOKだが、ownerとadminそれぞれで設定する
                                        // それぞれのリダイレクト先を作っておく必要がある
    public const OWNER_HOME = '/owner/dashboard'; // OWNERのログイン画面にログインしたら、/owner/dashboard にリダイレクトされる
    public const ADMIN_HOME = '/admin/dashboard'; // OWNERのログイン画面にログインしたら、/owner/dashboard にリダイレクトされる



    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // ルート情報は大きく2種類パターンがあってmiddlewareの'web'を使うものと'api'を使うもの
            // laravelでview側を表示してリクエスト・レスポンスを返すパターンはweb 現在作成しているパターン
            // フロント側をすべてJavaScriptで作る場合はapi
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::prefix('/')  // ownerやadminがついていないものは全てuserのurlになる
                ->as('user.')   // asで別名をつける ルート情報をuser.で書いていく形となる 名前付きルートを指定するときはuser.*という形になる
                ->middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('owner')
                ->as('owner.')   // asで別名をつける ルート情報をowner.で書いていく形となる
                ->middleware('web')
                ->group(base_path('routes/owner.php'));
                // owner.phpのすべてのurlの頭にownerがつく

            Route::prefix('admin')
                ->as('admin.')   // asで別名をつける ルート情報をadmin.で書いていく形となる
                ->middleware('web') //??
                // ->middleware('admin') //?? adminでは？？ adminだとエラーになる
                ->group(base_path('routes/admin.php'));
                // admin.phpのすべてのurlの頭にownerがつく

            // Routeファサードが持っているメソッドを確認しておく
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
