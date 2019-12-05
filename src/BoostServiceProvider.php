<?php

namespace T8891\LineBoost;

use Illuminate\Support\ServiceProvider;

class BoostServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {  
        // 執行發佈命令會將配置文件copy到Laravel根目錄下的config文件夾下
        $this->publishes([
            __DIR__ . "/Config/boost.php" => config_path('boost.php')
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/Route/routes.php');

        // 註冊中間件
        $this->app['router']->aliasMiddleware('boostApiFormat', \T8891\LineBoost\Middleware\BoostApiFormat::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/boost.php',
            'boost'
        );
    }
}
