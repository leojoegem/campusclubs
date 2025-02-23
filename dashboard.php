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
    $image = '';

    // Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image file
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false && $_FILES["image"]["size"] <= 500000 && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;
            }
        }
    }

    if ($club_id > 0) {
        // Update existing club
        if ($image) {
            $stmt = $conn->prepare("UPDATE clubs SET club_name=?, description=?, category=?, contact_info=?, image=? WHERE id=?");
            $stmt->bind_param("sssssi", $club_name, $description, $category, $contact_info, $image, $club_id);
        } else {
            $stmt = $conn->prepare("UPDATE clubs SET club_name=?, description=?, category=?, contact_info=? WHERE id=?");
            $stmt->bind_param("ssssi", $club_name, $description, $category, $contact_info, $club_id);
        }
    } else {
        // Insert new club
        $stmt = $conn->prepare("INSERT INTO clubs (club_name, description, category, contact_info, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $club_name, $description, $category, $contact_info, $image);
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">CampusClubs Admin</div>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container">
        <h1>Admin Dashboard</h1>

        <!-- Button to open modal -->
        <button onclick="openModal()">+ Add New Club</button>

        <h2>Existing Clubs</h2>
        <table class="club-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Club Name</th>
                    <th>Category</th>
                    <th>Contact Info</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM clubs");
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['club_name']); ?></td>
                        <td><?= htmlspecialchars($row['category']); ?></td>
                        <td><?= htmlspecialchars($row['contact_info']); ?></td>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="<?= $row['image']; ?>" style="max-width: 50px; height: auto;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <button onclick="editClub(<?= $row['id']; ?>, '<?= htmlspecialchars($row['club_name']); ?>', '<?= htmlspecialchars($row['description']); ?>', '<?= htmlspecialchars($row['category']); ?>', '<?= htmlspecialchars($row['contact_info']); ?>')">Edit</button>
                            <a href="dashboard.php?delete_club=<?= $row['id']; ?>" class="delete-link" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal for Adding/Editing Clubs -->
    <div id="clubModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Add Club</h2>
            <form id="clubForm" method="post" enctype="multipart/form-data">
                <input type="hidden" name="club_id" id="club_id">
                <label for="club_name">Club Name:</label>
                <input type="text" name="club_name" id="club_name" required>
                
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>

                <label for="category">Category:</label>
                <input type="text" name="category" id="category" required>

                <label for="contact_info">Contact Info:</label>
                <input type="text" name="contact_info" id="contact_info" required>

                <label for="image">Image:</label>
                <input type="file" name="image" id="image">

                <button type="submit">Save Club</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById("clubModal").style.display = "block";
        }
        
        function closeModal() {
            document.getElementById("clubModal").style.display = "none";
        }

        function editClub(id, name, description, category, contact_info) {
            document.getElementById("modalTitle").textContent = "Edit Club";
            document.getElementById("club_id").value = id;
            document.getElementById("club_name").value = name;
            document.getElementById("description").value = description;
            document.getElementById("category").value = category;
            document.getElementById("contact_info").value = contact_info;
            openModal();
        }
    </script>
</body>
</html>
