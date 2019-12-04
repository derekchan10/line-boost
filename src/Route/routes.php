<?php

Route::group(['namespace' => config('boost.route.namespace'), 'middleware' => config('boost.route.middleware'), 'prefix' => config('boost.route.prefix')], function () {
    Route::get('{compaign}/line/data', 'BoostController@lineData');
    Route::post('{compaign}/line/auth', 'BoostController@lineAuth');
    Route::post('{compaign}/boost', 'BoostController@boost');
});