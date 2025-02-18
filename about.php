<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About CampusClubs</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 960px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        h2 {
            color: #007bff;
            margin-bottom: 10px;
        }

        p {
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .image-wrapper {
            width: 45%;
            margin-bottom: 20px;
        }

        img {
            width: 100%;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .copyright {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">

        <h1>About CampusClubs</h1>

        <p>
            At CampusClubs, we're passionate about the incredible power of clubs within university life.  We believe that clubs are more than just gatherings of people with shared interests – they're vibrant communities where students discover their passions, develop essential skills, and build lifelong connections.
        </p>

        <h2>The Importance of Clubs</h2>

        <div class="image-container">
            <div class="image-wrapper">
                <img src="images/club1.jpg" alt="Club Activity">
                <p>
                    <strong>Skill Development:</strong> Clubs provide a platform to hone practical skills, from leadership and teamwork to communication and problem-solving.  These skills are invaluable both in your academic journey and future career.
                </p>
            </div>
            <div class="image-wrapper">
                <img src="images/club2.jpg" alt="Networking Event">
                <p>
                    <strong>Networking Opportunities:</strong>  Connect with fellow students who share your interests, expand your network, and build relationships that can last a lifetime. Clubs often connect with professionals in the field, opening doors to mentorship and career opportunities.
                </p>
            </div>
            <div class="image-wrapper">
                <img src="images/club3.jpg" alt="Community Building">
                <p>
                    <strong>Community and Belonging:</strong>  Find your tribe! Clubs offer a sense of community and belonging, creating a supportive environment where you can connect with like-minded individuals and make lasting friendships.
                </p>
            </div>
            <div class="image-wrapper">
                <img src="images/club4.jpg" alt="Personal Growth">
                <p>
                    <strong>Personal Growth:</strong>  Explore new interests, challenge yourself, and discover hidden talents. Clubs provide opportunities for personal growth and self-discovery, enriching your university experience.
                </p>
            </div>
        </div>


        <p>
            CampusClubs is here to make it easier than ever for students to find and engage with the clubs that matter to them.  We provide a centralized platform where you can discover clubs, connect with members, and stay up-to-date on club activities.
        </p>

        <p>
            Join us in celebrating the vibrant club culture within universities and empowering students to make the most of their university experience!
        </p>

        <p class="copyright">&copy; <?php echo date("Y"); ?> CampusClubs</p>

    </div>

</body>
</html>