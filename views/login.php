<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - Attendance Star</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="../assets/libraries/bootstrap-5.3.7-dist/css/bootstrap.min.css">
    
  <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="../assets/libraries/fontawesome6/free/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="../assets/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid login-container d-flex justify-content-center align-items-center">
        <div class="card login-card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="../assets/img/LOGO-att.jpg" alt="SAMS Logo" height="60" class="mb-2">
                    <h2 class="card-title">Attendance Star</h2>
                    <p class="text-muted">Please login to continue</p>
                </div>
                <form id="loginForm" action="../app/auth/auth.php" method="post">
                    <div class="mb-3">
                        <label for="Email" class="form-label">Email/Phone Number</label>
                        <input type="text" class="form-control" id="user_credential" name="user_credential" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <div class="text-center mt-3">
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">Forgot password?</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <!-- Custom JS -->
    <script src="js/auth.js"></script>
    
    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="resetPasswordForm">
                        <div class="mb-3">
                            <label for="resetEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="resetEmail" placeholder="Enter your registered email" required>
                        </div>
                        <div class="mb-3">
                            <label for="studentId" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="studentId" placeholder="Enter your student ID" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="resetPasswordBtn">Send Reset Link</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Simple Footer -->
    <footer class="text-center py-3 mt-4 text-muted">
        <small>© 2023 Attendance Star | All Rights Reserved</small>
    </footer>

    <?php if (isset($_SESSION['toast_message'])): ?>
    <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
        <?php echo htmlspecialchars($_SESSION['toast_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toast = document.getElementById('notificationToast');
            var toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = <?php echo json_encode($_SESSION['toast_message']); ?>;
            toastMessage.className = 'toast-body bg-danger text-white';
            var bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        });
    </script>
    <?php unset($_SESSION['toast_message']); ?>
    <?php endif; ?>
</body>
</html>