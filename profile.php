<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];
$msg = ""; $error = "";


if (isset($_POST['update_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($current_pass, $user['password'])) {
        if ($new_pass === $confirm_pass && strlen($new_pass) >= 6) {
            $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $conn->prepare("UPDATE users SET password = :p WHERE id = :id")->execute([':p'=>$new_hash, ':id'=>$user_id]);
            $msg = "Password updated!";
        } else { $error = "Password mismatch or too short."; }
    } else { $error = "Incorrect current password."; }
}


if (isset($_POST['update_info'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check->execute([$email, $user_id]);
    
    if($check->rowCount() > 0) {
        $error = "Email already taken.";
    } else {
        $sql = "UPDATE users SET full_name=?, email=?, address=? WHERE id=?";
        if($conn->prepare($sql)->execute([$full_name, $email, $address, $user_id])){
            $msg = "Profile updated!";
            $_SESSION['user_name'] = $full_name;
        } else { $error = "Failed to update."; }
    }
}


$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

include 'templates/header.php';

if ($_SESSION['user_role'] == 'admin') include 'templates/nav_admin.php';
elseif ($_SESSION['user_role'] == 'teacher') include 'templates/nav_teacher.php';
elseif ($_SESSION['user_role'] == 'student') include 'templates/nav_student.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold display-4 shadow" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea, #764ba2);">
                             <?php echo strtoupper(substr($profile['full_name'], 0, 1)); ?>
                        </div>
                        <h4 class="mt-3"><?php echo htmlspecialchars($profile['full_name']); ?></h4>
                        <span class="badge bg-primary bg-opacity-75"><?php echo ucfirst($profile['role']); ?></span>
                    </div>
                    
                    <form method="POST">
                        <div class="mb-3"><label class="small text-muted fw-bold">Full Name</label><input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($profile['full_name']); ?>" required></div>
                        <div class="mb-3"><label class="small text-muted fw-bold">Email</label><input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($profile['email']); ?>" required></div>
                        <div class="mb-3"><label class="small text-muted fw-bold">Address</label><textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($profile['address']); ?></textarea></div>
                        
                        <?php if($profile['role'] == 'student'): ?>
                        <div class="row bg-light p-2 rounded mb-3 mx-0">
                            <div class="col-6">
                                <label class="small text-muted fw-bold">CGPA</label>
                                <input type="text" class="form-control bg-white" value="<?php echo $profile['cgpa']; ?>" readonly>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted fw-bold">Credits</label>
                                <input type="text" class="form-control bg-white" value="<?php echo $profile['credits_completed']; ?>" readonly>
                            </div>
                        </div>
                        <?php endif; ?>

                        <button type="submit" name="update_info" class="btn btn-primary w-100 fw-bold" style="background: #764ba2; border:none;">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4"><h4 class="fw-bold" style="color: #764ba2;">Security</h4></div>
                <div class="card-body p-4">
                    <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
                    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST">
                        <div class="mb-3"><input type="password" name="current_password" class="form-control" placeholder="Current Password" required></div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><input type="password" name="new_password" class="form-control" placeholder="New Password" required></div>
                            <div class="col-md-6 mb-3"><input type="password" name="confirm_password" class="form-control" placeholder="Confirm" required></div>
                        </div>
                        <div class="d-flex justify-content-end"><button type="submit" name="update_password" class="btn btn-dark fw-bold px-4">Update Password</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>