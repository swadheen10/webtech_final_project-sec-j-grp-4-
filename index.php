<?php include 'templates/header.php'; ?>

<div class="hero-section">
    <div class="container">
        <h1 class="hero-title">Welcome to STMS</h1>
        <p class="hero-subtitle">The efficient way to manage Students, Teachers, and Administration.</p>
        
        <div class="mt-4">
            <a href="login.php" class="btn btn-light btn-custom text-primary fw-bold shadow">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
            
            <a href="register.php" class="btn btn-outline-light btn-custom fw-bold">
                Create Account
            </a>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row text-center">
        <div class="col-md-4">
            <h3>Admin</h3>
            <p class="text-muted">Manage users and system settings.</p>
        </div>
        <div class="col-md-4">
            <h3>Teachers</h3>
            <p class="text-muted">Manage classes and view profiles.</p>
        </div>
        <div class="col-md-4">
            <h3>Students</h3>
            <p class="text-muted">Access academic information.</p>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>