<?php
session_start();
include 'dbConnect.php'; // Database connection

// Check if user is logged in and redirect accordingly
if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === "admin") {
        header("Location: dashboard.php");
        exit();
    } elseif ($_SESSION['user_role'] === "student") {
        header("Location: profile.php");
        exit();
    }
}

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
                    <?php if (isset($_SESSION['user_role'])): ?>
                        <a id="profileLink" href="<?= $_SESSION['user_role'] === 'admin' ? 'dashboard.php' : 'profile.php'; ?>">Profile</a>
                    <?php else: ?>
                        <a id="profileLink" href="#">Profile</a>
                    <?php endif; ?>
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
