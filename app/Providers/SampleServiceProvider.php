<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// SampleServiceProviderを起動するときに読み込むために、config/app.php に追記する
class SampleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('serviceProviderTest', function() {
            return 'サービスプロバイダのテスト';
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
