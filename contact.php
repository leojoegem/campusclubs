<?php
// Include your database connection (dbConnect.php)
include 'dbConnect.php';

// Fetch admins and their clubs from the database
$admins = [];
$stmt = $conn->prepare("SELECT u.id, u.username, c.club_name FROM users u JOIN clubs c ON u.id = c.admin_id WHERE u.role = 'admin'"); // Adjust query if needed
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $admins[] = $row;
}

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $user_email =  $_POST['email']; // Get user's email

    // Basic form validation (add more as needed)
    if (empty($admin_id) || empty($subject) || empty($message) || empty($user_email)) {
        $error_message = "All fields are required.";
    } else if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Here, you would typically send the inquiry via email or store it in the database.
        // I'll show a basic email example using mail() (replace with a more robust email solution)
        $to = ""; // Initialize the recipient email

        // Find the admin's email based on ID
        $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $admin_result = $stmt->get_result();

        if ($admin_result->num_rows > 0) {
            $admin_data = $admin_result->fetch_assoc();
            $to = $admin_data['email'];
        }

        if (!empty($to)) { // Make sure the recipient email is set
            $headers = "From: " . $user_email; // Set the "From" header
            if (mail($to, $subject, $message, $headers)) {
                $success_message = "Your inquiry has been sent successfully!";
            } else {
                $error_message = "Failed to send inquiry. Please try again later.";
            }
        } else {
            $error_message = "Recipient email not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 700px; /* Adjusted width */
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select,
        input[type="text"],
        textarea,
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Ensures padding is included in width */
        }

        textarea {
            height: 150px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Contact Us</h1>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="admin_id">To Admin/Club:</label>
            <select name="admin_id" id="admin_id">
                <option value="">Select Admin/Club</option>
                <?php foreach ($admins as $admin): ?>
                    <option value="<?php echo $admin['id']; ?>"><?php echo $admin['username'] . " (" . $admin['club_name'] . ")"; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="email">Your Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="subject">Subject:</label>
            <input type="text" name="subject" id="subject" required>

            <label for="message">Message:</label>
            <textarea name="message" id="message" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>

</body>
</html>