    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><?php echo SITE_NAME; ?></h5>
                    <p>The official blog of University, featuring news, research, and campus life.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>" class="text-light">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/blog.php" class="text-light">Blog</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about.php" class="text-light">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php" class="text-light">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-linkedin fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <p class="text-center mb-0">&copy; <?php echo date('Y'); ?> University. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
</body>
</html>