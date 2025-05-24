<?php
require 'public_header.php';
require 'image_slider.php';
?>

<main class="contact-main">
    <section class="contact-section">
        <h1>Contact Us</h1>
        <p>If you have any questions, please do not hesitate to send us a message. We reply within 24 hours!</p>
        <?php
        include ("db.php");
        if (isset($_POST['send_inquire'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = addslashes($_POST['message']);
            if (!empty($name) && !empty($email) && !empty($message)) {
                $insert_inquire = $pdo->prepare("insert into inquiries(full_name,email,message) values('$name','$email','$message')");
                $insert_inquire->execute();
                if ($insert_inquire) {
                    echo "<div class='success-message'>Inquiry Sent.....check your email for response</div>";
                }
            }
        }
        ?>
        <div class="contact-form-container">
            <form class="contact-form" method="POST">
                <input type="text" id="name" name="name" placeholder="Your Name" required>
                <input type="email" id="email" name="email" placeholder="Your Email" required>
                <textarea id="message" name="message" placeholder="Your Message" required></textarea>
                <button type="submit" name="send_inquire">Send Message</button>
            </form>

            <div class="contact-info">
                <h3>Our Address</h3>
                <p>123 Learning Rd, Education City, 12345</p>
                <h3>Phone</h3>
                <p>+1 234 567 8900</p>
                <h3>Email</h3>
                <p>contact@littlelearners.edu</p>
            </div>
        </div>

        <!-- include a map iframe 
<div class="map-container">
Replace with your location's iframe from Google Maps 
<iframe src="your-google-map-iframe-link"></iframe>
</div> -->
    </section>

    <?php
    require 'footer.php';
    ?>