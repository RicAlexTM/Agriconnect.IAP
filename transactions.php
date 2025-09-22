<?php
// config.php
return (object)[
    // DB
    'db' => (object)[
        'dsn' => 'mysql:host=127.0.0.1;dbname=agri_db;charset=utf8mb4',
        'user' => 'db_user',
        'pass' => 'db_pass',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ],
    ],

    // M-Pesa Daraja (fill with your credentials)
    'mpesa' => (object)[
        'consumer_key' => 'YOUR_CONSUMER_KEY',
        'consumer_secret' => 'YOUR_CONSUMER_SECRET',
        'short_code' => '174379', // or your paybill/shortcode
        'passkey' => 'YOUR_PASSKEY',
        'stk_push_callback' => 'https://yourdomain.com/public/mpesa_callback.php',
        // Endpoints (sandbox by default)
        'oauth_url' => 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
        'stk_push_url' => 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
    ],

    // App settings
    'app' => (object)[
        'env' => 'development',
        'log_path' => __DIR__ . '/logs/payments.log',
    ],
];
