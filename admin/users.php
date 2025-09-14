<?php
require_once '../config/db.php';
$pageTitle = "Manage Users";
include 'includes/header.php';
include 'includes/sidebar.php';

// Handle user actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($action == 'delete' && $user_id > 0) {
        // Prevent deleting own account
        if ($user_id == $_SESSION['admin_id']) {
            $_SESSION['error_msg'] = "You cannot delete your own account.";
        } else {
            // Delete user
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $_SESSION['success_msg'] = "User deleted successfully.";
        }
        header('Location: users.php');
        exit;
    }
}

// Handle form submission for adding/editing users
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $role = $_POST['role'];
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->execute([$username, $user_id]);
    $existing_user = $stmt->fetch();
    
    if ($existing_user) {
        $_SESSION['error_msg'] = "Username already exists.";
        header('Location: users.php');
        exit;
    }
    
    if ($user_id > 0) {
        // Update existing user
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $email, $first_name, $last_name, $role, $user_id]);
        $_SESSION['success_msg'] = "User updated successfully.";
    } else {
        // Insert new user
        $password = password_hash('password123', PASSWORD_DEFAULT); // Default password
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $email, $first_name, $last_name, $role]);
        $_SESSION['success_msg'] = "User created successfully. Default password: password123";
    }
    
    header('Location: users.php');
    exit;
}

// Fetch all users
$users = $pdo->query("SELECT * FROM users ORDER BY role, username")->fetchAll(PDO::FETCH_ASSOC);

// Check if we're editing a user
$editing_user = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $editing_user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h1 class="h2">Manage Users</h1>
    
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
                    <h5 class="mb-0"><?php echo $editing_user ? 'Edit User' : 'Add New User'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <?php if ($editing_user): ?>
                        <input type="hidden" name="user_id" value="<?php echo $editing_user['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $editing_user ? htmlspecialchars($editing_user['username']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $editing_user ? htmlspecialchars($editing_user['email']) : ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $editing_user ? htmlspecialchars($editing_user['first_name']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $editing_user ? htmlspecialchars($editing_user['last_name']) : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="author" <?php echo ($editing_user && $editing_user['role'] == 'author') ? 'selected' : ''; ?>>Author</option>
                                <option value="admin" <?php echo ($editing_user && $editing_user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><?php echo $editing_user ? 'Update User' : 'Create User'; ?></button>
                        
                        <?php if ($editing_user): ?>
                        <a href="users.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">All Users</h5>
                </div>
                <div class="card-body">
                    <?php if (count($users) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($user['role'] == 'admin') ? 'danger' : 'primary'; ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="users.php?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <?php if ($user['id'] != $_SESSION['admin_id']): ?>
                                        <a href="users.php?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger delete-btn">Delete</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">No users found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>