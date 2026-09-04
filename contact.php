<?php
$currentPage = 'contact';
$pageTitle = 'Contact Us';
require_once 'includes/functions.php';
require_once 'includes/database.php';

$db = getDB();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $messageText = sanitize($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($messageText)) {
        $error = 'Please fill in all required fields.';
    } elseif (!validateEmail($email)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $subject, $messageText]);
        $message = 'Thank you for your message! We will get back to you soon.';
        
        // You can add email notification here
    }
}

$settings = getSettings();

require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Get in touch with CLEDAN FC</p>
    </div>
</section>

<section class="py-40">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Info -->
            <div>
                <h2>Get in Touch</h2>
                <p style="color: var(--dark-gray); margin-bottom: 30px;">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                
                <div class="contact-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Address</h4>
                        <p><?php echo $settings['stadium_name'] ?? 'Farasi Lane'; ?><br>
                        <?php echo $settings['stadium_location'] ?? 'Farasi Lane Primary School'; ?></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Phone</h4>
                        <p><?php echo $settings['contact_phone'] ?? '+254 700 000 000'; ?></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p><?php echo $settings['contact_email'] ?? 'info@cledanfc.com'; ?></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Office Hours</h4>
                        <p>Monday - Friday: 9:00 AM - 5:00 PM<br>
                        Saturday: 10:00 AM - 2:00 PM</p>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div>
                <div class="contact-form-wrapper">
                    <h2>Send a Message</h2>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" id="contact-form">
                        <div class="form-group">
                            <label for="name">Your Name *</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo $_POST['name'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo $_POST['phone'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" class="form-control" value="<?php echo $_POST['subject'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" class="form-control" rows="5" required><?php echo $_POST['message'] ?? ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Send Message <i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
}

.contact-info-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    padding: 20px;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
}

.contact-info-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover);
}

.contact-info-item i {
    font-size: 1.5rem;
    color: var(--gold);
    width: 40px;
    text-align: center;
    margin-top: 3px;
}

.contact-info-item h4 {
    font-size: 1rem;
    margin-bottom: 5px;
}

.contact-info-item p {
    color: var(--dark-gray);
    font-size: 0.95rem;
}

.contact-form-wrapper {
    background: var(--white);
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.contact-form-wrapper h2 {
    margin-bottom: 20px;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>