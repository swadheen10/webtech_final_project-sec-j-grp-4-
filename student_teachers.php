<?php
session_start();
include 'config/database.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit;
}


$stmt = $conn->query("SELECT full_name, email, department, address FROM users WHERE role = 'teacher' ORDER BY department ASC");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'templates/header.php';
include 'templates/nav_student.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color: #764ba2;">Our Teachers</h3>
        <span class="badge bg-secondary p-2">Faculty Directory</span>
    </div>

    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="p-3 ps-4">Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Region</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($teachers as $t): ?>
                        <tr>
                            <td class="p-3 ps-4 fw-bold"><?php echo htmlspecialchars($t['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($t['email']); ?></td>
                            <td><span class="badge bg-success bg-opacity-75"><?php echo htmlspecialchars($t['department']); ?></span></td>
                            <td class="text-muted small"><?php echo htmlspecialchars($t['address']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>