<?php
<<<<<<< HEAD
require_once __DIR__ . '/../vendor/autoload.php';
use App\Config\Database;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $db = new Database();
    $conn = $db->connect();

    $stmt = $conn->prepare("SELECT * FROM users WHERE verify_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $update = $conn->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?");
        $update->execute([$user['id']]);
        echo "<h3>Email verified successfully! You can now <a href='login.php'>login</a>.</h3>";
    } else {
        echo "<h3>Invalid or expired token.</h3>";
    }
} else {
    echo "<h3>Missing verification token.</h3>";
}
?>
=======
require_once __DIR__.'/../../vendor/autoload.php';
use App\Controllers\AuthController;

$msg = "";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    if($auth->verifyOTP($email,$otp)) {
        $msg = "✅ Account verified successfully! You can now login.";
    } else {
        $msg = "❌ Invalid OTP or expired. Try resending OTP.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Account</title>
</head>
<body>
<h2>Verify Your Account</h2>
<?php if($msg) echo "<p>$msg</p>"; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Registered Email" required>
    <input type="text" name="otp" placeholder="6-digit OTP" required>
    <button type="submit">Verify</button>
</form>

<p>Didn't get OTP? <a href="resend.php">Resend OTP</a></p>
</body>
</html>
>>>>>>> 54df187fef5a87da47a262fd726af0f23f237160
