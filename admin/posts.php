<?php
require_once '../config/db.php';
$pageTitle = "Manage Posts";
include 'includes/header.php';
include 'includes/sidebar.php';

// Handle post actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($action == 'delete' && $post_id > 0) {
        // Delete post
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$post_id]);
        $_SESSION['success_msg'] = "Post deleted successfully.";
        header('Location: posts.php');
        exit;
    } elseif ($action == 'toggle_status' && $post_id > 0) {
        // Toggle post status
        $stmt = $pdo->prepare("SELECT status FROM posts WHERE id = ?");
        $stmt->execute([$post_id]);
        $post = $stmt->fetch();
        
        $new_status = ($post['status'] == 'published') ? 'draft' : 'published';
        $update_stmt = $pdo->prepare("UPDATE posts SET status = ? WHERE id = ?");
        $update_stmt->execute([$new_status, $post_id]);
        
        $_SESSION['success_msg'] = "Post status updated successfully.";
        header('Location: posts.php');
        exit;
    }
}

// Handle form submission for adding/editing posts
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $excerpt = trim($_POST['excerpt']);
    $category_id = (int)$_POST['category_id'];
    $status = $_POST['status'];
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    
    // Handle image upload
    $featured_image = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $file_name = time() . '_' . basename($_FILES['featured_image']['name']);
        $file_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $file_path)) {
            $featured_image = $file_name;
        }
    }
    
    if ($post_id > 0) {
        // Update existing post
        if ($featured_image) {
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, excerpt = ?, category_id = ?, status = ?, featured_image = ? WHERE id = ?");
            $stmt->execute([$title, $content, $excerpt, $category_id, $status, $featured_image, $post_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, excerpt = ?, category_id = ?, status = ? WHERE id = ?");
            $stmt->execute([$title, $content, $excerpt, $category_id, $status, $post_id]);
        }
        $_SESSION['success_msg'] = "Post updated successfully.";
    } else {
        // Insert new post
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, excerpt, category_id, author_id, status, featured_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $content, $excerpt, $category_id, $_SESSION['admin_id'], $status, $featured_image]);
        $_SESSION['success_msg'] = "Post created successfully.";
    }
    
    header('Location: posts.php');
    exit;
}

// Fetch all posts
$posts = $pdo->query("SELECT p.*, c.name as category_name, u.first_name, u.last_name 
                      FROM posts p 
                      LEFT JOIN categories c ON p.category_id = c.id 
                      LEFT JOIN users u ON p.author_id = u.id 
                      ORDER BY p.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories for dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Check if we're editing a post
$editing_post = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $post_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $editing_post = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h1 class="h2">Manage Posts</h1>
    
    <?php if (isset($_SESSION['success_msg'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><?php echo $editing_post ? 'Edit Post' : 'Add New Post'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <?php if ($editing_post): ?>
                        <input type="hidden" name="post_id" value="<?php echo $editing_post['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $editing_post ? htmlspecialchars($editing_post['title']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="10" required><?php echo $editing_post ? htmlspecialchars($editing_post['content']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?php echo $editing_post ? htmlspecialchars($editing_post['excerpt']) : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo ($editing_post && $editing_post['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="draft" <?php echo ($editing_post && $editing_post['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                                        <option value="published" <?php echo ($editing_post && $editing_post['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="featured_image" class="form-label">Featured Image</label>
                            <input type="file" class="form-control" id="featured_image" name="featured_image">
                            <?php if ($editing_post && $editing_post['featured_image']): ?>
                            <div class="mt-2">
                                <img src="../uploads/<?php echo $editing_post['featured_image']; ?>" alt="Current featured image" style="max-height: 150px;">
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><?php echo $editing_post ? 'Update Post' : 'Create Post'; ?></button>
                        
                        <?php if ($editing_post): ?>
                        <a href="posts.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">All Posts</h5>
                </div>
                <div class="card-body">
                    <?php if (count($posts) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo $post['category_name']; ?></span></td>
                                    <td><?php echo $post['first_name'] . ' ' . $post['last_name']; ?></td>
                                    <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($post['status'] == 'published') ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($post['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="posts.php?action=edit&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="posts.php?action=toggle_status&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-<?php echo ($post['status'] == 'published') ? 'warning' : 'success'; ?>">
                                            <?php echo ($post['status'] == 'published') ? 'Unpublish' : 'Publish'; ?>
                                        </a>
                                        <a href="posts.php?action=delete&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-danger delete-btn">Delete</a>
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
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>