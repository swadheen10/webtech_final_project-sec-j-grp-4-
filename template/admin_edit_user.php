<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') { header("Location: login.php"); exit; }
if (!isset($_GET['id'])) { header("Location: admin_students.php"); exit; }
$id = $_GET['id'];

// UPDATE LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $dept = $_POST['department'];
    $address = $_POST['address'];
    
   
    $cgpa = isset($_POST['cgpa']) ? $_POST['cgpa'] : 0.00;
    $credits = isset($_POST['credits_completed']) ? $_POST['credits_completed'] : 0;

    $sql = "UPDATE users SET full_name=?, email=?, department=?, address=?, cgpa=?, credits_completed=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if($stmt->execute([$name, $email, $dept, $address, $cgpa, $credits, $id])) {
        $msg = "User updated successfully!";
    } else {
        $error = "Update failed.";
    }
}


$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

include 'templates/header.php';
include 'templates/nav_admin.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 text-center pt-4"><h4 class="fw-bold" style="color: #764ba2;">Edit User</h4></div>
                <div class="card-body p-4">
                    <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
                    
                    <form method="POST">
                        <div class="mb-3"><label class="small text-muted fw-bold">Full Name</label><input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required></div>
                        <div class="mb-3"><label class="small text-muted fw-bold">Email</label><input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required></div>
                        <div class="mb-3"><label class="small text-muted fw-bold">Department</label><input type="text" name="department" class="form-control" value="<?php echo htmlspecialchars($user['department']); ?>"></div>
                        
                        <?php if($user['role'] == 'student'): ?>
                        <div class="row mb-3 bg-light p-3 rounded mx-0">
                            <h6 class="text-primary small fw-bold mb-3">Academic Info (Admin Only)</h6>
                            <div class="col-6">
                                <label class="small text-muted fw-bold">CGPA</label>
                                <input type="number" step="0.01" min="0" max="4" name="cgpa" class="form-control" value="<?php echo $user['cgpa']; ?>">
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold">Credits Completed</label>
                                <input type="number" name="credits_completed" class="form-control" value="<?php echo $user['credits_completed']; ?>">
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3"><label class="small text-muted fw-bold">Address</label><textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($user['address']); ?></textarea></div>
                        <button type="submit" class="btn w-100 text-white fw-bold" style="background: #764ba2;">Save Changes</button>
                    </form>
                    <div class="text-center mt-3"><a href="dashboard.php" class="text-decoration-none text-muted">Cancel</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>