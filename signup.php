<?php
session_start();
include 'dbConnect.php'; // Database connection

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoloader
require 'vendor/autoload.php';

// Include User class
include_once 'User.php';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_role = $_POST['user_role'];

    // Validation checks
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

                        $mail->setFrom('leojoegem@gmail.com', 'CampusClubs');
                        $mail->addAddress($email, $username);

                        $mail->isHTML(true);
                        $mail->Subject = 'Your OTP for CampusClubs Registration';
                        $mail->Body = "<p>Your OTP code is: <b>$otp</b></p>";

                        if ($mail->send()) {
                            $_SESSION['email'] = $email; // Store email in session
                            header('Location: otp_verification.php');
                            exit(); // Ensure redirection
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

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>CampusClubs - Sign Up</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;700;999&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/tooplate-little-fashion.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f0f5;
        }
        .signup-container {
            max-width: 420px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .signup-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 700;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ddd;
        }
        .btn-custom {
            width: 100%;
            padding: 12px;
            background-color: #0069d9;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
        }
        .footer a {
            text-decoration: none;
            color: #0069d9;
            font-weight: 500;
        }
        .footer a:hover {
            color: #0056b3;
        }
        .error-message {
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="signup-container">
        <h2>Create an Account on <span style="color: #0069d9;">CampusClubs</span></h2>

        <?php if (!empty($error_message)):?>
            <div class="error-message"><?php echo $error_message;?></div>
        <?php endif;?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <div class="form-group">
                <label for="user_role">Select User Type</label>
                <select class="form-control" id="user_role" name="user_role" required>
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
