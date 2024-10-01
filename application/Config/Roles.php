<?php
return [
    'admin' => [
        'dashboard' => ['view', 'edit', 'delete'],
        'users' => ['view', 'edit', 'create', 'delete'],
        'posts' => ['view', 'edit', 'create', 'delete'],
        'settings' => ['view', 'edit'],
    ],
    'moderator' => [
        'dashboard' => ['view'],
        'users' => ['view', 'edit'],
        'posts' => ['view', 'edit', 'create'],
    ],
    'author' => [
        'posts' => ['view', 'create', 'edit'],
    ],
    'member' => [
        'posts' => ['view'],
    ],
    // Thêm các roles khác tùy theo yêu cầu
];
