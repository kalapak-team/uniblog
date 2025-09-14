<?php
require_once 'config/db.php';
$pageTitle = "Categories";
include 'includes/header.php';

// Fetch all categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// If a specific category is selected
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$category_name = "All Categories";
$posts = [];

if ($category_id > 0) {
    $stmt = $pdo->prepare("SELECT c.name FROM categories c WHERE c.id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($category) {
        $category_name = $category['name'];
        
        // Fetch posts for this category
        $stmt = $pdo->prepare("SELECT p.*, u.first_name, u.last_name, c.name as category_name 
                               FROM posts p 
                               LEFT JOIN users u ON p.author_id = u.id 
                               LEFT JOIN categories c ON p.category_id = c.id 
                               WHERE p.category_id = ? AND p.status = 'published' 
                               ORDER BY p.created_at DESC");
        $stmt->execute([$category_id]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // Fetch all posts if no category is selected
    $posts = $pdo->query("SELECT p.*, u.first_name, u.last_name, c.name as category_name 
                          FROM posts p 
                          LEFT JOIN users u ON p.author_id = u.id 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          WHERE p.status = 'published' 
                          ORDER BY p.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container mt-5">
    <div class="row">
        <!-- Categories sidebar -->
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Categories</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="categories.php" class="list-group-item list-group-item-action <?php echo ($category_id == 0) ? 'active' : ''; ?>">
                        All Categories
                    </a>
                    <?php foreach ($categories as $cat): ?>
                    <a href="categories.php?id=<?php echo $cat['id']; ?>" class="list-group-item list-group-item-action <?php echo ($category_id == $cat['id']) ? 'active' : ''; ?>">
                        <?php echo $cat['name']; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Posts content -->
        <div class="col-lg-9">
            <h1 class="mb-4"><?php echo $category_name; ?></h1>
            
            <?php if (count($posts) > 0): ?>
                <div class="row">
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
                </div>
            <?php else: ?>
                <div class="alert alert-info">No posts found in this category.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>