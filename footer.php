<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/footer.css"> <!-- External CSS for footer styles -->
    <style>
        /* Footer Styles */
        footer {
            background-color: #333;
            color: white;
            padding: 40px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: none; /* Initially hide the footer */
            opacity: 0;
            transition: opacity 0.5s ease; /* Smooth transition for the appearance */
        }

        .footer-container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .footer-column {
            flex: 1;
            margin: 10px;
            min-width: 250px;
        }

        .footer-column h3 {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #fff;
        }

        .footer-column p, .footer-column a {
            color: #bbb;
            text-decoration: none;
            font-size: 0.9em;
            line-height: 1.6;
        }

        .footer-column a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .footer-social {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-social a {
            color: white;
            font-size: 1.5em;
            transition: color 0.3s;
        }

        .footer-social a:hover {
            color: #0057ff;
        }

        .footer-bottom {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #444;
            margin-top: 20px;
        }

        .footer-bottom p {
            color: #bbb;
            font-size: 0.8em;
        }

        .footer-bottom a {
            color: #fff;
            text-decoration: none;
            margin: 0 5px;
        }

        .footer-bottom a:hover {
            text-decoration: underline;
        }

        .map {
            width: 100%;
            height: 200px;
            background-color: #eee;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        .map iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                align-items: center;
            }

            .footer-column {
                margin: 20px 0;
            }
        }
    </style>
</head>
<body>

<footer id="footer">
    <div class="footer-container">
        <!-- Company Info Column -->
        <div class="footer-column">
            <h3>About Tech Spot</h3>
            <p>Tech Spot is your go-to destination for top-notch tech products, expert services, and comprehensive training courses to help you navigate the tech world with ease. Join our community of tech enthusiasts and stay ahead in the digital age!</p>
        </div>

        <!-- Contact Info Column -->
        <div class="footer-column">
            <h3>Contact Us</h3>
            <p>Address: 123 Tech Avenue, Suite 456, Tech City, TX 78910</p>
            <p>Phone: +1 (123) 456-7890</p>
            <p>Email: info@techspot.com</p>
        </div>

        <!-- Social Media Column -->
        <div class="footer-column">
            <h3>Follow Us</h3>
            <div class="footer-social">
                <a href="https://facebook.com" target="_blank"><i class="fa fa-facebook"></i></a>
                <a href="https://twitter.com" target="_blank"><i class="fa fa-twitter"></i></a>
                <a href="https://instagram.com" target="_blank"><i class="fa fa-instagram"></i></a>
                <a href="https://linkedin.com" target="_blank"><i class="fa fa-linkedin"></i></a>
            </div>
        </div>

        <!-- Useful Links Column -->
        <div class="footer-column">
            <h3>Quick Links</h3>
            <p><a href="index.php">Home</a></p>
            <p><a href="products.php">Products</a></p>
            <p><a href="services.php">Services</a></p>
            <p><a href="training.php">Training</a></p>
            <p><a href="about.php">About Us</a></p>
            <p><a href="contact_us.php">Contact Us</a></p>
        </div>

        <!-- Google Map Column -->
        <div class="footer-column">
            <h3>Our Location</h3>
            <div class="map">
                <!-- Embed Google Map iframe -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1000000!2d-95.712891!3d37.09024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDA1JzI1LjMiTiA5NcKwNDInMTMuMCJX!5e0!3m2!1sen!2sus!4v1600000000000!5m2!1sen!2sus"></iframe>
            </div>
        </div>
    </div>

    <!-- Footer Bottom Section -->
    <div class="footer-bottom">
        <p>&copy; <?= date('Y'); ?> Tech Spot. All rights reserved. | Designed by Tech Spot Team</p>
        <p><a href="privacy_policy.php">Privacy Policy</a> | <a href="terms_of_service.php">Terms of Service</a></p>
    </div>
</footer>

<!-- Include FontAwesome for social media icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/DT5z6YXosK1WzLvKo+NP9zYI1yNMC0aylY7MP7p" crossorigin="anonymous">

<!-- JavaScript to Show Footer on Scroll -->
<script>
    window.addEventListener('scroll', function() {
        var footer = document.getElementById('footer');
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
            footer.style.display = 'block';
            footer.style.opacity = '1';
        }
    });
</script>

</body>
</html>
