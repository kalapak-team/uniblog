<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'posts.php') ? 'active' : ''; ?>" href="posts.php">
                            <i class="fas fa-file-alt me-2"></i>
                            Posts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'categories.php') ? 'active' : ''; ?>" href="categories.php">
                            <i class="fas fa-folder me-2"></i>
                            Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'comments.php') ? 'active' : ''; ?>" href="comments.php">
                            <i class="fas fa-comments me-2"></i>
                            Comments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : ''; ?>" href="users.php">
                            <i class="fas fa-users me-2"></i>
                            Users
                        </a>
                    </li>
                </ul>
            </div>
        </nav>