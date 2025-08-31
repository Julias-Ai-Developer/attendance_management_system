<?php include_once '../includes/topnav.php'?>
    <!-- Main Content -->
    <div class="container mt-4">
        <h2 class="mb-4">Attendance Reports</h2>

        <!-- Report Generator Card -->
        <div class="card dashboard-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Generate Report</h5>
            </div>
            <div class="card-body">
                <form id="reportForm">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="reportType" class="form-label">Report Type</label>
                            <select class="form-select" id="reportType" required>
                                <option value="" selected disabled>Select Report Type</option>
                                <option value="daily">Daily Report</option>
                                <option value="weekly">Weekly Report</option>
                                <option value="monthly">Monthly Report</option>
                                <option value="custom">Custom Date Range</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3" id="dateContainer">
                            <label for="reportDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="reportDate">
                        </div>
                        <div class="col-md-3 mb-3 d-none" id="weekContainer">
                            <label for="reportWeek" class="form-label">Week</label>
                            <input type="week" class="form-control" id="reportWeek">
                        </div>
                        <div class="col-md-3 mb-3 d-none" id="monthContainer">
                            <label for="reportMonth" class="form-label">Month</label>
                            <input type="month" class="form-control" id="reportMonth">
                        </div>
                        <div class="col-md-3 mb-3 d-none" id="startDateContainer">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate">
                        </div>
                        <div class="col-md-3 mb-3 d-none" id="endDateContainer">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="reportFormat" class="form-label">Format</label>
                            <select class="form-select" id="reportFormat" required>
                                <option value="table" selected>Table View</option>
                                <option value="csv">CSV Export</option>
                                <option value="pdf">PDF Export</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-alt me-1"></i> Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Report Results Card -->
        <div class="card dashboard-card mb-4 d-none" id="reportResultsCard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="reportTitle">Report Results</h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary me-2" id="exportCsvBtn">
                        <i class="fas fa-file-csv me-1"></i> Export CSV
                    </button>
                    <button class="btn btn-sm btn-outline-primary" id="exportPdfBtn">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Report Summary -->
                <div class="row mb-4" id="reportSummary">
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 id="totalDays">0</h3>
                                <p class="mb-0">Total Days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 id="presentDays">0</h3>
                                <p class="mb-0">Present Days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 id="absentDays">0</h3>
                                <p class="mb-0">Absent Days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 id="attendanceRate">0%</h3>
                                <p class="mb-0">Attendance Rate</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="reportTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Status</th>
                                <th>Check-in Time</th>
                                <th>Check-out Time</th>
                                <th>Duration</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody id="reportTableBody">
                            <!-- Report data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Saved Reports Card -->
        <div class="card dashboard-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Saved Reports</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="savedReportsTable">
                        <thead>
                            <tr>
                                <th>Report Name</th>
                                <th>Type</th>
                                <th>Date Range</th>
                                <th>Generated On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="savedReportsTableBody">
                            <!-- Saved reports will be loaded here -->
                        </tbody>
                    </table>
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
                Report generated successfully!
            </div>
        </div>
    </div>

  

    <script src="../assets/js/reports.js"></script>

<?php include_once '../includes/footer.php'?>
