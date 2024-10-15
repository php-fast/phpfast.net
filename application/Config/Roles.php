<?php
return [
    'admin' => [
        'Backend\Dashboard' => ["index", 'edit', 'delete'],
        'Backend\Users' => ["index", 'edit', 'create', 'delete'],
        'Backend\Posts' => ["index", 'edit', 'create', 'delete'],
        'Backend\Options' => ["index", 'edit'],
    ],
    'moderator' => [
        'Backend\Dashboarddashboard' => ["index",],
        'Backend\Users' => ["index", 'edit'],
        'Backend\Posts' => ["index",'edit', 'create'],
    ],
    'author' => [
        'Backend\Posts' => ["index",'create', 'edit'],
    ],
    'member' => [],
    // Thêm các roles khác tùy theo yêu cầu
];
