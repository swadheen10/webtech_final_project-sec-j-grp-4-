<?php
session_start();
include 'config/database.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') { header("Location: login.php"); exit; }

$msg = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $dept = $_POST['department'];
    $pass = password_hash("123456", PASSWORD_DEFAULT); // Default Password
    
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    
    if ($check->rowCount() > 0) {
        $error = "Email already exists!";
    } else {
        $sql = "INSERT INTO users (full_name, email, password, role, department) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$name, $email, $pass, $role, $dept])) {
            $msg = "User added! Default password is '123456'.";
        } else {
            $error = "Failed to add user.";
        }
    }
}

include 'templates/header.php';
include 'templates/nav_admin.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 text-center pt-4">
                    <h4 class="fw-bold" style="color: #764ba2;">Add New User</h4>
                </div>
                <div class="card-body p-4">
                    <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
                    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Role</label>
                            <select name="role" class="form-select">
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Department</label>
                            <input type="text" name="department" class="form-control" required>
                        </div>
                        <button type="submit" class="btn w-100 text-white fw-bold" style="background: #764ba2;">Create User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>