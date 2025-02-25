<?php
session_start();
include 'dbConnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // File Upload Logic
    if (!empty($_FILES['club_image']['name'])) {
        $targetDir = "uploads/clubs/";  // Ensure this directory exists
        $fileName = basename($_FILES["club_image"]["name"]);
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        
        // Generate unique filename to prevent overwrites
        $newFileName = uniqid("club_") . "." . $fileExt;
        $targetFilePath = $targetDir . $newFileName;

        // Allowed file types
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($fileExt), $allowedTypes)) {
            if (move_uploaded_file($_FILES["club_image"]["tmp_name"], $targetFilePath)) {
                // Insert into database
                $sql = "INSERT INTO clubs (name, description, image) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $name, $description, $targetFilePath);
                if ($stmt->execute()) {
                    echo "Club added successfully!";
                } else {
                    echo "Database error: " . $conn->error;
                }
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and GIF allowed.";
        }
    } else {
        echo "Please upload an image.";
    }
}
?>
