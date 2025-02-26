<?php
session_start();
include 'dbConnect.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include_once 'User.php';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_role = $_POST['user_role'];

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($user_role)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        try {
            // Check if email or username already exists
            $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? OR username = ?');
            $stmt->bind_param('ss', $email, $username);
            $stmt->execute();
            $existing_user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($existing_user) {
                $error_message = "Email or Username is already registered.";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $user = new User(0, $username, $email, $hashed_password, $user_role);

                if ($user->register($conn)) {
                    $otp = rand(100000, 999999);
                    $user->saveOtp($conn, $otp);

                    // Send OTP via email
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'leojoegem@gmail.com'; // Your Gmail email
                        $mail->Password = 'bydk jari tiah qbyx'; // Your Gmail app password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        $mail->setFrom('your-email@gmail.com', 'CampusClubs');
                        $mail->addAddress($email, $username);
                        $mail->isHTML(true);
                        $mail->Subject = 'Your OTP for CampusClubs Registration';
                        $mail->Body = "<p>Your OTP code is: <b>$otp</b></p>";

                        if ($mail->send()) {
                            $_SESSION['email'] = $email; // Store email in session
                            header('Location: otp_verification.php');
                            exit();
                        } else {
                            $error_message = "Failed to send OTP email. Please try again.";
                        }
                    } catch (Exception $e) {
                        $error_message = "Mailer Error: " . $mail->ErrorInfo;
                    }
                } else {
                    $error_message = "Registration failed. Please try again.";
                }
            }
        } catch (Exception $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusClubs - Sign Up</title>
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

        .signup-container {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: #fff;
        }

        .signup-container h2 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        select.form-control {
            appearance: none;
            background: rgba(255, 255, 255, 0.2);
            color: white;
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

        .footer {
            margin-top: 20px;
            font-size: 14px;
        }

        .footer a {
            color: #fff;
            font-weight: 500;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="signup-container">
        <h2>Create an Account on <span style="color: #f1f1f1;">CampusClubs</span></h2>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div class="form-group">
                <select class="form-control" name="user_role" required>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn-custom">Sign Up</button>
        </form>

        <div class="footer">
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
