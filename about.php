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

        /* Navigation Bar (Unchanged) */
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
            height: 500px;
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
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
            margin: 0;
            padding: 0 20px;
        }
        .hero p {
            font-size: 1.5rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
            margin-top: 20px;
        }

        /* About Section */
        .about-section {
            padding: 60px 20px;
            background-color: #fff;
            text-align: center;
        }
        .about-section h2 {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 20px;
        }
        .about-section p {
            font-size: 1.1rem;
            color: #555;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Club Section */
        .club-section {
            padding: 60px 20px;
            background-color: #f9f9f9;
        }
        .club-section h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 40px;
            color: #007bff;
        }
        .club-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
        }
        .club-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .club-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .club-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .club-card .content {
            padding: 20px;
            text-align: center;
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

        /* Testimonials Section */
        .testimonials-section {
            padding: 60px 20px;
            background-color: #fff;
            text-align: center;
        }
        .testimonials-section h2 {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 40px;
        }
        .testimonials-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
        }
        .testimonial-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .testimonial-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .testimonial-card h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #333;
        }
        .testimonial-card p {
            font-size: 1rem;
            color: #666;
            font-style: italic;
        }

        /* Footer (Unchanged) */
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

    <!-- Hero Section -->
    <div class="hero">
        <img id="heroImage" src="images/hero1.jpg" alt="Campus Life">
        <div>
            <h1>Discover Your Passion, Connect with Your Community</h1>
            <p>Join a vibrant community of students and explore your interests.</p>
        </div>
    </div>

    <!-- About Section -->
    <section class="about-section">
        <h2>About CampusClubs</h2>
        <p>
            CampusClubs is your gateway to a thriving campus life. Whether you're into sports, arts, technology, or community service, 
            we have a club for you. Our platform connects students with like-minded peers, fostering creativity, collaboration, and growth.
        </p>
    </section>

    <!-- Club Section -->
    <section class="club-section">
        <h2>Explore Clubs</h2>
        <div class="club-container">
            <?php while ($row = $clubs->fetch_assoc()): ?>
                <div class="club-card">
                    <img src="images/club_placeholder.jpg" alt="<?= htmlspecialchars($row['name']); ?>">
                    <div class="content">
                        <h3><?= htmlspecialchars($row['name']); ?></h3>
                        <p><?= htmlspecialchars($row['description']); ?></p>
                        <a href="club_details.php?id=<?= $row['id']; ?>" class="club-link">Learn More</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <h2>What Our Members Say</h2>
        <div class="testimonials-container">
            <div class="testimonial-card">
                <img src="images/testimonial1.jpg" alt="John Doe">
                <h4>John Doe</h4>
                <p>"CampusClubs helped me find my passion for photography. I've made lifelong friends and learned so much!"</p>
            </div>
            <div class="testimonial-card">
                <img src="images/testimonial2.jpg" alt="Jane Smith">
                <h4>Jane Smith</h4>
                <p>"Joining the debate club through CampusClubs has been a game-changer for my confidence and skills."</p>
            </div>
            <div class="testimonial-card">
                <img src="images/diversity.jpg" alt="Alex Johnson">
                <h4>Alex Johnson</h4>
                <p>"I love how easy it is to discover and join clubs that match my interests. Highly recommend!"</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 CampusClubs</p>
    </footer>

    <script>
        // Hero Image Rotation
        let images = ["images/diversity.jpg", "images/hero2.jpg", "images/hero3.jpg"];
        let currentIndex = 0;
        function rotateImages() {
            document.getElementById("heroImage").src = images[currentIndex];
            currentIndex = (currentIndex + 1) % images.length;
        }
        setInterval(rotateImages, 3000);
    </script>
</body>
</html>