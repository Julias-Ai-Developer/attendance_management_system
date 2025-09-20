<?php include_once '../includes/topnav.php'; 
include_once '../app/functions/functions.php';
// Fetch statistics for dashboard cards
$counts = count_students();
$allStudents = $counts['total_students'];

// Get today's attendance statistics
$today = date('Y-m-d');
$present_query = "SELECT COUNT(*) as count FROM attendance WHERE attendance_date = '$today' AND status = 'present'";
$absent_query = "SELECT COUNT(*) as count FROM attendance WHERE attendance_date = '$today' AND status = 'absent'";
$late_query = "SELECT COUNT(*) as count FROM attendance WHERE attendance_date = '$today' AND status = 'late'";

$present_result = $conn->query($present_query);
$absent_result = $conn->query($absent_query);
$late_result = $conn->query($late_query);

$present_count = $present_result->fetch_assoc()['count'];
$absent_count = $absent_result->fetch_assoc()['count'];
$late_count = $late_result->fetch_assoc()['count'];
?>
<!-- Main Content -->
<div class="container mt-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h2 class="card-title">
                        Welcome, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?>!
                    </h2>
                    <p class="card-text">Here's your attendance summary for this month.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card text-center">
                <div class="card-header">Present</div>
                <div class="card-body">
                    <h3 class="card-title status-present" id="presentCount"><?php echo $present_count; ?></h3>
                    <p class="card-text">Students Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card text-center">
                <div class="card-header">Absent</div>
                <div class="card-body">
                    <h3 class="card-title status-absent" id="absentCount"><?php echo $absent_count; ?></h3>
                    <p class="card-text">Students Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card text-center">
                <div class="card-header">Late</div>
                <div class="card-body">
                    <h3 class="card-title status-late" id="lateCount"><?php echo $late_count; ?></h3>
                    <p class="card-text">Students Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card text-center">
                <div class="card-header">Attendance Rate</div>
                <div class="card-body">
                    <h3 class="card-title" id="attendanceRate">0%</h3>
                    <p class="card-text">This Month</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Statistics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card dashboard-card text-center">
                <div class="card-header">Total Students</div>
                <div class="card-body">
                    <h3 class="card-title" id="totalStudents"><?=$allStudents?></h3>
                    <p class="card-text">Registered</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card dashboard-card text-center">
                <div class="card-header">Active Students</div>
                <div class="card-body">
                    <h3 class="card-title" id="activeStudents">0</h3>
                    <p class="card-text">Currently Active</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card dashboard-card text-center">
                <div class="card-header">Present Today</div>
                <div class="card-body">
                    <h3 class="card-title" id="presentToday">0</h3>
                    <p class="card-text">Students</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendance Records -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Attendance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td colspan="7" class="text-center">No attendance records found</td>
                                <!-- Recent attendance records will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <a href="attendance.php" class="btn btn-primary">View All Records</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Charts -->
<div class="row mb-4">
    <!-- Bar Chart -->
    <div class="col-md-8 mb-3">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Monthly Attendance Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Donut Chart -->
    <div class="col-md-4 mb-3">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Attendance Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="attendanceDonutChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Student Performance Bar Graph -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Student Performance</h5>
            </div>
            <div class="card-body">
                <canvas id="studentPerformanceChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js (Offline) -->
<script src="assets/jsbundles/chart.umd.js"></script>

<script>
    // Monthly Attendance Overview - Bar Chart
    new Chart(document.getElementById('attendanceChart'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Attendance',
                data: [80, 90, 75, 88, 95, 85],
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            }]
        }
    });

    // Attendance Distribution - Donut Chart
    new Chart(document.getElementById('attendanceDonutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Absent', 'Late'],
            datasets: [{
                data: [300, 50, 20],
                backgroundColor: ['#4caf50', '#f44336', '#ff9800']
            }]
        }
    });

    // Student Performance - Bar Chart
    new Chart(document.getElementById('studentPerformanceChart'), {
        type: 'bar',
        data: {
            labels: ['Math', 'Science', 'English', 'History'],
            datasets: [{
                label: 'Scores',
                data: [85, 90, 78, 88],
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        }
    });
</script>

</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-bell me-2"></i>
            <strong class="me-auto">Notification</strong>
            <small id="toastTime">just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            <?php echo isset($_SESSION['toast_message']) ? htmlspecialchars($_SESSION['toast_message']) : 'Operation completed successfully!'; ?>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['toast_message'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toast = document.getElementById('notificationToast');
            var toastMessage = document.getElementById('toastMessage');
            var toastTime = document.getElementById('toastTime');
            toastMessage.textContent = <?php echo json_encode($_SESSION['toast_message']); ?>;
            toastMessage.className = 'toast-body bg-success text-white';
            toastTime.textContent = 'just now';
            var bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        });
    </script>
    <?php unset($_SESSION['toast_message']); ?>
<?php endif; ?>

<!-- Chart.js -->
<script src="../libraries/Chart.min.js"></script>
<!-- Custom JS -->
 <script src="../assets/js/dashboard.js"></script>

<?php include_once '../includes/footer.php'; ?>