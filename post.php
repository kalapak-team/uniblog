<?php
require_once 'config/db.php';
$pageTitle = "Post";
include 'includes/header.php';

// Get post ID from URL
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch post details
$stmt = $pdo->prepare("SELECT p.*, u.first_name, u.last_name, c.name as category_name 
                       FROM posts p 
                       LEFT JOIN users u ON p.author_id = u.id 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.id = ? AND p.status = 'published'");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// If post not found, redirect to blog page
if (!$post) {
    header('Location: blog.php');
    exit;
}

// Update page title
$pageTitle = $post['title'];

// Fetch comments for this post
$comment_stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? AND status = 'approved' ORDER BY created_at DESC");
$comment_stmt->execute([$post_id]);
$comments = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    $author_name = trim($_POST['author_name']);
    $author_email = trim($_POST['author_email']);
    $comment_content = trim($_POST['comment_content']);
    
    if (!empty($author_name) && !empty($author_email) && !empty($comment_content)) {
        $insert_stmt = $pdo->prepare("INSERT INTO comments (post_id, author_name, author_email, content) VALUES (?, ?, ?, ?)");
        $insert_stmt->execute([$post_id, $author_name, $author_email, $comment_content]);
        
        $success_msg = "Your comment has been submitted and is awaiting moderation.";
    } else {
        $error_msg = "Please fill in all fields.";
    }
}
?>

<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="blog.php">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $post['title']; ?></li>
        </ol>
    </nav>
    
    <article>
        <header class="mb-4">
            <h1 class="fw-bold mb-1"><?php echo $post['title']; ?></h1>
            <div class="text-muted fst-italic mb-2">
                Posted on <?php echo date('F j, Y', strtotime($post['created_at'])); ?> by 
                <?php echo $post['first_name'] . ' ' . $post['last_name']; ?>
            </div>
            <a class="badge bg-secondary text-decoration-none text-light" href="#!"><?php echo $post['category_name']; ?></a>
        </header>
        
        <?php if ($post['featured_image']): ?>
        <figure class="mb-4">
            <img class="img-fluid rounded" src="uploads/<?php echo $post['featured_image']; ?>" alt="<?php echo $post['title']; ?>" />
        </figure>
        <?php endif; ?>
        
        <section class="mb-5">
            <?php echo $post['content']; ?>
        </section>
    </article>
    
    <!-- Comments section -->
    <section class="mb-5">
        <div class="card bg-light">
            <div class="card-body">
                <h4 class="mb-4">Comments (<?php echo count($comments); ?>)</h4>
                
                <?php if (isset($success_msg)): ?>
                <div class="alert alert-success"><?php echo $success_msg; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_msg)): ?>
                <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                <?php endif; ?>
                
                <!-- Comment form -->
                <form method="POST" class="mb-5">
                    <div class="form-group mb-3">
                        <label for="author_name">Name</label>
                        <input type="text" class="form-control" id="author_name" name="author_name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="author_email">Email</label>
                        <input type="email" class="form-control" id="author_email" name="author_email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="comment_content">Comment</label>
                        <textarea class="form-control" id="comment_content" name="comment_content" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="submit_comment" class="btn btn-primary">Submit Comment</button>
                </form>
                
                <!-- Comments list -->
                <?php if (count($comments) > 0): ?>
                    <?php foreach ($comments as $comment): ?>
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="Commenter avatar" />
                        </div>
                        <div class="ms-3">
                            <div class="fw-bold"><?php echo $comment['author_name']; ?></div>
                            <small class="text-muted"><?php echo date('F j, Y', strtotime($comment['created_at'])); ?></small>
                            <p><?php echo $comment['content']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No comments yet. Be the first to comment!</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>