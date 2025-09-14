<?php
require_once 'config/db.php';
$pageTitle = "Blog";
include 'includes/header.php';

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 6;
$start = ($page > 1) ? ($page * $per_page) - $per_page : 0;

// Get total posts for pagination
$total = $pdo->query("SELECT COUNT(*) as total FROM posts WHERE status = 'published'")->fetch()['total'];
$pages = ceil($total / $per_page);

// Fetch posts with pagination
$stmt = $pdo->prepare("SELECT p.*, u.first_name, u.last_name, c.name as category_name 
                       FROM posts p 
                       LEFT JOIN users u ON p.author_id = u.id 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.status = 'published' 
                       ORDER BY p.created_at DESC 
                       LIMIT {$start}, {$per_page}");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h1 class="mb-4">University Blog</h1>
    
    <div class="row">
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <?php if ($post['featured_image']): ?>
                    <img src="uploads/<?php echo $post['featured_image']; ?>" class="card-img-top" alt="<?php echo $post['title']; ?>">
                    <?php else: ?>
                    <img src="assets/images/placeholder.jpg" class="card-img-top" alt="Post image">
                    <?php endif; ?>
                    <div class="card-body">
                        <span class="badge bg-primary mb-2"><?php echo $post['category_name']; ?></span>
                        <h5 class="card-title"><?php echo $post['title']; ?></h5>
                        <p class="card-text"><?php echo $post['excerpt']; ?></p>
                        <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Read More</a>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Posted by <?php echo $post['first_name'] . ' ' . $post['last_name']; ?> on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">No blog posts found.</div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>