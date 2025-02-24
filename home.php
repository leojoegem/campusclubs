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
    <style>
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            }
            .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            }
            .navbar .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
            }
            .navbar .nav-links li {
            display: inline;
            }
            .navbar .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s ease;
            }
            .navbar .nav-links a:hover {
            color: #ff6347;
            }
        .hero {
            position: relative;
            text-align: center;
            color: white;
            height: 400px;
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            transition: opacity 1s ease-in-out;
        }
        .club-container {
            overflow-x: auto;
            display: flex;
            gap: 15px;
            padding: 20px;
            white-space: nowrap;
        }
        .club-card {
            display: inline-block;
            width: 250px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
<header>
        <nav class="navbar">
            <div class="logo">CampusClubs</div>
            <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="clubs.php">Clubs</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="hero">
        <img id="heroImage" src="placeholder1.jpg" alt="Club Image">
        <h1>Discover Your Passion, Connect with Your Community</h1>
    </div>

    <section class="club-section">
        <h2 style="text-align: center;">Explore Clubs</h2>
        <div class="club-container">
            <?php while ($row = $clubs->fetch_assoc()): ?>
                <div class="club-card">
                    <h3><?= htmlspecialchars($row['name']); ?></h3>
                    <p><?= htmlspecialchars($row['description']); ?></p>
                    <a href="club_details.php?id=<?= $row['id']; ?>" class="club-link">Learn More</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 CampusClubs</p>
    </footer>

    <script>
        let images = ["placeholder1.jpg", "placeholder2.jpg", "placeholder3.jpg"];
        let currentIndex = 0;
        function rotateImages() {
            document.getElementById("heroImage").src = images[currentIndex];
            currentIndex = (currentIndex + 1) % images.length;
        }
        setInterval(rotateImages, 3000);
    </script>
</body>
</html>
