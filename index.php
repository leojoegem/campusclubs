<?php
session_start();
include('dbConnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        if ($role === $user['role']) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['is_admin'] = ($role === 'admin');

            header('Location: ' . ($role === 'admin' ? 'dashboard.php' : 'home.php'));
            exit;
        } else {
            $error_message = "Invalid role selected.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusClubs - Login</title>
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

        .login-container {
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

        .login-container h2 {
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
    <div class="login-container">
        <h2>Welcome to <span style="color: #f1f1f1;">CampusClubs</span></h2>

        <?php if (isset($error_message)) { echo "<div class='error-message'>$error_message</div>"; } ?>

        <form action="" method="POST">
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <select class="form-control" name="role" required>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn-custom">Login</button>
        </form>

        <div class="footer">
            <p>Forgot password? <a href="#">Reset here</a></p>
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
