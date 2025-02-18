<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusClubs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* styles.css */
body {
    font-family: sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4; /* Light background */
    color: #333; /* Dark text for contrast */
}

header {
    background-color: #333; /* Darker header background */
    color: white;
    padding: 20px;
}

nav {
    display: flex;
    justify-content: space-between; /* Align logo and links */
    align-items: center;
}

.logo {
    font-size: 1.5em;
    font-weight: bold;
}

nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

nav li {
    margin-left: 20px;
}

nav a {
    color: white;
    text-decoration: none;
    padding: 5px 10px; /* Add some padding around links */
    border-radius: 5px; /* Slightly rounded corners */
    transition: background-color 0.3s; /* Smooth hover effect */
}

nav a:hover {
    background-color: rgba(255, 255, 255, 0.2); /* Slightly transparent white on hover */
}

.hero {
    text-align: center;
    padding: 100px 0;
}

.hero h1 {
    font-size: 2.5em;
    margin-bottom: 20px;
}

.hero p {
    font-size: 1.2em;
    margin-bottom: 30px;
}

.cta-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: orange;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 1.1em;
    transition: background-color 0.3s;
}

.cta-button:hover {
    background-color: darkorange;
}

.section {
    padding: 40px;
    text-align: center; /* Center content within sections */
}


.club-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Responsive grid */
    gap: 20px;
}

.club-card {
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 2px 2px 5px rgba(0,0,0,0.1); /* Subtle shadow */
    background-color: white;
    text-align: left; /* Align text within card to the left */
    transition: transform 0.2s; /* Add a transition for the transform property */
}

.club-card:hover {
    transform: translateY(-5px); /* Move the card up slightly on hover */
    box-shadow: 4px 4px 8px rgba(0,0,0,0.15); /* Increase shadow on hover */
}

.club-card h3 {
    margin-bottom: 10px;
}

.club-link {
    display: inline-block;
    padding: 8px 16px;
    background-color: #007bff; /* Blue button */
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
    transition: background-color 0.3s;
}

.club-link:hover {
    background-color: #0056b3; /* Darker blue on hover */
}


footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px;
}

/* Responsive adjustments (example) */
@media (max-width: 768px) {
    nav ul {
        flex-direction: column; /* Stack links vertically on smaller screens */
        text-align: center; /* Center text in links */
    }

    nav li {
        margin: 10px 0; /* Add top/bottom margins to stacked links */
        margin-left: 0; /* Remove left margin from stacked links */
    }

    .club-grid {
        grid-template-columns: 1fr; /* Single column on smaller screens */
    }
}
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">CampusClubs</div>  <ul>
                <li><a href="#about">About</a></li>
                <li><a href="#clubs">Clubs</a></li>
                <li><a href="#events">Events</a></li>
                <li><a href="#contact">Contact</a></li>
                <?php
                session_start();
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
        <div class="hero">
            <h1>Discover Your Passion, Connect with Your Community</h1>
            <p>Find and join clubs that match your interests. Explore events, connect with fellow students, and enrich your campus life.</p>
            <a href="#clubs" class="cta-button">Explore Clubs</a>
        </div>
    </header>

    <section id="about" class="section">
        <h2>About CampusClubs</h2>
        <p>CampusClubs is a platform designed to connect students with various clubs and organizations on campus. Our mission is to foster a vibrant campus community by making it easy for students to discover their interests, engage in extracurricular activities, and build lasting connections.</p>
        <p>Whether you're passionate about sports, technology, arts, or community service, you'll find a home here. We provide a centralized hub for clubs to showcase their activities, events, and membership opportunities, empowering students to get involved and make the most of their college experience.</p>
    </section>

    <section id="clubs" class="section">
        <h2>Explore Clubs</h2>
        <div class="club-grid">
            <?php
            include 'dbConnect.php'; // Include your database connection

            $stmt = $conn->prepare("SELECT * FROM clubs"); // Fetch club data
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo '<div class="club-card">';
                echo '<h3>' . $row['club_name'] . '</h3>';
                echo '<p>' . $row['description'] . '</p>'; // Short description
                echo '<a href="club_details.php?id=' . $row['id'] . '" class="club-link">Learn More</a>'; // Link to club details page
                echo '</div>';
            }
            ?>

        </div>
    </section>

    <section id="events" class="section">
        <h2>Upcoming Events</h2>
        <p>Check out the latest events happening on campus.</p>
        </section>

    <section id="contact" class="section">
        <h2>Contact Us</h2>
        <p>Have questions or suggestions? Get in touch!</p>
        </section>

    <footer>
        <p>&copy; 2023 CampusClubs</p>
    </footer>

</body>
</html>