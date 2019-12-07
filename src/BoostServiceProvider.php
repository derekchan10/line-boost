<?php

namespace T8891\LineBoost;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use T8891\LineBoost\Event\BoostLineData;
use T8891\LineBoost\Event\BoostUserAuthFinish;
use T8891\LineBoost\Event\BoostBefore;
use T8891\LineBoost\Event\BoostSuccess;

class BoostServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        BoostLineData::class => [],
        BoostUserAuthFinish::class => [],
        BoostBefore::class => [],
        BoostSuccess::class => [],
    ];

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

        parent::boot();
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

        // 门面绑定
        $this->app->singleton('LineBoost', function($app) {
            return $this->app->make('T8891\LineBoost\LineBoostService');
        });
    }
}
