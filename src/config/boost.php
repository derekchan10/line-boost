<?php
return [
    'route' => [
        // 路由命名空間
        'namespace' => 'T8891\Boost\Controller',

        // 路由中間件
        'middleware' => [],

        // 路由前綴
        'prefix' => 'api/v2',
    ],

    'table' => [
        'boost' => 't_boost_list',
        'sponsor_auth' => 't_boost_sponsor_auth',
        'user_auth' => 't_boost_user_auth',
    ],
    
];