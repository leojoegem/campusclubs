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
            font-family: 'Copperplate', serif;
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
            overflow: hidden;
        }
        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .hero img.active {
            opacity: 1;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
        }

        /* Upcoming Events Section */
        .events-section {
            font-family: 'Copperplate', serif;
            padding: 40px 20px;
            background-color: #fff;
            text-align: center;
        }
        .events-section h2 {
            font-family: 'Copperplate', serif;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #007bff;
        }
        .events-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .event-banner {
            flex: 1 1 calc(33.333% - 40px);
            max-width: calc(33.333% - 40px);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .event-banner:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .event-banner img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Photo Gallery Section */
        .gallery-section {
            font-family: 'Copperplate', serif;
            padding: 40px 20px;
            background-color: #f9f9f9;
            text-align: center;
        }
        .gallery-section h2 {
            font-family: 'Copperplate', serif;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #007bff;
        }
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .gallery-item {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .gallery-item img {
            width: 100%;
            height: auto;
        }

        /* Alumni Section */
        .alumni-section {
            padding: 40px 20px;
            background-color: #fff;
            text-align: center;
            position: relative;
        }
        .alumni-section h2 {
            font-family: 'Copperplate', serif;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #007bff;
        }
        .alumni-section p {
            font-family: 'Copperplate', serif;
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 30px;
        }
        .alumni-section .alumni-link {
            font-family: 'Copperplate', serif;
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .alumni-section .alumni-link:hover {
            background-color: #0056b3;
        }
        .alumni-section .arrow {
            font-size: 2rem;
            color: #007bff;
            margin-top: 10px;
            cursor: pointer;
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
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section with Carousel -->
    <div class="hero">
        <img id="heroImage1" src="images/diversity6.webp" alt="Club Image 1" class="active">
        <img id="heroImage2" src="images/events7.jpg" alt="Club Image 2">
        <img id="heroImage3" src="images/events6.webp" alt="Club Image 3">
        <h1>Discover Your Passion, Connect with Your Community</h1>
    </div>

    <!-- Upcoming Events Section -->
    <section class="events-section">
        <h2>Upcoming Events</h2>
        <div class="events-container">
            <div class="event-banner">
                <img src="images/events7.jpg" alt="Event 1">
            </div>
            <div class="event-banner">
                <img src="images/events6.webp" alt="Event 2">
            </div>
            <div class="event-banner">
                <img src="images/events5.jpg" alt="Event 3">
            </div>
        </div>
    </section>

    <!-- Photo Gallery Section -->
    <section class="gallery-section">
        <h2>Photo Gallery</h2>
        <div class="gallery-container">
            <div class="gallery-item">
                <img src="images/diversity2.jpg" alt="Gallery Image 1">
            </div>
            <div class="gallery-item">
                <img src="images/diversity3.JPG" alt="Gallery Image 2">
            </div>
            <div class="gallery-item">
                <img src="images/diversity4.png" alt="Gallery Image 3">
            </div>
            <div class="gallery-item">
                <img src="images/diversity2.jpg" alt="Gallery Image 4">
            </div>
        </div>
    </section>

    <!-- Alumni Section -->
    <section class="alumni-section">
        <h2>Alumni</h2>
        <p>Stay connected with our alumni network and explore their achievements.</p>
        <a href="alumni.php" class="alumni-link">Visit Alumni Page</a>
        <div class="arrow" onclick="window.location.href='alumni.php'">&#8594;</div>
    </section>

    <footer style="position: relative; text-align: center; padding: 20px; background-color: #007bff; color: white; margin-top: 40px;">
    <p style="margin: 0;">&copy; 2023 CampusClubs</p>
    <a href="dashboard.php" 
       style="color: white; text-decoration: none; font-size: 1.2em; 
              font-weight: bold; position: absolute; bottom: 10px; right: 20px; 
              opacity: 0.6; transition: opacity 0.3s ease, transform 0.2s ease;">
        D
    </a>
</footer>


    <script>
        // Hero Section Carousel
        let heroImages = [
            "images/diversity6.webp",
            "images/events7.jpg",
            "images/events6.webp"
        ];
        let currentIndex = 0;

        function rotateHeroImages() {
            const heroImageElements = document.querySelectorAll('.hero img');
            heroImageElements.forEach((img, index) => {
                img.classList.remove('active');
                if (index === currentIndex) {
                    img.classList.add('active');
                }
            });
            currentIndex = (currentIndex + 1) % heroImages.length;
        }
        setInterval(rotateHeroImages, 3000);

        // Event Section Image Sizing
        const eventBanners = document.querySelectorAll('.event-banner');
        eventBanners.forEach(banner => {
            const img = banner.querySelector('img');
            img.onload = () => {
                banner.style.height = `${img.clientHeight}px`;
            };
        });
    </script>
</body>
</html>