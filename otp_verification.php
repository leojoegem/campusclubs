<?php
session_start();
include 'dbConnect.php';

// Check if the user is redirected from the registration process
if (!isset($_SESSION['temp_user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];
    $stored_otp = $_SESSION['temp_user']['otp'];
    $user_id = $_SESSION['temp_user']['id'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $otp_expiry = strtotime($data['otp_expiry']);
        if ($otp_expiry >= time() && $user_otp == $stored_otp) {
            // OTP is valid and not expired
            $_SESSION['user_id'] = $data['id'];
            unset($_SESSION['temp_user']);
            header("Location: login.php");
            exit();
        } else {
            echo "<script>alert('OTP has expired or is invalid. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Verification</title>
    <style type="text/css">
        #container {
            border: 1px solid black;
            width: 400px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }
        input[type=number] {
            width: 290px;
            padding: 10px;
            margin-top: 10px;
        }
        button {
            background-color: orange;
            border: 1px solid orange;
            width: 100px;
            padding: 9px;
            margin-top: 10px;
        }
        button:hover {
            cursor: pointer;
            opacity: .9;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>Two-Step Verification</h1>
        <p>Enter the 6 Digit OTP Code that has been sent <br> to your email address: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <form method="post" action="otp_verification.php">
            <label style="font-weight: bold; font-size: 18px;" for="otp">Enter OTP Code:</label><br>
            <input type="number" name="otp" pattern="\d{6}" placeholder="Six-Digit OTP" required><br><br>
            <button type="submit">Verify OTP</button>
        </form>
        <p><a href="resend_otp.php">Resend OTP</a></p> <!-- Link to resend OTP -->
    </div>
</body>
</html>