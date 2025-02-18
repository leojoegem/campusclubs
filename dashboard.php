<?php
session_start();
include 'dbConnect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php"); // Redirect to login if not admin
    exit();
}

// Handle adding a new club
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_club'])) {
    $club_name = $_POST['club_name'];
    $description = $_POST['description'];
    $category = $_POST['category']; // Add category field
    $contact_info = $_POST['contact_info']; // Add contact info field
    $image = ''; // Initialize image variable

    // Handle image upload (if an image was selected)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/"; // Directory to store uploaded images. Create this folder!
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) { // 500KB limit
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your image was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file; // Store the file path in the database
            } else {
                echo "Sorry, there was an error uploading your image.";
            }
        }
    }


    $stmt = $conn->prepare("INSERT INTO clubs (club_name, description, category, contact_info, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $club_name, $description, $category, $contact_info, $image); // Include category and contact info
    $stmt->execute();
    $stmt->close();

    // Redirect to refresh the page after adding
    header("Location: dashboard.php");
    exit();
}

// Handle deleting a club
if (isset($_GET['delete_club'])) {
    $club_id = $_GET['delete_club'];
    $stmt = $conn->prepare("DELETE FROM clubs WHERE id = ?");
    $stmt->bind_param("i", $club_id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php"); // Redirect after delete
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add dashboard-specific styles here */
        .club-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .club-table th, .club-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .club-table th {
            background-color: #f2f2f2;
        }

        .club-table .delete-link {
            color: red;
            text-decoration: none;
            margin-left: 10px;
        }
        .add-club-form label{
            display: block;
            margin-bottom: 5px;
        }
        .add-club-form input[type="text"],
        .add-club-form textarea,
        .add-club-form select{
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .add-club-form button{
            padding: 10px 15px;
            background-color: #4CAF50; /* Green */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .add-club-form button:hover{
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">CampusClubs</div>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="clubs.php">Clubs</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <h1>Admin Dashboard</h1>

    <h2>Add New Club</h2>
    <form class="add-club-form" method="post" enctype="multipart/form-data">
        <label for="club_name">Club Name:</label>
        <input type="text" name="club_name" id="club_name" required><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea><br>

        <label for="category">Category:</label>
        <input type="text" name="category" id="category" required><br>

        <label for="contact_info">Contact Info:</label>
        <input type="text" name="contact_info" id="contact_info" required><br>

        <label for="image">Image:</label>
        <input type="file" name="image" id="image"><br>

        <button type="submit" name="add_club">Add Club</button>
    </form>


    <h2>Existing Clubs</h2>
    <table class="club-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Club Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Contact Info</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM clubs");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['club_name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['category'] . "</td>";
                echo "<td>" . $row['contact_info'] . "</td>";
                echo "<td>";
                if (!empty($row['image'])) {
                    echo "<img src='" . $row['image'] . "' alt='Club Image' style='max-width: 50px; height: auto;'>";
                }
                echo "</td>";
                echo "<td><a href='dashboard.php?delete_club=" . $row['id'] . "' class='delete-link' onclick=\"return confirm('Are you sure you want to delete this club?')\">Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>