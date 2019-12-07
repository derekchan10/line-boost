<?php
return [
    'exception_handle' => 1, // 默認組件會處理異常，如果要自定義處理異常，請設置為 0

    'route' => [
        // 路由命名空間
        'namespace' => 'T8891\LineBoost\Controller',

        // 路由中間件
        'middleware' => ['boostApiFormat'],

        // 路由前綴
        'prefix' => 'api/v2',
    ],

    'table' => [
        'boost_list' => 't_boost_list',
        'sponsor_auth' => 't_boost_sponsor_auth',
        'user_auth' => 't_boost_user_auth',
    ],

    'messages' => [
        'param_error' => '參數錯誤！',
        'auth_error' => 'Line 授權失敗！',
        'boost_line_error' => 'Line 授權失敗！',
        'boost_success' => '助力成功！',
        'boost_failed' => '助力失敗！',
        'boost_unique' => '您今天已助力過！',
        'sponsor_auth_page_error' => '該頁面已經授權過其他 Line 賬號！',
        'sponsor_auth_line_error' => 'Line 賬號已經授權過其他頁面！',
    ],
    
];