<?php

Route::group(['namespace' => config('boost.route.namespace'), 'middleware' => config('boost.route.middleware'), 'prefix' => config('boost.route.prefix')], function () {
    Route::get('{compaign}/line/data', 'BoostController@lineData');
    Route::post('{compaign}/sponsor/auth', 'BoostController@sponsorAuth');
    Route::post('{compaign}/user/auth', 'BoostController@userAuth');
    Route::get('{compaign}/boost', 'BoostController@boost');
});