<?php
require_once 'config/db.php';
$pageTitle = "About Us";
include 'includes/header.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">About University Blog</h1>
    
    <div class="row">
        <div class="col-lg-8">
            <p class="lead">Welcome to the official blog of University, your source for the latest news, research, and events from our campus community.</p>
            
            <p>Our mission is to share the incredible work being done by our students, faculty, and staff, and to keep our community informed about the many opportunities and activities available on campus.</p>
            
            <h3 class="mt-5">Our History</h3>
            <p>Founded in 19XX, University has a long tradition of academic excellence and innovation. Our blog continues this tradition by providing a platform for sharing knowledge and fostering dialogue.</p>
            
            <h3 class="mt-5">What We Cover</h3>
            <ul>
                <li>Research breakthroughs and publications</li>
                <li>Campus events and activities</li>
                <li>Student achievements and stories</li>
                <li>Faculty spotlights and interviews</li>
                <li>Alumni news and updates</li>
            </ul>
            
            <h3 class="mt-5">Join Our Community</h3>
            <p>We welcome submissions from students, faculty, and staff. If you have a story to share, please contact our editorial team.</p>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Facts</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><strong>Founded:</strong> 19XX</li>
                        <li><strong>Students:</strong> 10,000+</li>
                        <li><strong>Faculty:</strong> 500+</li>
                        <li><strong>Programs:</strong> 100+</li>
                        <li><strong>Blog Posts:</strong> 200+</li>
                    </ul>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Contact Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Email:</strong> blog@university.edu</p>
                    <p><strong>Phone:</strong> (123) 456-7890</p>
                    <p><strong>Address:</strong> 123 University Ave, City, State 12345</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>