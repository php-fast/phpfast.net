<?php

return [
    // Cấu hình ứng dụng
    'app' => [
        'debug' => true,
        'environment' => 'development',
        'app_url' => 'https://phpfast.net/',
        'app_name' => 'phpfast',
        'app_timezone' => 'Asia/Ho_Chi_Minh'
    ],
    'security' => [
        'app_id' => '123456',
        'app_secret' => '123456789'
    ],
    'db' => [
        // Cấu hình cơ sở dữ liệu
        'db_driver' => 'mysql',
        'db_host' => '127.0.0.1',
        'db_port' => 3306,
        'db_username' => 'root',
        'db_password' => '',
        'db_database' => 'phpfast.net',
        'db_charset'  => 'utf8mb4'
    ],
    'email' => [
        'mail_mailer' => 'smtp',
        'mail_host' => 'smtp.mailtrap.io',
        'mail_port' => 2525,
        'mail_username' => '',
        'mail_password' => '',
        'mail_encryption' => 'tls',
        'mail_from_address' => '',
        'mail_from_name' => 'phpfast',
    ],
    'cache' => [
        'cache_driver' => 'redis',
        'cache_host' => '127.0.0.1',
        'cache_port' => 6379,
        'cache_username' => '',
        'cache_password' => '',
        'cache_database' => 0,
    ],
    'theme' => [
        'theme_path' => 'application/Views',
        'theme_name' => 'default'
    ]
    
];
