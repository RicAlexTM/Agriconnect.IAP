<?php
<<<<<<< HEAD
require_once __DIR__ . '/../vendor/autoload.php';
use App\Config\Database;

session_start();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->connect();

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_verified']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header('Location: dashboard.php');
            exit();
        } else {
            $msg = "Please verify your email before logging in.";
        }
    } else {
        $msg = "Invalid email or password.";
=======
// ✅ Correct autoload path (2 levels up)
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\AuthController;

session_start();

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $auth = new AuthController();
    $user = $auth->login($email, $password);

    if ($user === 'not_verified') {
        $msg = "⚠️ Your account is not verified. Check your email for the verification link.";
    } elseif ($user === 'invalid') {
        $msg = "❌ Invalid email or password.";
    } elseif (is_array($user)) {
        // ✅ Login successful, store session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        // Redirect to dashboard or home
        header("Location: ../../index.php");
        exit;
    } else {
        $msg = "⚠️ Unexpected error occurred. Try again later.";
>>>>>>> 54df187fef5a87da47a262fd726af0f23f237160
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <title>Login - AgriMarket</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>
=======
    <title>Login - AgriConnect</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
<div class="container">
    <h2>Login to Your Account</h2>

    <?php if (!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>

>>>>>>> 54df187fef5a87da47a262fd726af0f23f237160
    <form method="POST">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
<<<<<<< HEAD
    <p>Don’t have an account? <a href="register.php">Register</a></p>
=======

    <p>Don’t have an account? <a href="register.php">Register here</a></p>
>>>>>>> 54df187fef5a87da47a262fd726af0f23f237160
</div>
</body>
</html>
