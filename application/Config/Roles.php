<?php
//Setting Default Roles
return [
    'admin' => [
        'Backend\Dashboard' => ["index", 'edit', 'delete'],
        'Backend\Users' => ["index", 'edit', 'add', 'delete', 'update_status'],
        'Backend\Posts' => ["index", 'edit', 'add', 'delete'],
        'Backend\Options' => ["index", 'edit'],
    ],
    'moderator' => [
        'Backend\Dashboard' => ["index",],
        'Backend\Users' => ["index", 'edit'],
        'Backend\Posts' => ["index",'edit', 'add'],
    ],
    'author' => [
        'Backend\Posts' => ["index",'add', 'edit'],
    ],
    'member' => [],
    // Thêm các roles khác tùy theo yêu cầu
];
