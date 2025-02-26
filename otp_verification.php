<?php
session_start();
include 'dbConnect.php';

// Check if the user has an email session (registered but unverified)
if (!isset($_SESSION['email'])) {
    header("Location: home.php");
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = implode("", $_POST['otp']); // Combine the OTP digits into a single string

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

            // Debugging: Check if the update was successful
            if ($stmt->affected_rows > 0) {
                unset($_SESSION['email']);
                $_SESSION['user_id'] = $user_data['id'];

                // Debugging: Print a message before redirecting
                echo "OTP verified successfully. Redirecting to home...";
                header("Location: home.php");
                exit();
            } else {
                $error_message = "Failed to update user verification status.";
            }
        } else {
            $error_message = "OTP has expired or is incorrect. Please try again.";
        }
    } else {
        $error_message = "Invalid request. Please sign up again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Verification</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
        }

        .otp-container {
            width: 100%;
            max-width: 400px;
            padding: 35px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: white;
        }

        .otp-container h2 {
            font-weight: 700;
            margin-bottom: 15px;
        }

        .otp-container p {
            font-size: 14px;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.8);
        }

        .otp-box {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .otp-box input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 18px;
            border-radius: 8px;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            outline: none;
            transition: 0.3s ease-in-out;
        }

        .otp-box input:focus {
            background: rgba(255, 255, 255, 0.4);
        }

        .btn-custom {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            background-color: #0069d9;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }

        .btn-custom:hover {
            background: #0056b3;
        }

        .error-message {
            color: #ff4d4d;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .resend-link {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: white;
        }

        .resend-link a {
            color: #ffcc00;
            text-decoration: none;
            font-weight: bold;
        }

        .resend-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="otp-container">
        <h2>OTP Verification</h2>
        <p>A verification code was sent to your email <b><?php echo htmlspecialchars($email); ?></b>. Enter it below:</p>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="otp-box">
                <input type="number" name="otp[]" maxlength="1" required>
                <input type="number" name="otp[]" maxlength="1" required>
                <input type="number" name="otp[]" maxlength="1" required>
                <input type="number" name="otp[]" maxlength="1" required>
                <input type="number" name="otp[]" maxlength="1" required>
                <input type="number" name="otp[]" maxlength="1" required>
            </div>
            <button type="submit" class="btn-custom">Verify</button>
        </form>

        <div class="resend-link">
            Didn't receive the code? <a href="resend_otp.php">Resend OTP</a>
        </div>
    </div>

    <script>
        // Automatically focus next input on entry
        document.querySelectorAll('.otp-box input').forEach((input, index, inputs) => {
            input.addEventListener('input', () => {
                if (input.value.length === input.maxLength && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
        });
    </script>

</body>
</html>