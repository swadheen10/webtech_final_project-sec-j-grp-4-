<?php
session_start(); 
include 'config/database.php';
include 'templates/header.php';


$error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in both email and password.";
    } else {
        
        $sql = "SELECT id, full_name, password, role FROM users WHERE email = :email LIMIT 1";
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                
                if (password_verify($password, $user['password'])) {
                    
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_role'] = $user['role'];

                    
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Invalid password. Please try again.";
                }
            } else {
                $error = "No account found with this email.";
            }
        } catch (PDOException $e) {
            $error = "System Error: " . $e->getMessage();
        }
    }
}
?>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    .card-login {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .form-control:focus {
        border-color: #764ba2;
        box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.25);
    }
    .btn-login {
        background: linear-gradient(to right, #667eea, #764ba2);
        border: none;
        color: white;
        transition: all 0.3s;
    }
    .btn-login:hover {
        background: linear-gradient(to right, #5a6fd6, #653f8f);
        color: white;
        transform: translateY(-2px);
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5 col-lg-4">
            
            <div class="card card-login bg-white">
                <div class="card-body p-5">
                    
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color: #764ba2;">Welcome Back</h2>
                        <p class="text-muted">Login to continue</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger text-center shadow-sm py-2">
                            <small><i class="bi bi-exclamation-triangle-fill"></i> <?php echo $error; ?></small>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-lg fs-6" placeholder="name@example.com" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg fs-6" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn btn-login w-100 py-2 fw-bold shadow">Sign In</button>
                        
                        <div class="text-center mt-4">
                            <span class="text-muted">New here? </span>
                            <a href="register.php" style="color: #764ba2; font-weight: bold; text-decoration: none;">Create Account</a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>