<?php

return [
    // Cấu hình ứng dụng
    'app' => [
        'debug' => true,
        'environment' => 'development',
        'app_url' => 'https://domainweb.com/',
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
        'db_database' => 'domainweb.com',
        'db_charset'  => 'utf8mb4',
        'db_collate'  => 'utf8mb4_unicode_ci',

    ],
    'email' => [
        'mail_mailer' => 'smtp',
        'mail_host' => 'smtp.gmail.com',
        'mail_port' => 587,
        'mail_username' => 'baonamhd1@gmail.com',
        'mail_password' => 'kgafazfrsrbslbiw',
        'mail_encryption' => 'tls',
        'mail_charset'  =>  'UTF-8',
        'mail_from_address' => 'baonamhd1@gmail.com',
        'mail_from_name' => 'PHPFast.net',
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
