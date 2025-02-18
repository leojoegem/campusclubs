<?php
session_start();
include 'dbConnect.php';

// Check if the user has an email session (registered but unverified)
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];

    // Fetch stored OTP from the database
    $stmt = $conn->prepare("SELECT id, otp, otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    if ($user_data) {
        $stored_otp = $user_data['otp'];
        $otp_expiry = strtotime($user_data['otp_expiry']);
        
        if ($otp_expiry >= time() && $user_otp == $stored_otp) {
            // OTP is valid and not expired
            $stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            unset($_SESSION['email']);
            $_SESSION['user_id'] = $user_data['id'];

            header("Location: login.php");
            exit();
        } else {
            echo "<script>alert('OTP has expired or is incorrect. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Invalid request. Please sign up again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Verification</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { width: 400px; margin: 100px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        input[type=number] { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ddd; }
        button { background-color: orange; border: none; width: 100px; padding: 9px; margin-top: 10px; color: white; cursor: pointer; border-radius: 5px; }
        button:hover { background-color: darkorange; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Enter OTP</h2>
        <p>A verification code was sent to your email <b><?php echo $email; ?></b>. Enter it below:</p>

        <form action="" method="POST">
            <input type="number" name="otp" placeholder="Enter OTP" required>
            <button type="submit">Verify</button>
        </form>
    </div>

</body>
</html>
