<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusClubs</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
            display: flex; /* Added Flexbox */
            flex-direction: column; /* Arrange content vertically */
            min-height: 100vh; /* Ensure the page takes at least the full viewport height */
}
        
        /* Navbar Styles */
        .navbar {
            background-color: #007BFF;
            padding: 10px 20px;
            text-align: center;
        }
        .navbar a {
            color: #fff;
            font-size: 16px;
            margin: 0 15px;
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
        }
        .navbar a:hover {
            background-color: #0056b3;
            border-radius: 5px;
        }

        /* Container */
        .container {
            flex-grow: 1; /* This makes the container take up available space */
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 15px;
        }

        /* Intro Section */
        .intro {
            text-align: center;
            margin-bottom: 40px;
        }
        .intro h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
        }
        .intro p {
            font-size: 18px;
            color: #555;
            line-height: 1.5;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Clubs Section */
        .clubs {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            justify-items: center;
        }
        .club {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .club:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .club img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .club h2 {
            font-size: 24px;
            color: #007BFF;
            margin-bottom: 10px;
        }
        .club p {
            font-size: 16px;
            color: #555;
            margin-bottom: 0;
        }

        /* Footer Styles */
        .footer {
            background-color: #333;
            color: #f9f9f9;
            text-align: center;
            padding: 15px;
            width: 100%;
        }
        .footer p {
            margin: 0;
        }

    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <a href="#home">Home</a>
        <a href="about.php">About</a>
        <a href="clubs.php">Clubs</a>
        <a href="contact.php">Contact</a>
        <a href="profile.php">profile</a>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Introduction Section -->
        <div class="intro">
            <h1>Welcome to CampusClubs</h1>
            <p>Discover and join a variety of clubs on campus. Connect with like-minded individuals, enhance your skills, and make lasting memories.</p>
        </div>

        <!-- Clubs Section -->
        <div class="clubs">
            <!-- Club 1 -->
            <div class="club">
                <img src="placeholder.jpg" alt="Club Image">
                <h2>Club Name 1</h2>
                <p>Explore new opportunities and collaborate with peers on exciting projects!</p>
            </div>
            <!-- Club 2 -->
            <div class="club">
                <img src="placeholder.jpg" alt="Club Image">
                <h2>Club Name 2</h2>
                <p>Join us for engaging discussions and events that empower your growth.</p>
            </div>
            <!-- Club 3 -->
            <div class="club">
                <img src="placeholder.jpg" alt="Club Image">
                <h2>Club Name 3</h2>
                <p>Get involved in community service projects and make a difference on campus.</p>
            </div>
            <!-- More clubs can be added here -->
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 CampusClubs. All rights reserved.</p>
    </div>

</body>
</html>
