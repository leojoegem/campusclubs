<?php
session_start(); 
include 'dbConnect.php';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredOtp = $_POST['otp'];

    // Retrieve the user's email from the session
    $email = $_SESSION['email'];

    // Fetch user data based on email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email =?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $storedOtp = $user['otp'];
        $otpGeneratedAt = strtotime($user['otp_generated_at']); // Convert to Unix timestamp
        $currentTime = time();
        $otpExpiryTime = 5 * 60; // 5 minutes in seconds

        // Check if OTP is correct and not expired
        if ($enteredOtp == $storedOtp && ($currentTime - $otpGeneratedAt) <= $otpExpiryTime) {
            // Mark user as verified (assuming you have a 'verified' column)
            $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE email =?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Clear OTP and redirect to login page
            $stmt = $conn->prepare("UPDATE users SET otp = NULL, otp_generated_at = NULL WHERE email =?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $_SESSION['success_message'] = "Email verified successfully! You can now log in.";
            header("Location: index.php");
            exit;
        } else {
            $error_message = "Invalid or expired OTP. Please try again.";
        }
    } else {
        $error_message = "User not found.";
    }
}?>

<!DOCTYPE html>
<html>
<head>
  <title>OTP Verification</title>
  <style>
    /*... add your CSS styles here... */
  </style>
</head>
<body>

  <h2>OTP Verification</h2>

  <?php if (isset($error_message)):?>
    <div class="error-message"><?php echo $error_message;?></div>
  <?php endif;?>

  <form method="POST" action="">
    <label for="otp">Enter OTP:</label>
    <input type="text" id="otp" name="otp" required><br><br>

    <button type="submit">Verify</button>
  </form>

</body>
</html>