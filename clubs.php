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

    public function getClubs($search_term = '') {
        $sql = "SELECT * FROM clubs";
        if (!empty($search_term)) {
            $sql .= " WHERE name LIKE ? OR description LIKE ?";
        }
        $stmt = $this->conn->prepare($sql);
        if (!empty($search_term)) {
            $search_term = "%$search_term%";
            $stmt->bind_param("ss", $search_term, $search_term);
        }
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
    <title>CampusClubs - Clubs</title>
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

        /* Main Section */
        .section {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
            flex: 1;
        }

        /* Search Bar */
        .search-container {
            margin-bottom: 40px;
            text-align: center;
        }
        .search-container input[type="text"] {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 400px;
            max-width: 100%;
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
        }
        .search-container button {
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }

        /* Club Cards */
        .club-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .club-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .club-card img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .club-card h2 {
            font-family: 'Copperplate', serif;
            font-size: 2rem;
            margin: 0 0 10px;
            color: #007bff;
        }
        .club-card p {
            margin: 5px 0;
            color: #555;
            font-size: 1rem;
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
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .club-card .join-button:hover {
            background-color: #218838;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: auto;
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

    <section id="clubs" class="section">
        <h2>Explore Clubs</h2>

        <!-- Display Session Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo (strpos($_SESSION['message'], 'successfully') !== false ? 'success' : 'danger'); ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); // Clear the message after displaying ?>
        <?php endif; ?>

        <!-- Search Bar -->
        <div class="search-container">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Search clubs..." value="<?php echo $search_term; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="row">
            <?php
            if ($clubs->num_rows > 0) {
                while ($row = $clubs->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="club-card" onclick="window.location.href=\'clubdetails.php?club_id=' . $row['id'] . '\'">';
                    if (!empty($row['image'])) {
                        echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '" class="img-fluid">';
                    }
                    echo '<h2>' . $row['name'] . '</h2>';
                    echo '<p class="description">' . $row['description'] . '</p>';
                    echo '<p><b>Category:</b> ' . $row['category'] . '</p>';
                    echo '<p><b>Contact:</b> ' . $row['contact_info'] . '</p>';
                    echo '<p><b>Meeting Schedule:</b> ' . $row['meeting_schedule'] . '</p>';
                    echo '<p><b>Location:</b> ' . $row['location'] . '</p>';
                    echo '<a href="joinclubs.php?club_id=' . $row['id'] . '" class="join-button">Join Club</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-12"><p>No clubs found.</p></div>';
            }
            ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2023 CampusClubs</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>