<?php
require_once '../config/db.php';
$pageTitle = "Manage Comments";
include 'includes/header.php';
include 'includes/sidebar.php';

// Handle comment actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $comment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($action == 'delete' && $comment_id > 0) {
        // Delete comment
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$comment_id]);
        $_SESSION['success_msg'] = "Comment deleted successfully.";
        header('Location: comments.php');
        exit;
    } elseif ($action == 'approve' && $comment_id > 0) {
        // Approve comment
        $stmt = $pdo->prepare("UPDATE comments SET status = 'approved' WHERE id = ?");
        $stmt->execute([$comment_id]);
        $_SESSION['success_msg'] = "Comment approved successfully.";
        header('Location: comments.php');
        exit;
    } elseif ($action == 'reject' && $comment_id > 0) {
        // Reject comment (mark as spam)
        $stmt = $pdo->prepare("UPDATE comments SET status = 'spam' WHERE id = ?");
        $stmt->execute([$comment_id]);
        $_SESSION['success_msg'] = "Comment marked as spam.";
        header('Location: comments.php');
        exit;
    }
}

// Fetch all comments with post information
$comments = $pdo->query("SELECT c.*, p.title as post_title 
                         FROM comments c 
                         LEFT JOIN posts p ON c.post_id = p.id 
                         ORDER BY c.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <h1 class="h2">Manage Comments</h1>
    
    <?php if (isset($_SESSION['success_msg'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">All Comments</h5>
                </div>
                <div class="card-body">
                    <?php if (count($comments) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Author</th>
                                    <th>Comment</th>
                                    <th>Post</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($comments as $comment): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($comment['author_name']); ?></strong><br>
                                        <small><?php echo htmlspecialchars($comment['author_email']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($comment['content'], 0, 100)); ?><?php echo strlen($comment['content']) > 100 ? '...' : ''; ?></td>
                                    <td><?php echo htmlspecialchars($comment['post_title']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo ($comment['status'] == 'approved') ? 'success' : 
                                                 (($comment['status'] == 'pending') ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($comment['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($comment['status'] == 'pending'): ?>
                                        <a href="comments.php?action=approve&id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-outline-success">Approve</a>
                                        <a href="comments.php?action=reject&id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-outline-danger">Spam</a>
                                        <?php endif; ?>
                                        <a href="comments.php?action=delete&id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-outline-danger delete-btn">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">No comments found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>