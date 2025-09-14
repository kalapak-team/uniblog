<?php
require_once 'config/db.php';
$pageTitle = "Home";
include 'includes/header.php';

// Fetch featured posts
$stmt = $pdo->query("SELECT p.*, u.first_name, u.last_name, c.name as category_name 
                     FROM posts p 
                     LEFT JOIN users u ON p.author_id = u.id 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     WHERE p.status = 'published' 
                     ORDER BY p.created_at DESC 
                     LIMIT 6");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Welcome to University Blog</h1>
                <p class="lead">Discover the latest news, research, and events from our campus community.</p>
                <a href="blog.php" class="btn btn-light btn-lg mt-3">Explore Blog</a>
            </div>
            <div class="col-lg-6">
                <img src="assets/images/campus-hero.png" alt="University Campus" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Featured Posts -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Latest Articles</h2>
        <div class="row">
            <?php foreach ($posts as $post): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if ($post['featured_image']): ?>
                    <img src="uploads/<?php echo $post['featured_image']; ?>" class="card-img-top" alt="<?php echo $post['title']; ?>">
                    <?php else: ?>
                    <img src="assets/images/placeholder.jpg" class="card-img-top" alt="Post image">
                    <?php endif; ?>
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2"><?php echo $post['category_name']; ?></span>
                        <h5 class="card-title"><?php echo $post['title']; ?></h5>
                        <p class="card-text"><?php echo substr($post['excerpt'], 0, 100) . '...'; ?></p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">By <?php echo $post['first_name'] . ' ' . $post['last_name']; ?></small>
                            <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="blog.php" class="btn btn-primary">View All Posts</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>