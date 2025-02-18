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
        /* Add any specific styles for clubs.php here */
        .club-details { /* Style for individual club details */
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: white;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .club-details h2 {
            margin-bottom: 10px;
        }

        .club-details img { /* Style for club image (if you have one) */
            max-width: 200px; /* Adjust as needed */
            height: auto;
            float: left; /* Image on the left */
            margin-right: 20px;
        }
        .club-details .description{
            text-align: justify;
        }
        .search-container {
            margin-bottom: 20px;
            text-align: center; /* Center the search bar */
        }

        .search-container input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px; /* Adjust width as needed */
        }

        .search-container button {
            padding: 8px 16px;
            background-color: #007bff; /* Blue */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 5px;
        }

        .search-container button:hover {
            background-color: #0056b3; /* Darker blue */
        }

    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">CampusClubs</div>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="clubs.php">Clubs</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">Contact</a></li>
                <?php
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
    </header>

    <section id="clubs" class="section">
        <h2>Explore Clubs</h2>

        <div class="search-container">
            <form method="get" action="">  <input type="text" name="search" placeholder="Search clubs..." value="<?php echo $search_term; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <?php
        $sql = "SELECT * FROM clubs";
        if (!empty($search_term)) {
            $sql .= " WHERE club_name LIKE '%$search_term%' OR description LIKE '%$search_term%'"; // Add search condition
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="club-details">';
                echo '<h2>' . $row['club_name'] . '</h2>';
                if (!empty($row['image'])) {  // Check if there is an image path
                    echo '<img src="' . $row['image'] . '" alt="' . $row['club_name'] . '">'; // Display image
                }
                echo '<p class="description">' . $row['description'] . '</p>';
                echo '<p><b>Category:</b> ' . $row['category'] . '</p>'; // Example: Show category
                echo '<p><b>Contact:</b> ' . $row['contact_info'] . '</p>'; // Example: Show contact info
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