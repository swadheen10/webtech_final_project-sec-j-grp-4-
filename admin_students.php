<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') { header("Location: login.php"); exit; }


if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->prepare("DELETE FROM users WHERE id = :id AND role = 'student'")->execute([':id'=>$id]);
    $msg = "Student deleted successfully.";
}


$search = "";
$query = "SELECT * FROM users WHERE role = 'student'";

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
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'templates/header.php';
include 'templates/nav_admin.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color: #764ba2;">Manage Students</h3>
        <a href="admin_add_user.php" class="btn text-white fw-bold shadow-sm" style="background-color: #764ba2;">+ Add New</a>
    </div>

    <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Search by Name or Email..." value="<?php echo htmlspecialchars($search); ?>">
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
                            <th class="p-3 ps-4">Name</th>
                            <th>Email</th>
                            <th>Dept</th>
                            <th>CGPA</th>
                            <th>Credits</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($students) > 0): ?>
                            <?php foreach($students as $s): ?>
                            <tr>
                                <td class="p-3 ps-4 fw-bold"><?php echo htmlspecialchars($s['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($s['email']); ?></td>
                                <td><span class="badge bg-primary bg-opacity-75"><?php echo htmlspecialchars($s['department']); ?></span></td>
                                <td><?php echo number_format($s['cgpa'], 2); ?></td>
                                <td><?php echo $s['credits_completed']; ?></td>
                                <td class="text-end pe-4">
                                    <a href="admin_edit_user.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                    <a href="admin_students.php?delete_id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?');">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center p-4 text-muted">No students found matching your search.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>