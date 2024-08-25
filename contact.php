<?php include 'templates/header.php'; ?>

<div class="container">
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

<?php include 'templates/footer.php'; ?>
