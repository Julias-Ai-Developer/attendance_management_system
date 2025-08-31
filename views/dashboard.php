<?php include_once '../includes/topnav.php'; ?>
<!-- Main Content -->
<div class="container mt-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h2 class="card-title">Welcome, Kama<span id="welcomeUsername"></span>!</h2>
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
                    <h3 class="card-title status-present" id="presentCount">0</h3>
                    <p class="card-text">Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card text-center">
                <div class="card-header">Absent</div>
                <div class="card-body">
                    <h3 class="card-title status-absent" id="absentCount">0</h3>
                    <p class="card-text">Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card dashboard-card text-center">
                <div class="card-header">Late</div>
                <div class="card-body">
                    <h3 class="card-title status-late" id="lateCount">0</h3>
                    <p class="card-text">Days</p>
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
                    <h3 class="card-title" id="totalStudents">0</h3>
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
            Welcome to the Attendance Star!
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="../libraries/Chart.min.js"></script>
<!-- Custom JS -->
 <script src="../assets/js/dashboard.js"></script>

<?php include_once '../includes/footer.php'; ?>