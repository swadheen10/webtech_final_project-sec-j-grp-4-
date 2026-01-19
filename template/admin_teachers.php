<?php
session_start();
include 'config/database.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $delStmt = $conn->prepare("DELETE FROM users WHERE id = :id AND role = 'teacher'");
    $delStmt->bindParam(":id", $id);
    
    if($delStmt->execute()) {
        $msg = "Teacher deleted successfully.";
    } else {
        $err = "Failed to delete teacher.";
    }
}


$search = "";
$query = "SELECT * FROM users WHERE role = 'teacher'";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
  
    $query .= " AND (full_name LIKE :search OR email LIKE :search)";
}

$query .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($query);

if ($search) {
    $searchTerm = "%$search%";
    $stmt->bindParam(':search', $searchTerm);
}

$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'templates/header.php';
include 'templates/nav_admin.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color: #764ba2;">Manage Teachers</h3>
        <a href="admin_add_user.php" class="btn text-white fw-bold shadow-sm" style="background-color: #764ba2;">+ Add New</a>
    </div>

    <?php if(isset($msg)) echo "<div class='alert alert-success shadow-sm'>$msg</div>"; ?>
    <?php if(isset($err)) echo "<div class='alert alert-danger shadow-sm'>$err</div>"; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Search Teacher by Name or Email..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn text-white fw-bold w-100" style="background: linear-gradient(to right, #667eea, #764ba2);">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="p-3 ps-4">Full Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($teachers) > 0): ?>
                            <?php foreach($teachers as $t): ?>
                            <tr>
                                <td class="p-3 ps-4 fw-bold"><?php echo htmlspecialchars($t['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($t['email']); ?></td>
                                <td><span class="badge bg-success bg-opacity-75"><?php echo htmlspecialchars($t['department']); ?></span></td>
                                <td class="text-end pe-4">
                                    <a href="admin_edit_user.php?id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                    <a href="admin_teachers.php?delete_id=<?php echo $t['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Are you sure you want to delete this teacher?');">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center p-4 text-muted">No teachers found matching your search.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>