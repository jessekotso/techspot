<?php
session_start();
include 'php/db.php'; // Include the database connection

// Fetch random featured products from the database
try {
    // Fetch random featured products
    $stmt = $pdo->prepare("SELECT * FROM products WHERE featured = 1 ORDER BY RAND() LIMIT 5");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching products: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products | Tech Spot</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your main stylesheet -->
    <style>
        /* Page Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #aeaeae; /* Dark background for the main page */
            color: #e0e0e0; /* Light text color for contrast */
            margin: 0;
            padding: 0;
            padding-top: 80px; /* Adjust padding-top to account for the header height */
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
            background-color: #aeaeae; /* Darker section background */
            border-radius: 10px; /* Add rounded corners to the container */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add subtle shadow for depth */
        }

        .section-title {
            text-align: center;
            font-size: 2.5em;
            color: #ffffff; /* White text for section titles */
            margin-bottom: 40px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            align-items: stretch;
        }

        .card {
            background-color: #ffffff; /* Lighter card background */
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
            color: #1abc9c; /* Accent color for titles */
        }

        .card-text {
            font-size: 1em;
            color: #e0e0e0; /* Light text color for description */
            margin-bottom: 15px;
        }

        .card-price {
            font-size: 1.2em;
            color: #ffffff; /* White text for pricing */
            font-weight: bold;
            margin-bottom: 20px;
        }

        .button {
            background-color: #1abc9c;
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
            background-color: #16a085;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        /* 3D Carousel Styles */
        .carousel-container {
            perspective: 1000px;
            max-width: 100%;
            margin: auto;
            overflow: hidden;
            border-radius: 10px;
            position: relative;
            text-align: center;
            margin-bottom: 50px;
            background-color: #aeaeae; /* Darker section background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add subtle shadow for depth */
        }

        .carousel {
            display: flex;
            transition: transform 0.5s ease;
            transform-style: preserve-3d;
        }

        .carousel-item {
            min-width: 300px;
            margin: 0 10px;
            background-color: #4d4d4d; /* Lighter card background for carousel items */
            border-radius: 10px;
            transform: scale(0.8);
            opacity: 0.5;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        .carousel-item.active {
            transform: scale(1);
            opacity: 1;
        }

        .carousel-item img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .carousel-controls {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .carousel-controls button {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 50%;
        }

        .carousel-controls button:hover {
            background-color: rgba(0, 0, 0, 0.8);
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
    <h1 class="section-title">Featured Products</h1>

    <!-- 3D Carousel of Featured Products -->
    <div class="carousel-container">
        <div class="carousel">
            <?php foreach ($products as $index => $product): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="item-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                        <p class="card-price">$<?= htmlspecialchars(number_format($product['price'], 2)) ?></p>
                        <a href="product_details.php?id=<?= htmlspecialchars($product['id']) ?>" class="button">Buy Now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="carousel-controls">
            <button id="prev">&lt;</button>
            <button id="next">&gt;</button>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid-container">
        <?php foreach ($products as $product): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="card-content">
                    <h3 class="card-title"><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                    <p class="card-price">$<?= htmlspecialchars(number_format($product['price'], 2)) ?></p>
                    <a href="product_details.php?id=<?= htmlspecialchars($product['id']) ?>" class="button">Buy Now</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // JavaScript for Carousel Controls
    const carousel = document.querySelector('.carousel');
    const items = document.querySelectorAll('.carousel-item');
    let currentIndex = 0;

    function updateCarousel() {
        items.forEach((item, index) => {
            item.classList.remove('active');
            if (index === currentIndex) {
                item.classList.add('active');
            }
        });
        carousel.style.transform = `translateX(-${currentIndex * (items[0].clientWidth + 20)}px)`;
    }

    document.getElementById('prev').addEventListener('click', function() {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : items.length - 1;
        updateCarousel();
    });

    document.getElementById('next').addEventListener('click', function() {
        currentIndex = (currentIndex < items.length - 1) ? currentIndex + 1 : 0;
        updateCarousel();
    });

    // Initialize the carousel
    updateCarousel();
</script>

</body>
</html>
