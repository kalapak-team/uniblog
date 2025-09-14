<?php
require_once '../config/db.php';
$pageTitle = "Manage Categories";
include 'includes/header.php';
include 'includes/sidebar.php';

// Handle category actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($action == 'delete' && $category_id > 0) {
        // Check if category has posts
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM posts WHERE category_id = ?");
        $stmt->execute([$category_id]);
        $post_count = $stmt->fetch()['count'];
        
        if ($post_count > 0) {
            $_SESSION['error_msg'] = "Cannot delete category with posts. Please reassign posts first.";
        } else {
            // Delete category
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$category_id]);
            $_SESSION['success_msg'] = "Category deleted successfully.";
        }
        header('Location: categories.php');
        exit;
    }
}

// Handle form submission for adding/editing categories
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    
    if ($category_id > 0) {
        // Update existing category
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $description, $category_id]);
        $_SESSION['success_msg'] = "Category updated successfully.";
    } else {
        // Insert new category
        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
        $_SESSION['success_msg'] = "Category created successfully.";
    }
    
    header('Location: categories.php');
    exit;
}

// Fetch all categories
$categories = $pdo->query("SELECT c.*, COUNT(p.id) as post_count 
                           FROM categories c 
                           LEFT JOIN posts p ON c.id = p.category_id 
                           GROUP BY c.id 
                           ORDER BY c.name")->fetchAll(PDO::FETCH_ASSOC);

// Check if we're editing a category
$editing_category = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $category_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$category_id]);
    $editing_category = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h1 class="h2">Manage Categories</h1>
    
    <?php if (isset($_SESSION['success_msg'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_msg'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><?php echo $editing_category ? 'Edit Category' : 'Add New Category'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <?php if ($editing_category): ?>
                        <input type="hidden" name="category_id" value="<?php echo $editing_category['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $editing_category ? htmlspecialchars($editing_category['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo $editing_category ? htmlspecialchars($editing_category['description']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><?php echo $editing_category ? 'Update Category' : 'Create Category'; ?></button>
                        
                        <?php if ($editing_category): ?>
                        <a href="categories.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">All Categories</h5>
                </div>
                <div class="card-body">
                    <?php if (count($categories) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Posts</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td><span class="badge bg-primary"><?php echo $category['post_count']; ?></span></td>
                                    <td>
                                        <a href="categories.php?action=edit&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="categories.php?action=delete&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-outline-danger delete-btn">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">No categories found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>