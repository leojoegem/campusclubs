<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusClubs</title>
    <link rel="stylesheet" href="styles.css">
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