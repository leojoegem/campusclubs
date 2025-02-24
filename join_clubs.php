<?php
session_start();
include 'dbConnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['club_id'])) {
    $club_id = intval($_GET['club_id']);
    $user_id = $_SESSION['user_id'];

    // Check if the user is already a member of the club
    $check_sql = "SELECT * FROM club_members WHERE user_id = $user_id AND club_id = $club_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows == 0) {
        // Add the user to the club
        $insert_sql = "INSERT INTO club_members (user_id, club_id) VALUES ($user_id, $club_id)";
        if ($conn->query($insert_sql) === TRUE) {
            echo "You have successfully joined the club!";
        } else {
            echo "Error joining the club.";
        }
    } else {
        echo "You are already a member of this club.";
    }
} else {
    echo "Invalid club ID.";
}

header("Location: clubs.php");
exit();
?>