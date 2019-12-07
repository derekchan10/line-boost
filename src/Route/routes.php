<?php

Route::group(['namespace' => config('boost.route.namespace'), 'middleware' => config('boost.route.middleware'), 'prefix' => config('boost.route.prefix')], function () {
    Route::any('{compaign}/line/data', 'BoostController@lineData');
    Route::any('{compaign}/sponsor/auth', 'BoostController@sponsorAuth');
    Route::any('{compaign}/user/auth', 'BoostController@userAuth');
    Route::any('{compaign}/boost', 'BoostController@boost');
});