
<?php
include_once '../includes/config.php';
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
    <link rel="shortcut icon" href="../assets/img/LOGO-att.jpg" type="image/x-icon">
  <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="../assets/libraries/fontawesome6/free/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="../assets/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <img src="../assets/img/LOGO-att.jpg" alt="SAMS Logo" height="40" class="me-2 bg-white" style="border-radius: 50%;">
                Attendance Star
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php
                // Set current page variable in each view before including topnav.php, e.g. $currentPage = 'students';
                if (!isset($currentPage)) {
                    $currentPage = basename($_SERVER['PHP_SELF'], '.php');
                }
                ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link<?php echo ($currentPage == 'dashboard') ? ' active" aria-current="page"' : '"'; ?> href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo ($currentPage == 'attendance') ? ' active" aria-current="page"' : '"'; ?> href="attendance.php">
                            <i class="fas fa-clipboard-check me-1"></i> Attendance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo ($currentPage == 'students') ? ' active" aria-current="page"' : '"'; ?> href="students.php">
                            <i class="fas fa-user-graduate me-1"></i> Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo ($currentPage == 'calendar') ? ' active" aria-current="page"' : '"'; ?> href="calendar.php">
                            <i class="fas fa-calendar-alt me-1"></i> Calendar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo ($currentPage == 'reports') ? ' active" aria-current="page"' : '"'; ?> href="reports.php">
                            <i class="fas fa-chart-bar me-1"></i> Reports
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" id="notificationsList">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <!-- Notifications will be loaded here -->
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../assets/img/CEO.jpg" alt="Profile" class="rounded-circle" width="30" height="30" id="navProfileImg">
                            <span id="navUsername">julias Muyambi</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>