<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

include 'templates/header.php';

$user_role = $_SESSION['user_role'];


if ($user_role == 'admin') {
    include 'templates/nav_admin.php';
    $totalStudents = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
    $totalTeachers = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'teacher'")->fetchColumn();

} elseif ($user_role == 'teacher') {
    include 'templates/nav_teacher.php';
    $totalStudents = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
    
    $stmt = $conn->prepare("SELECT department FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $myDept = $stmt->fetchColumn();

} elseif ($user_role == 'student') {
    include 'templates/nav_student.php';
    
   
    $stmt = $conn->prepare("SELECT cgpa, credits_completed, department FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $studentInfo = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold" style="color: #764ba2;"><?php echo ucfirst($user_role); ?> Dashboard</h2>
            <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
        </div>
    </div>

    <?php if ($user_role == 'admin'): ?>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="card-body">
                    <h5 class="text-muted">Total Students</h5>
                    <h2 class="fw-bold text-primary"><?php echo $totalStudents; ?></h2>
                </div>
                <div class="card-footer bg-white border-0"><a href="admin_students.php" class="text-decoration-none fw-bold">Manage Students &rarr;</a></div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="card-body">
                    <h5 class="text-muted">Total Teachers</h5>
                    <h2 class="fw-bold text-success"><?php echo $totalTeachers; ?></h2>
                </div>
                <div class="card-footer bg-white border-0"><a href="admin_teachers.php" class="text-decoration-none fw-bold text-success">Manage Teachers &rarr;</a></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($user_role == 'teacher'): ?>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="card-body">
                    <h5 class="text-muted">Department</h5>
                    <h2 class="fw-bold text-info"><?php echo $myDept; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="card-body">
                    <h5 class="text-muted">Students</h5>
                    <h2 class="fw-bold text-primary"><?php echo $totalStudents; ?></h2>
                </div>
                <div class="card-footer bg-white border-0"><a href="teacher_students.php" class="text-decoration-none fw-bold">View List &rarr;</a></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($user_role == 'student'): ?>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg h-100 bg-white rounded-4">
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                    <h1 class="display-3 fw-bold" style="color: #764ba2;"><?php echo number_format($studentInfo['cgpa'], 2); ?></h1>
                    <p class="mb-0 text-muted fw-bold">Current CGPA</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg h-100 bg-white rounded-4">
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                    <h1 class="display-3 fw-bold" style="color: #764ba2;"><?php echo $studentInfo['credits_completed']; ?></h1>
                    <p class="mb-0 text-muted fw-bold">Credits Completed</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg h-100 bg-white rounded-4">
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                    <h3 class="fw-bold" style="color: #764ba2;"><?php echo $studentInfo['department']; ?></h3>
                    <p class="mb-0 text-muted fw-bold">My Department</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12 text-center">
             <a href="profile.php" class="btn text-white fw-bold px-5 py-2 me-3 shadow-sm rounded-pill" 
                style="background: linear-gradient(to right, #667eea, #764ba2);">
                View Full Profile
             </a>
             
             <a href="student_teachers.php" class="btn fw-bold px-5 py-2 shadow-sm rounded-pill" 
                style="border: 2px solid #764ba2; color: #764ba2; background: white;">
                View Teachers
             </a>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php include 'templates/footer.php'; ?>