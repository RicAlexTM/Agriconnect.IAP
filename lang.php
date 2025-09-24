<?php
// Start session to store language preference
session_start();

// Load config to get default language
$config = require __DIR__ . '/../config.php';
$default = $config['default_lang'] ?? 'en';

// If no language selected yet, set default
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = $default;
}

// If user clicks ?lang=en or ?lang=sw, change session
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en','sw'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

//Function to output text based on selected language
function t($en, $sw) {
    return ($_SESSION['lang'] ?? 'en') === 'sw' ? $sw : $en;
}
