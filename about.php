<?php
include 'php/db.php'; // Include the database connection
include 'templates/header.php';

?>
<body>
    <div class="product-section">
        <h1>About Tech Spot</h1>
        
        <section id="company-overview">
            <h2>Company Overview</h2>
            <p>
                Tech Spot is a leading technology company dedicated to empowering individuals and businesses through innovative solutions and cutting-edge training. Our services range from selling top-quality tech products to providing comprehensive training courses in various fields like web development, graphic design, and tech consultation. Our mission is to bridge the gap between people and technology, making advanced tools and skills accessible to everyone.
            </p>
            <p>
                Established in [Year], Tech Spot has grown from a small startup to a reputable name in the tech industry. We pride ourselves on our commitment to quality, customer satisfaction, and continuous innovation. Whether you're looking to enhance your skills, purchase the latest gadgets, or find tech solutions for your business, Tech Spot is your trusted partner.
            </p>
        </section>

        <section id="mission-vision">
            <h2>Our Mission and Vision</h2>
            <h3>Mission</h3>
            <p>
                Our mission is to empower our clients by providing them with the tools, knowledge, and resources they need to succeed in an increasingly digital world. We are dedicated to fostering a culture of continuous learning, innovation, and excellence.
            </p>
            <h3>Vision</h3>
            <p>
                Our vision is to be the leading technology provider and educator in our region, recognized for our commitment to quality, innovation, and customer service. We aim to create a tech-savvy community where everyone has the opportunity to learn, grow, and succeed.
            </p>
        </section>

        <section id="team">
            <h2>Meet Our Team</h2>
            <div class="team-list">
                <?php
                // PHP Code to Fetch and Display Team Members
                try {
                    $teamMembers = $pdo->query("SELECT * FROM team_members")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($teamMembers as $member) {
                        echo '<div class="team-member">';
                        echo '<img src="' . htmlspecialchars($member['photo_url']) . '" alt="' . htmlspecialchars($member['name']) . '">';
                        echo '<h3>' . htmlspecialchars($member['name']) . '</h3>';
                        echo '<p>' . htmlspecialchars($member['position']) . '</p>';
                        echo '<p>' . htmlspecialchars($member['bio']) . '</p>';
                        echo '</div>';
                    }
                } catch (PDOException $e) {
                    echo "Error fetching team members: " . $e->getMessage();
                }
                ?>
            </div>
        </section>

        <section id="testimonials">
            <h2>What Our Clients Say</h2>
            <div class="testimonials-list">
                <?php
                // PHP Code to Fetch and Display Testimonials
                try {
                    $testimonials = $pdo->query("SELECT * FROM testimonials")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($testimonials as $testimonial) {
                        echo '<div class="testimonial">';
                        echo '<p>"' . htmlspecialchars($testimonial['message']) . '"</p>';
                        echo '<h4>- ' . htmlspecialchars($testimonial['client_name']) . '</h4>';
                        echo '</div>';
                    }
                } catch (PDOException $e) {
                    echo "Error fetching testimonials: " . $e->getMessage();
                }
                ?>
            </div>
        </section>
    </div><div class="container">
    <h1>Contact Us</h1>
    
    <section id="contact-form">
        <h2>Get in Touch</h2>
        <form action="send_contact.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
            
            <button type="submit">Send</button>
        </form>
    </section>

    <section id="location-map">
        <h2>Our Location</h2>
        <!-- Embed Google Maps Location -->
    </section>

    <section id="contact-info">
        <h2>Phone and Email</h2>
        <p>Phone: (Your Phone Number)</p>
        <p>Email: (Your Email Address)</p>
    </section>
</div>

</body>
<?php include 'templates/footer.php'; ?>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .featured-products {
        margin-bottom: 40px;
        text-align: center;
    }

    .featured-products h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: #34495e;
    }

    .slideshow-container {
        position: relative;
        max-width: 100%;
        margin: auto;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .slide {
        display: none;
        text-align: center;
    }

    .slide img {
        width: 100%;
        border-radius: 8px;
    }

    .slide-caption {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .slide-caption h3 {
        font-size: 1.8rem;
        margin-bottom: 5px;
    }

    .slide-caption p {
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .slide-caption .btn {
        background-color: #1abc9c;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .slide-caption .btn:hover {
        background-color: #16a085;
    }

    /* Product Section Styling */
    h1 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        text-align: center;
        color: #2c3e50;
    }

    .product-section {
        margin-bottom: 40px;
    }

    h2 {
        font-size: 2rem;
        margin-bottom: 20px;
        color: #34495e;
        text-align: center;
        border-bottom: 2px solid #1abc9c;
        padding-bottom: 10px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .product-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .product-card img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .product-card h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .product-card p {
        font-size: 1.2rem;
        margin-bottom: 15px;
        color: #16a085;
    }

    .product-card .btn {
        background-color: #1abc9c;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .product-card .btn:hover {
        background-color: #16a085;
    }
</style>

