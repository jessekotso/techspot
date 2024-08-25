<?php
include 'php/db.php'; // Include the database connection

session_start();

// Fetch all services from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM services");
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching services: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services | Tech Spot</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your main stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Page Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            width: 90%;
            max-width: 1200px;
            margin: auto;
            padding: 40px 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5em;
            color: #333;
            margin-bottom: 40px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            align-items: stretch;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .card:hover img {
            transform: scale(1.05);
        }

        .card-content {
            padding: 20px;
        }

        .card-title {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #0057ff;
        }

        .card-text {
            font-size: 1em;
            color: #777;
            margin-bottom: 15px;
        }

        .button {
            background-color: #0057ff;
            color: white;
            padding: 12px 24px;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #003bb5;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        .search-bar {
            text-align: center;
            margin-bottom: 40px;
        }

        .search-input {
            width: 80%;
            max-width: 600px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            background-color: #0057ff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-button:hover {
            background-color: #003bb5;
        }

        /* Footer Styles */
        footer {
            background-color: #0057ff;
            color: white;
            padding: 20px 0;
            text-align: center;
            width: 100%;
        }

        .social-icons a {
            color: white;
            margin: 0 10px;
            font-size: 1.2em;
        }
    </style>
</head>

<body>

<!-- Include Header -->
<?php include 'templates/header.php'; ?>

<div class="container">
    <h1 class="section-title">Our Services</h1>

    <!-- Search Bar -->
    <div class="search-bar">
        <input type="text" class="search-input" placeholder="Search services..." id="search-input">
        <button class="search-button" onclick="searchServices()">Search</button>
    </div>

    <!-- Services Grid -->
    <div class="grid-container">
        <?php foreach ($services as $service): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>">
                <div class="card-content">
                    <h3 class="card-title"><?= htmlspecialchars($service['name']) ?></h3>
                    <p class="card-text"><?= htmlspecialchars($service['description']) ?></p>
                    <a href="request_service.php?id=<?= htmlspecialchars($service['id']) ?>" class="button">Request Service</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function searchServices() {
        let input = document.getElementById('search-input').value.toLowerCase();
        let serviceCards = document.getElementsByClassName('card');
        for (let i = 0; i < serviceCards.length; i++) {
            let title = serviceCards[i].querySelector('.card-title').textContent.toLowerCase();
            if (title.includes(input)) {
                serviceCards[i].style.display = '';
            } else {
                serviceCards[i].style.display = 'none';
            }
        }
    }
</script>

</body>
</html>
