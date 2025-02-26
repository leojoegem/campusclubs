<?php
session_start();
include 'dbConnect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Handle adding or editing a club
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $club_id = isset($_POST['club_id']) ? intval($_POST['club_id']) : 0;
    $club_name = htmlspecialchars($_POST['club_name']);
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);
    $contact_info = htmlspecialchars($_POST['contact_info']);
    $social_links = htmlspecialchars($_POST['social_links']);
    $meeting_schedule = htmlspecialchars($_POST['meeting_schedule']);
    $location = htmlspecialchars($_POST['location']);
    $created_by = $_SESSION['user_id']; // Set the created_by field to the logged-in user's ID
    $image = '';

    // Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create the uploads directory if it doesn't exist
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image file
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false && $_FILES["image"]["size"] <= 500000 && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file; // Save the file path to the database
            } else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid image file. Only JPG, JPEG, PNG, and GIF files up to 500KB are allowed.');</script>";
        }
    }

    if ($club_id > 0) {
        // Update existing club
        if ($image) {
            $stmt = $conn->prepare("UPDATE clubs SET name=?, description=?, category=?, contact_info=?, social_links=?, meeting_schedule=?, location=?, image=? WHERE id=?");
            $stmt->bind_param("ssssssssi", $club_name, $description, $category, $contact_info, $social_links, $meeting_schedule, $location, $image, $club_id);
        } else {
            $stmt = $conn->prepare("UPDATE clubs SET name=?, description=?, category=?, contact_info=?, social_links=?, meeting_schedule=?, location=? WHERE id=?");
            $stmt->bind_param("sssssssi", $club_name, $description, $category, $contact_info, $social_links, $meeting_schedule, $location, $club_id);
        }
    } else {
        // Insert new club
        $stmt = $conn->prepare("INSERT INTO clubs (name, description, category, contact_info, social_links, meeting_schedule, location, image, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $club_name, $description, $category, $contact_info, $social_links, $meeting_schedule, $location, $image, $created_by);
    }
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
    exit();
}

// Handle deleting a club
if (isset($_GET['delete_club'])) {
    $club_id = intval($_GET['delete_club']);
    $stmt = $conn->prepare("DELETE FROM clubs WHERE id = ?");
    $stmt->bind_param("i", $club_id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #f8f9fa;
            color: #333;
        }
        header {
            background: #007bff;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        nav ul {
            list-style: none;
            display: flex;
        }
        nav ul li {
            margin: 0 10px;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: 500;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        img {
            max-width: 50px;
            height: auto;
            border-radius: 5px;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            text-align: center;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .close {
            float: right;
            cursor: pointer;
            font-size: 1.5rem;
        }
        .chart-container {
            margin: 20px auto;
            width: 80%;
            max-width: 800px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">CampusClubs Admin</div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="#clubs">Clubs</a></li>
                <li><a href="#memberships">Memberships</a></li>
                <li><a href="#charts">Charts</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container">
        <h1>Admin Dashboard</h1>

        <!-- Clubs Section -->
        <section id="clubs">
            <h2>Clubs Management</h2>
            <button onclick="openModal()">+ Add New Club</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Club Name</th>
                        <th>Category</th>
                        <th>Contact</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch existing clubs from the database
                    $query = "SELECT id, name, category, contact_info, image FROM clubs";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['category']}</td>
                                    <td>{$row['contact_info']}</td>
                                    <td><img src='{$row['image']}' alt='Club Image'></td>
                                    <td>
                                        <button onclick='editClub({$row['id']})'>Edit</button>
                                        <button onclick='deleteClub({$row['id']})'>Delete</button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No clubs found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Memberships Section -->
        <section id="memberships">
            <h2>Memberships</h2>
            <button onclick="exportMemberships()">Export as PDF</button>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Club ID</th>
                        <th>Joined At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch memberships from the database
                    $query = "SELECT user_id, club_id, joined_at FROM memberships";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['user_id']}</td>
                                    <td>{$row['club_id']}</td>
                                    <td>{$row['joined_at']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No memberships found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Charts Section -->
        <section id="charts">
            <h2>Membership Chart</h2>
            <div class="chart-container">
                <canvas id="membershipChart"></canvas>
            </div>
        </section>
    </main>

    <!-- Modal for Adding/Editing Clubs -->
    <div id="clubModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Add Club</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="club_id" id="club_id" value="0">
                <input type="text" name="club_name" placeholder="Club Name" required>
                <textarea name="description" placeholder="Description"></textarea>
                <input type="text" name="category" placeholder="Category" required>
                <input type="text" name="contact_info" placeholder="Contact Info">
                <input type="text" name="social_links" placeholder="Social Links">
                <input type="text" name="meeting_schedule" placeholder="Meeting Schedule">
                <input type="text" name="location" placeholder="Location">
                <input type="file" name="image" accept="image/*">
                <button type="submit">Save Club</button>
            </form>
        </div>
    </div>

    <script>
        // Chart.js for Membership Chart
        const ctx = document.getElementById('membershipChart').getContext('2d');
        const membershipChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php
                    // Fetch club names for chart labels
                    $query = "SELECT name FROM clubs";
                    $result = $conn->query($query);
                    $labels = [];
                    while ($row = $result->fetch_assoc()) {
                        $labels[] = $row['name'];
                    }
                    echo json_encode($labels);
                ?>,
                datasets: [{
                    label: 'Number of Members',
                    data: <?php
                        // Fetch member counts for each club
                        $query = "SELECT club_id, COUNT(*) as member_count FROM memberships GROUP BY club_id";
                        $result = $conn->query($query);
                        $data = [];
                        while ($row = $result->fetch_assoc()) {
                            $data[] = $row['member_count'];
                        }
                        echo json_encode($data);
                    ?>,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Export Memberships as PDF
        function exportMemberships() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text("Memberships Report", 10, 10);
            doc.autoTable({
                head: [['User ID', 'Club ID', 'Joined At']],
                body: <?php
                    $query = "SELECT user_id, club_id, joined_at FROM memberships";
                    $result = $conn->query($query);
                    $data = [];
                    while ($row = $result->fetch_assoc()) {
                        array_push($data, [$row['user_id'], $row['club_id'], $row['joined_at']]);
                    }
                    echo json_encode($data);
                ?>
            });
            doc.save('memberships_report.pdf');
        }

        // Modal Functions
        function openModal() {
            document.getElementById("clubModal").style.display = "flex";
        }
        function closeModal() {
            document.getElementById("clubModal").style.display = "none";
        }

        function editClub(clubId) {
            // Fetch club details and populate the modal form for editing
            // You can use AJAX to fetch the club details and populate the form
            alert("Edit club with ID: " + clubId);
        }

        function deleteClub(clubId) {
            if (confirm("Are you sure you want to delete this club?")) {
                window.location.href = "dashboard.php?delete_club=" + clubId;
            }
        }
    </script>
</body>
</html>