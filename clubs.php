<?php
session_start();
include 'dbConnect.php'; // Include your database connection

// Optional: Add a search/filter feature
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusClubs - Clubs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Uniform Navigation Bar Styles */
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

        /* Minimalistic and user-friendly styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure the body takes at least the full viewport height */
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .section {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            flex: 1; /* Allow the section to grow and push the footer to the bottom */
        }

        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
            max-width: 100%;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }

        .club-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .club-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .club-card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .club-card h2 {
            margin: 0 0 10px;
            font-size: 1.5rem;
            color: #007bff;
        }

        .club-card p {
            margin: 5px 0;
            color: #555;
        }

        .club-card .description {
            margin: 10px 0;
            color: #666;
            line-height: 1.6;
        }

        .club-card .join-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 10px;
        }

        .club-card .join-button:hover {
            background-color: #218838;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
            margin-top: auto; /* Push the footer to the bottom */
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

    <section id="clubs" class="section">
        <h2>Explore Clubs</h2>

        <!-- Search Bar -->
        <div class="search-container">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Search clubs..." value="<?php echo $search_term; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <?php
        // Fetch clubs from the database
        $sql = "SELECT * FROM clubs";
        if (!empty($search_term)) {
            $sql .= " WHERE name LIKE '%$search_term%' OR description LIKE '%$search_term%'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="club-card">';
                if (!empty($row['image'])) {
                    echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '">';
                }
                echo '<h2>' . $row['name'] . '</h2>';
                echo '<p class="description">' . $row['description'] . '</p>';
                echo '<p><b>Category:</b> ' . $row['category'] . '</p>';
                echo '<p><b>Contact:</b> ' . $row['contact_info'] . '</p>';
                echo '<p><b>Meeting Schedule:</b> ' . $row['meeting_schedule'] . '</p>';
                echo '<p><b>Location:</b> ' . $row['location'] . '</p>';
                echo '<a href="join_club.php?club_id=' . $row['id'] . '" class="join-button">Join Club</a>';
                echo '</div>';
            }
        } else {
            echo "<p>No clubs found.</p>";
        }
        ?>
    </section>

    <footer>
        <p>&copy; 2023 CampusClubs</p>
    </footer>
</body>
</html>