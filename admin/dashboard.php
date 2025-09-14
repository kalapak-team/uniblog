<?php
require_once '../config/db.php';
$pageTitle = "Dashboard";
include 'includes/header.php';
include 'includes/sidebar.php';

// Get statistics
$posts_count = $pdo->query("SELECT COUNT(*) as count FROM posts")->fetch()['count'];
$categories_count = $pdo->query("SELECT COUNT(*) as count FROM categories")->fetch()['count'];
$users_count = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'];
$comments_count = $pdo->query("SELECT COUNT(*) as count FROM comments")->fetch()['count'];

// Get recent posts
$recent_posts = $pdo->query("SELECT p.*, c.name as category_name 
                             FROM posts p 
                             LEFT JOIN categories c ON p.category_id = c.id 
                             ORDER BY p.created_at DESC 
                             LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h1 class="h2">Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="row my-4">
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h5 class="card-title">Posts</h5>
                            <p class="card-text h3"><?php echo $posts_count; ?></p>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-file-alt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h5 class="card-title">Categories</h5>
                            <p class="card-text h3"><?php echo $categories_count; ?></p>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-folder fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h5 class="card-title">Users</h5>
                            <p class="card-text h3"><?php echo $users_count; ?></p>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-users fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h5 class="card-title">Comments</h5>
                            <p class="card-text h3"><?php echo $comments_count; ?></p>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-comments fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Posts -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Recent Posts</h5>
        </div>
        <div class="card-body">
            <?php if (count($recent_posts) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_posts as $post): ?>
                        <tr>
                            <td><?php echo $post['title']; ?></td>
                            <td><span class="badge bg-secondary"><?php echo $post['category_name']; ?></span></td>
                            <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($post['status'] == 'published') ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($post['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="posts.php?action=edit&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted">No posts found.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>