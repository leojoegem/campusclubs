<?php
// Start session for user login tracking
session_start();

// Include your database connection file
include('dbConnect.php'); // Assuming dbConnect.php connects to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get email, password, and role from the form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Query to fetch user data based on email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query); // Prepare the statement
    $stmt->bind_param("s", $email); // Bind the email to the query
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc(); // Fetch the user record
    
    // Check if user exists
    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Check if the role matches
            if ($role === $user['role']) {
                // Successful login, set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                
                // Redirect based on role
                if ($role === 'admin') {
                    header('Location: dashboard.php');
                } else {
                    header('Location: home.php');
                }
                exit;
            } else {
                $error_message = "Invalid role. Please select the correct role.";
            }
        } else {
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        $error_message = "No user found with that email. Please check your email.";
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
    <title>CampusClubs - Login</title>

    <!-- CSS Files -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;700;900&display=swap" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/tooplate-little-fashion.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f0f5;
        }

        .login-container {
            max-width: 420px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
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

    <div class="login-container">
        <h2>Welcome Back to <span style="color: #0069d9;">CampusClubs</span></h2>

        <?php
        if (isset($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label for="role">Select Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <a href="#" class="text-primary">Forgot password?</a>
            </div>
            <button type="submit" class="btn-custom">Login</button>
        </form>
        <div class="footer">
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
