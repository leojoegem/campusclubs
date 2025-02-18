<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusClubs - Events</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* events.php specific styles */
        .events-content {
            max-width: 960px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .event-card {
            display: flex; /* Use flexbox for layout */
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1); /* Subtle shadow */
            background-color: white;
            transition: transform 0.2s; /* Add a transition for the transform property */
        }

        .event-card:hover {
            transform: translateY(-5px); /* Move the card up slightly on hover */
            box-shadow: 4px 4px 8px rgba(0,0,0,0.15); /* Increase shadow on hover */
        }

        .event-image {
            width: 250px; /* Adjust image width as needed */
            margin-right: 20px;
            border-radius: 8px;
            overflow: hidden; /* Hide overflowing image content */
        }

        .event-image img {
            width: 100%;
            height: auto;
            display: block; /* Prevent image from creating extra space */
        }

        .event-details {
            flex-grow: 1; /* Allow details to take up remaining space */
        }

        .event-title {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .event-date {
            color: #777;
            margin-bottom: 5px;
        }

        .event-description {
            line-height: 1.6;
        }

        /* Responsive adjustments (example) */
        @media (max-width: 768px) {
            .event-card {
                flex-direction: column; /* Stack image and details vertically */
            }

            .event-image {
                width: 100%;
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

    <header>
        <nav>
            <div class="logo">CampusClubs</div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="clubs.php">Clubs</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="contact.php">Contact</a></li>
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

    <div class="events-content">
        <h1>Upcoming and Past Events</h1>

        <?php if (empty($events)): ?>
            <p>No events found.</p>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <div class="event-image">
                        <img src="images/event_placeholder.jpg" alt="<?php echo $event['event_name']; ?>">  </div>
                    <div class="event-details">
                        <h2 class="event-title"><?php echo $event['event_name']; ?></h2>
                        <p class="event-date"><?php echo date("F j, Y", strtotime($event['event_date'])); ?></p>  <p class="event-description"><?php echo $event['description']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <footer>
        <p>&copy; 2023 CampusClubs</p>
    </footer>

</body>
</html>