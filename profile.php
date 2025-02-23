<?php
session_start();
include 'dbConnect.php'; // Database connection

// Redirect to login if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, email, phone, course, year_of_study FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch user's clubs
$clubs_stmt = $conn->prepare("SELECT c.id, c.club_name, c.description 
                              FROM clubs c 
                              JOIN club_members cm ON c.id = cm.club_id 
                              WHERE cm.user_id = ?");
$clubs_stmt->bind_param("i", $user_id);
$clubs_stmt->execute();
$clubs_result = $clubs_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - CampusClubs</title>
    <link href="/CampusClubs/styles.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="logo">CampusClubs</div>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="my_clubs.php">My Clubs</a></li>
                <li><a href="announcements.php">Announcements</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-content">
        <div class="profile-container">
            <h2>My Profile</h2>
            <div class="profile-details">
                <p><strong>Name:</strong> <?= htmlspecialchars($user['full_name']); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']); ?></p>
                <p><strong>Course:</strong> <?= htmlspecialchars($user['course']); ?></p>
                <p><strong>Year of Study:</strong> <?= htmlspecialchars($user['year_of_study']); ?></p>
            </div>
        </div>

        <div class="my-clubs">
            <h2>My Clubs</h2>
            <div class="club-grid">
                <?php while ($club = $clubs_result->fetch_assoc()): ?>
                    <div class="club-card">
                        <h3><?= htmlspecialchars($club['club_name']); ?></h3>
                        <p><?= htmlspecialchars($club['description']); ?></p>
                        <a href="club_details.php?id=<?= $club['id']; ?>" class="club-link">View Club</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 CampusClubs</p>
    </footer>
</body>
</html>
