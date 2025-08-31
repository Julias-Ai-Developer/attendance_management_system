<?php include_once '../includes/topnav.php'; ?>

<!-- Main Content -->
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Attendance Calendar</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-2" id="prevMonth">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="currentMonthYear">Month Year</span>
                        <button class="btn btn-sm btn-outline-primary ms-2" id="nextMonth">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Calendar Legend -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-center flex-wrap">
                                <div class="me-3 mb-2">
                                    <span class="badge bg-success">Present</span>
                                </div>
                                <div class="me-3 mb-2">
                                    <span class="badge bg-danger">Absent</span>
                                </div>
                                <div class="me-3 mb-2">
                                    <span class="badge bg-warning text-dark">Late</span>
                                </div>
                                <div class="me-3 mb-2">
                                    <span class="badge bg-info text-dark">Excused</span>
                                </div>
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark">No Record</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Sunday</th>
                                    <th class="text-center">Monday</th>
                                    <th class="text-center">Tuesday</th>
                                    <th class="text-center">Wednesday</th>
                                    <th class="text-center">Thursday</th>
                                    <th class="text-center">Friday</th>
                                    <th class="text-center">Saturday</th>
                                </tr>
                            </thead>
                            <tbody id="calendarBody">
                                <!-- Calendar will be generated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Details Modal -->
    <div class="modal fade" id="attendanceDetailsModal" tabindex="-1" aria-labelledby="attendanceDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceDetailsModalLabel">Attendance Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Date:</strong> <span id="modalDate"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong> <span id="modalStatus"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Time In:</strong> <span id="modalTimeIn"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Time Out:</strong> <span id="modalTimeOut"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Duration:</strong> <span id="modalDuration"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Notes:</strong> <span id="modalNotes"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            Calendar loaded successfully!
        </div>
    </div>
</div>


<?php include_once '../includes/footer.php';
