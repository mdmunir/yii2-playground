<?php
return[
    'GET,HEAD v01/travel-package' => 'v01/travel-package/index',
    'GET,HEAD v01/travel-package/<id:\d+>' => 'v01/travel-package/view',

    //
    'POST v01/order' => 'v01/order/create',
    'POST v01/order/paid/<id:\d+>' => 'v01/order/paid',
    'DELETE v01/order/<id:\d+>' => 'v01/order/delete',
    'GET,HEAD v01/order' => 'v01/order/index',
    'GET,HEAD v01/order/<id:\d+>' => 'v01/order/view',

    //
    'POST v01/comment' => 'v01/comment/create',
    'GET,HEAD v01/<type:(comment|review)>/<object_type:(package|item|comment)>/<id:\d+>' => 'v01/comment/index',
    'GET,HEAD v01/order/<transaction_id:\d+>/review' => 'v01/comment/view-review'
];
