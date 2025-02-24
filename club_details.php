<?php
session_start();
include 'dbConnect.php'; // Include your database connection

// Check if the club_id is provided in the URL
if (!isset($_GET['club_id'])) {
    header("Location: clubs.php");
    exit();
}

$club_id = intval($_GET['club_id']);

// Fetch club details
$club_sql = "SELECT * FROM clubs WHERE id = $club_id";
$club_result = $conn->query($club_sql);

if ($club_result->num_rows == 0) {
    echo "Club not found.";
    exit();
}

$club = $club_result->fetch_assoc();

// Fetch members of the club
$members_sql = "SELECT users.username 
                FROM club_members 
                JOIN users ON club_members.user_id = users.id 
                WHERE club_members.club_id = $club_id";
$members_result = $conn->query($members_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $club['name']; ?> - CampusClubs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Minimalistic and user-friendly styles */
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
        }

        .club-details {
            max-width: 1200px;
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

        .club-details .members-list {
            margin-top: 20px;
        }

        .club-details .members-list h2 {
            font-size: 1.5rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .club-details .members-list ul {
            list-style: none;
            padding: 0;
        }

        .club-details .members-list ul li {
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
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<li><a href="dashboard.php">Dashboard</a></li>';
                    echo '<li><a href="logout.php">Logout</a></li>';
                } else {
                    echo '<li><a href="login.php">Login</a></li>';
                    echo '<li><a href="register.php">Register</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <div class="club-details">
        <?php if (!empty($club['image'])): ?>
            <img src="<?php echo $club['image']; ?>" alt="<?php echo $club['name']; ?>">
        <?php endif; ?>
        <h1><?php echo $club['name']; ?></h1>
        <p class="description"><?php echo $club['description']; ?></p>
        <p><b>Category:</b> <?php echo $club['category']; ?></p>
        <p><b>Contact:</b> <?php echo $club['contact_info']; ?></p>
        <p><b>Meeting Schedule:</b> <?php echo $club['meeting_schedule']; ?></p>
        <p><b>Location:</b> <?php echo $club['location']; ?></p>

        <div class="members-list">
            <h2>Members</h2>
            <?php if ($members_result->num_rows > 0): ?>
                <ul>
                    <?php while ($member = $members_result->fetch_assoc()): ?>
                        <li><?php echo $member['username']; ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No members yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 CampusClubs</p>
    </footer>
</body>
</html>