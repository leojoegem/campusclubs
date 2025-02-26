<?php
session_start();
include 'dbConnect.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to join a club.";
    header("Location: login.php");
    exit();
}

if (isset($_GET['club_id']) && is_numeric($_GET['club_id'])) {
    $club_id = intval($_GET['club_id']);
    $user_id = $_SESSION['user_id'];

    // Check if the user is already a member of the club
    $check_sql = "SELECT * FROM memberships WHERE user_id = ? AND club_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $club_id);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows == 0) {
        // Add the user to the club
        $insert_sql = "INSERT INTO memberships (user_id, club_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ii", $user_id, $club_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "You have successfully joined the club!";
        } else {
            $_SESSION['message'] = "Error joining the club. Please try again.";
        }
    } else {
        $_SESSION['message'] = "You are already a member of this club.";
    }
    $stmt->close();
} else {
    $_SESSION['message'] = "Invalid club ID.";
}

$conn->close();
header("Location: clubs.php");
exit();
?>
