<?php

return [
    'master' => [
        'host' => '10.105.62.216',
        'port' => 3306,
        'user' => 'im',
        'password' => 'KGWkVR9NjmDxf0v2',
        'database' => 'im',
        'charset'  => 'utf8mb4',

        // 最大连接数，每个worker最大连接数为 async_max_count/worker_num
        'max_count' => 5
    ]
];
