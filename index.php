<?php
session_start();
include 'php/db.php'; // Include the database connection
include 'templates/header.php'; // Include the header

// Fetch random items for the carousel
try {
    // Fetch random featured products
    $stmt = $pdo->prepare("SELECT * FROM products WHERE featured = 1 ORDER BY RAND() LIMIT 5");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch random featured services
    $stmt = $pdo->prepare("SELECT * FROM services WHERE featured = 1 ORDER BY RAND() LIMIT 5");
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch random featured courses
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE featured = 1 ORDER BY RAND() LIMIT 5");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Combine all featured items into one array for the top carousel
    $featuredItems = array_merge($products, $services, $courses);
} catch (PDOException $e) {
    echo "Error fetching data: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Spot - Home</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your main stylesheet -->
    <style>
        /* Main Page Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eaeaea; /* Light background for the main page */
            color: #333; /* Dark text color for contrast */
            margin: 0;
            padding: 0;
            padding-top: 80px; /* Adjust padding-top to account for the header height */
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }

        /* Slideshow Styles */
        .carousel-container {
            perspective: 1000px;
            max-width: 100%;
            margin: auto;
            overflow: hidden;
            border-radius: 10px;
            position: relative;
            text-align: center;
            margin-bottom: 50px;
            background-color: #ffffff; /* Light background for sections */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        .carousel {
            display: flex;
            transition: transform 0.5s ease;
            transform-style: preserve-3d;
        }

        .carousel-item {
            min-width: 300px;
            margin: 0 10px;
            background-color: #f0f0f0; /* Slightly darker background for carousel items */
            border-radius: 10px;
            transform: scale(0.8);
            opacity: 0.7;
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
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 50%;
        }

        .carousel-controls button:hover {
            background-color: rgba(0, 0, 0, 0.9);
        }

        /* Section Styles */
        .section {
            margin-bottom: 50px;
            background-color: #ffffff; /* Light background for sections */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            text-align: center;
            font-size: 2em;
            color: #222; /* Darker text for section titles */
            margin-bottom: 20px;
        }

        .button {
            background-color: #333; /* Dark button background for contrast */
            color: white;
            padding: 10px 20px;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #000; /* Darker shade for button hover */
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .carousel-item {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- 3D Carousel of Random Featured Items -->
    <div class="section">
        <h2 class="section-title">Discover Our Top Picks</h2>
        <?php include 'sections/carousel_random_items.php'; ?>
    </div>

    <!-- 3D Carousel of Products -->
    <div class="section">
        <h2 class="section-title">Featured Products</h2>
        <?php include 'sections/carousel_products.php'; ?>
    </div>

    <!-- 3D Carousel of Services -->
    <div class="section">
        <h2 class="section-title">Our Premium Services</h2>
        <?php include 'sections/carousel_services.php'; ?>
    </div>

    <!-- 3D Carousel of Courses -->
    <div class="section">
        <h2 class="section-title">Top Training Courses</h2>
        <?php include 'sections/carousel_courses.php'; ?>
    </div>

    <!-- Additional Content Sections -->
    <div class="section">
        <h2 class="section-title">Welcome to Tech Spot</h2>
        <p>At Tech Spot, we provide top-notch tech products, expert services, and comprehensive training courses to help you navigate the tech world with ease. Explore our offerings and join our community of tech enthusiasts!</p>
    </div>

    <!-- Include more sections as needed -->

</div>

<!-- Include Footer -->
<?php include 'templates/footer.php'; ?>

<script>
    // JavaScript for Carousel Controls
    const carousels = document.querySelectorAll('.carousel');
    carousels.forEach((carousel) => {
        const items = carousel.querySelectorAll('.carousel-item');
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

        carousel.parentNode.querySelector('#prev').addEventListener('click', function() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : items.length - 1;
            updateCarousel();
        });

        carousel.parentNode.querySelector('#next').addEventListener('click', function() {
            currentIndex = (currentIndex < items.length - 1) ? currentIndex + 1 : 0;
            updateCarousel();
        });

        // Initialize the carousel
        updateCarousel();
    });
</script>

</body>
</html>
