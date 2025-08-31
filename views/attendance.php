<?php include_once '../includes/topnav.php';?>
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
                                        <th>Day</th>
                                        <th>Status</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Duration</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td colspan="7" class="text-center">No attendance records found</td>
                                    <!-- Attendance records will be loaded here -->
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