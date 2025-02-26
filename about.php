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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusClubs - About Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }

        /* Navigation Bar (Unchanged) */
        .navbar {
            background-color: #333;
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
        }
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
        }

        /* About Section */
        .about-section {
            padding: 60px 20px;
            background-color: #fff;
        }
        .about-section h2 {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 20px;
            text-align: center;
        }
        .about-section p {
            font-size: 1.1rem;
            color: #555;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Mission & Vision Section */
        .mission-vision-section {
            padding: 60px 20px;
            background-color: #f9f9f9;
        }
        .mission-vision-section h2 {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 40px;
            text-align: center;
        }
        .mission-vision-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
        }
        .mission-vision-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .mission-vision-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .mission-vision-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }
        .mission-vision-card p {
            font-size: 1rem;
            color: #666;
        }

        /* Team Section */
        .team-section {
            padding: 60px 20px;
            background-color: #fff;
        }
        .team-section h2 {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 40px;
            text-align: center;
        }
        .team-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            padding: 20px;
        }
        .team-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .team-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .team-card h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #333;
        }
        .team-card p {
            font-size: 1rem;
            color: #666;
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
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand logo" href="#">CampusClubs</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto nav-links">
                        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="clubs.php">Clubs</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <div class="hero">
        <img src="images/about-hero.jpg" alt="About Us">
        <h1>About CampusClubs</h1>
    </div>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <h2>Who We Are</h2>
            <p>
                CampusClubs is a platform dedicated to connecting students with clubs and organizations that match their interests. 
                Our mission is to foster a vibrant campus community where students can explore their passions, develop new skills, 
                and build lifelong friendships.
            </p>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="mission-vision-section">
        <div class="container">
            <h2>Our Mission & Vision</h2>
            <div class="mission-vision-container">
                <div class="mission-vision-card">
                    <h3>Mission</h3>
                    <p>
                        To provide a platform that empowers students to discover, join, and create clubs that align with their interests, 
                        fostering personal growth and community engagement.
                    </p>
                </div>
                <div class="mission-vision-card">
                    <h3>Vision</h3>
                    <p>
                        To create a campus environment where every student feels connected, inspired, and supported in pursuing their passions.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2>Meet Our Team</h2>
            <div class="team-container">
                <div class="team-card">
                    <img src="images/diversity5.avif" alt="John Doe">
                    <h4>Josalah</h4>
                    <p>Founder & CEO</p>
                </div>
                <div class="team-card">
                    <img src="images/diversity6.webp" alt="Jane Smith">
                    <h4>Harry Kane</h4>
                    <p>Head of Operations</p>
                </div>
                <div class="team-card">
                    <img src="images/diversity3.JPG" alt="Alex Johnson">
                    <h4>Jamal Musiala</h4>
                    <p>Community Manager</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 CampusClubs</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>