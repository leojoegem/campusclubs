<?php
session_start();
include 'dbConnect.php'; // Database connection

// Club class using OOP
class Club {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getClubs() {
        $stmt = $this->conn->prepare("SELECT * FROM clubs");
        $stmt->execute();
        return $stmt->get_result();
    }
}

$club = new Club($conn);
$clubs = $club->getClubs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusClubs</title>
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
                <li>
                    <?php
                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                        echo '<a href="admin_dashboard.php">Profile</a>';
                    } else {
                        echo '<a href="profile.php">Profile</a>';
                    }
                    ?>
                </li>
                <li>
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '<a href="logout.php">Logout</a>';
                    } else {
                        echo '<a href="login.php">Login</a>';
                    }
                    ?>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main content wrapper -->
    <div class="main-content">
        <div class="hero">
            <h1>Discover Your Passion, Connect with Your Community</h1>
            <p>Find and join clubs that match your interests.</p>
            <a href="#clubs" class="cta-button">Explore Clubs</a>
        </div>

        <section id="clubs" class="section">
            <div class="club-grid">
                <?php while ($row = $clubs->fetch_assoc()): ?>
                    <div class="club-card">
                        <h3><?= htmlspecialchars($row['club_name']); ?></h3>
                        <p><?= htmlspecialchars($row['description']); ?></p>
                        <a href="club_details.php?id=<?= $row['id']; ?>" class="club-link">Learn More</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </div> <!-- End of main-content -->

    <footer>
        <p>&copy; 2025 CampusClubs</p>
    </footer>
</body>
</html>
