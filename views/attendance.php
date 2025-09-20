<?php 
include_once '../includes/topnav.php';
// Process toast message from session
// Session is already started in topnav.php
$toast_message = '';
$toast_type = '';

if (isset($_SESSION['toast_message'])) {
    $toast_message = $_SESSION['toast_message'];
    $toast_type = $_SESSION['toast_type'];
    // Clear session message to prevent showing again on page refresh
    unset($_SESSION['toast_message']);
    unset($_SESSION['toast_type']);
}
?>
    <!-- Toast Notification -->
    <?php if (!empty($toast_message)): ?>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="notificationToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <small id="toastTime">just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body <?php echo $toast_type == 'success' ? 'bg-success text-white' : 'bg-danger text-white'; ?>">
                <?php echo $toast_message; ?>
            </div>
        </div>
    </div>
    <script>
        // Auto close toast after 5 seconds
        setTimeout(function() {
            var toastElement = document.getElementById('notificationToast');
            var toast = new bootstrap.Toast(toastElement);
            toast.hide();
        }, 5000);
    </script>
    <?php endif; ?>
    
    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Attendance Records</h5>
                        <button class="btn btn-sm btn-primary" id="exportBtn">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Filter Controls -->
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <label for="monthFilter" class="form-label">Month</label>
                                <select class="form-select" id="monthFilter">
                                    <option value="all">All Months</option>
                                    <option value="0">January</option>
                                    <option value="1">February</option>
                                    <option value="2">March</option>
                                    <option value="3">April</option>
                                    <option value="4">May</option>
                                    <option value="5">June</option>
                                    <option value="6">July</option>
                                    <option value="7">August</option>
                                    <option value="8">September</option>
                                    <option value="9">October</option>
                                    <option value="10">November</option>
                                    <option value="11">December</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="yearFilter" class="form-label">Year</label>
                                <select class="form-select" id="yearFilter">
                                    <!-- Years will be populated dynamically -->
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="all">All Statuses</option>
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="late">Late</option>
                                    <option value="excused">Excused</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" id="applyFilters">
                                    <i class="fas fa-filter me-1"></i> Apply Filters
                                </button>
                            </div>
                        </div>

                        <!-- Attendance Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Status</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch attendance records with student information
                                    $attendance_query = "
                                        SELECT a.*, s.full_name 
                                        FROM attendance a
                                        JOIN students s ON a.student_id = s.student_school_id
                                        WHERE a.deleted_at IS NULL
                                        ORDER BY a.attendance_date DESC, s.full_name ASC
                                    ";
                                    $attendance_result = $conn->query($attendance_query);
                                    
                                    if ($attendance_result->num_rows > 0) {
                                        while ($row = $attendance_result->fetch_assoc()) {
                                            // Determine badge color based on status
                                            $badge_class = 'bg-secondary';
                                            if ($row['status'] == 'present') {
                                                $badge_class = 'bg-success';
                                            } elseif ($row['status'] == 'absent') {
                                                $badge_class = 'bg-danger';
                                            } elseif ($row['status'] == 'late') {
                                                $badge_class = 'bg-warning text-dark';
                                            } elseif ($row['status'] == 'excused') {
                                                $badge_class = 'bg-info';
                                            }
                                            
                                            // Format time
                                            $time_in = $row['time_in'] ? date('h:i A', strtotime($row['time_in'])) : '-';
                                            $time_out = $row['time_out'] ? date('h:i A', strtotime($row['time_out'])) : '-';
                                            
                                            echo '<tr>';
                                            echo '<td>' . date('Y-m-d', strtotime($row['attendance_date'])) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['student_id']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['class']) . '</td>';
                                            echo '<td><span class="badge ' . $badge_class . '">' . ucfirst($row['status']) . '</span></td>';
                                            echo '<td>' . $time_in . '</td>';
                                            echo '<td>' . $time_out . '</td>';
                                            echo '<td>' . htmlspecialchars($row['notes']) . '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="8" class="text-center">No attendance records found</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="mb-0">Attendance Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3 text-center">
                                <div class="p-3 rounded" style="background-color: rgba(76, 175, 80, 0.1);">
                                    <h4 class="status-present" id="summaryPresent">0</h4>
                                    <p class="mb-0">Present</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div class="p-3 rounded" style="background-color: rgba(244, 67, 54, 0.1);">
                                    <h4 class="status-absent" id="summaryAbsent">0</h4>
                                    <p class="mb-0">Absent</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div class="p-3 rounded" style="background-color: rgba(255, 152, 0, 0.1);">
                                    <h4 class="status-late" id="summaryLate">0</h4>
                                    <p class="mb-0">Late</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div class="p-3 rounded" style="background-color: rgba(33, 150, 243, 0.1);">
                                    <h4 class="status-excused" id="summaryExcused">0</h4>
                                    <p class="mb-0">Excused</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                Attendance records loaded successfully!
            </div>
        </div>
    </div>

  
    <script src="../assets/js/attendance.js"></script>
    
   <?php include_once '../includes/footer.php'?>