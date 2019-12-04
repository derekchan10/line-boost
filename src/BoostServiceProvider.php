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
            __DIR__ . "/config/boost.php" => config_path('boost.php')
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->loadRoutesFrom(__DIR__.'/route/routes.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/boost.php',
            'boost'
        );
    }
}
