<?php
session_start();
include 'dbConnect.php'; // Include your database connection

// Club class using OOP
class Club {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getClubById($club_id) {
        $sql = "SELECT * FROM clubs WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $club_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getMemberCount($club_id) {
        $sql = "SELECT COUNT(*) as member_count FROM memberships WHERE club_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $club_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['member_count'];
    }
}

$club_id = isset($_GET['club_id']) ? $_GET['club_id'] : null;
$club = new Club($conn);
$club_details = $club->getClubById($club_id);
$club_details = $club->getClubById($club_id);

if (!$club_details) {
    header("Location: clubs.php");
$club_id = $_GET['club_id'] ?? null;

    $member_count = $club->getMemberCount($club_id);
}
?>
    <title>CampusClubs - Club Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Copperplate&family=Copperplate+Gothic+Light&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Copperplate Gothic Light', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        /* Main Section */
        .section {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
            flex: 1;
        }

        /* Club Details */
        

.club-details {
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.club-details img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin-bottom: 20px;
}

.club-details h2 {
    font-family: 'Copperplate', serif;
    font-size: 2.5rem;
    margin: 0 0 10px;
    color: #007bff;
}

.club-details p {
    margin: 5px 0;
    color: #555;
    font-size: 1rem;
}

.club-details .description {
    margin: 10px 0;
    color: #666;
    line-height: 1.6;
}

.club-details .join-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    margin-top: 10px;
    font-family: 'Copperplate Gothic Light', sans-serif;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.club-details .join-button:hover {
    background-color: #218838;
}

/* Additional Styling for Better Space Utilization */
.section {
    padding: 40px 20px;
    max-width: 1200px;
    margin: 0 auto;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 20px;
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

    <section id="club-details" class="section">
        <div class="club-details">
            <?php if (!empty($club_details['image'])): ?>
                <img src="<?php echo $club_details['image']; ?>" alt="<?php echo $club_details['name']; ?>">
            <?php endif; ?>
            <h2><?php echo $club_details['name']; ?></h2>
            <p class="description"><?php echo $club_details['description']; ?></p>
            <p><b>Category:</b> <?php echo $club_details['category']; ?></p>
            <p><b>Contact:</b> <?php echo $club_details['contact_info']; ?></p>
            <p><b>Meeting Schedule:</b> <?php echo $club_details['meeting_schedule']; ?></p>
            <p><b>Location:</b> <?php echo $club_details['location']; ?></p>
            <p><b>Members:</b> <?php echo $member_count; ?></p>
            <a href="joinclub.php?club_id=<?php echo $club_details['id']; ?>" class="join-button">Join Club</a>
        </div>
    </section>

    <footer>
        <p>&copy; 2023 CampusClubs</p>
    </footer>
</body>
</html>