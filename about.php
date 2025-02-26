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

// Club class
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="/CampusClubs/styles.css" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar .logo {
            font-family: 'Copperplate', serif;
            font-size: 24px;
            font-weight: bold;
            color: #fff;
        }
        .navbar .nav-links {
            font-family: 'Copperplate', serif;
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
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

        /* Hero Section */
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
            margin-bottom: 40px;
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
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
        }

        /* Club Section */
        .club-section {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .club-section h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #007bff;
        }
        .club-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .club-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .club-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .club-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }
        .club-card p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 15px;
        }
        .club-card .club-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .club-card .club-link:hover {
            background-color: #0056b3;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 40px;
        }
        footer p {
            margin: 0;
            font-size: 1rem;
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
                <li><a href="about.php">About</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="hero">
        <img id="heroImage" src="images/diversity2.jpg" alt="Club Image">
        <h1>Discover Your Passion, Connect with Your Community</h1>
    </div>

    <section class="club-section">
        <h2>Explore Clubs</h2>
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
        let images = ["images/diversity2.jpg", "placeholder2.jpg", "placeholder3.jpg"];
        let currentIndex = 0;
        function rotateImages() {
            document.getElementById("heroImage").src = images[currentIndex];
            currentIndex = (currentIndex + 1) % images.length;
        }
        setInterval(rotateImages, 3000);
    </script>
</body>
</html>