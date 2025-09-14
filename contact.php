<?php
require_once 'config/db.php';
$pageTitle = "Contact Us";
include 'includes/header.php';

// Handle form submission
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_msg = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid email address.";
    } else {
        // Process the form (in a real application, you would send an email or save to database)
        $success_msg = "Thank you for your message! We'll get back to you soon.";
        
        // For demonstration purposes, we'll just clear the form
        $_POST = array();
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Contact Us</h1>
    
    <div class="row">
        <div class="col-lg-8">
            <?php if ($success_msg): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
            <?php endif; ?>
            
            <?php if ($error_msg): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label">Your Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>Contact Information</h5>
                </div>
                <div class="card-body">
                    <p><i class="fas fa-map-marker-alt me-2"></i> 123 University Ave, City, State 12345</p>
                    <p><i class="fas fa-phone me-2"></i> (123) 456-7890</p>
                    <p><i class="fas fa-envelope me-2"></i> blog@university.edu</p>
                    <p><i class="fas fa-clock me-2"></i> Mon-Fri: 9AM-5PM</p>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Follow Us</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3">
                        <a href="#" class="text-dark"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-dark"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="text-dark"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-dark"><i class="fab fa-linkedin fa-2x"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>