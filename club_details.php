<?php
session_start();
include 'dbConnect.php'; // Ensure correct DB connection

// Debug: Check if club_id is being passed correctly
if (!isset($_GET['club_id'])) {
    die("Error: club_id not provided in URL.");
}

$club_id = intval($_GET['club_id']);

// Fetch club details using prepared statements
$club_sql = "SELECT * FROM clubs WHERE id = ?";
$stmt = $conn->prepare($club_sql);
$stmt->bind_param("i", $club_id);
$stmt->execute();
$club_result = $stmt->get_result();

if (!$club_result || $club_result->num_rows == 0) {
    die("Error: No club found for ID " . htmlspecialchars($club_id)); // Debugging
}

$club = $club_result->fetch_assoc();

// Fetch club members using prepared statement
$members_sql = "SELECT users.username 
                FROM club_members 
                JOIN users ON club_members.user_id = users.id 
                WHERE club_members.club_id = ?";
$members_stmt = $conn->prepare($members_sql);
$members_stmt->bind_param("i", $club_id);
$members_stmt->execute();
$members_result = $members_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($club['name']); ?> - CampusClubs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        header .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        header ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        header ul li {
            margin: 0 10px;
        }

        header ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        header ul li a:hover {
            color: #d4e2ff;
        }

        .club-details {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .club-details img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .club-details h1 {
            font-size: 2rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .club-details p {
            margin: 10px 0;
            color: #555;
            line-height: 1.6;
        }

        .members-list {
            margin-top: 20px;
        }

        .members-list h2 {
            font-size: 1.5rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .members-list ul {
            list-style: none;
            padding: 0;
        }

        .members-list ul li {
            background-color: #f1f1f1;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
            margin-top: 40px;
            position: relative;
        }

        /* Dashboard shortcut 'D' */
        .dashboard-link {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            font-weight: bold;
            position: absolute;
            bottom: 10px;
            right: 20px;
            opacity: 0.6;
            transition: opacity 0.3s ease, transform 0.2s ease;
        }

        .dashboard-link:hover {
            opacity: 1;
            transform: scale(1.1);
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="club-details">
        <?php if (!empty($club['image']) && filter_var($club['image'], FILTER_VALIDATE_URL)): ?>
            <img src="<?php echo htmlspecialchars($club['image']); ?>" alt="<?php echo htmlspecialchars($club['name']); ?>">
        <?php endif; ?>

        <h1><?php echo htmlspecialchars($club['name']); ?></h1>
        <p class="description"><?php echo nl2br(htmlspecialchars($club['description'])); ?></p>
        <p><b>Category:</b> <?php echo htmlspecialchars($club['category']); ?></p>
        <p><b>Contact:</b> <?php echo htmlspecialchars($club['contact_info']); ?></p>
        <p><b>Meeting Schedule:</b> <?php echo htmlspecialchars($club['meeting_schedule']); ?></p>
        <p><b>Location:</b> <?php echo htmlspecialchars($club['location']); ?></p>

        <div class="members-list">
            <h2>Members</h2>
            <?php if ($members_result->num_rows > 0): ?>
                <ul>
                    <?php while ($member = $members_result->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($member['username']); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No members yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 CampusClubs</p>
        <a href="dashboard.php" class="dashboard-link">D</a>
    </footer>
</body>
</html>
