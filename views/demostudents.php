<?php include_once '../includes/topnav.php';?>


    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Student Management</h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                            <i class="fas fa-plus me-1"></i> Add New Student
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Filter Controls -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="classFilter" class="form-label">Class/Grade</label>
                                <select class="form-select" id="classFilter">
                                    <option value="all">All Classes</option>
                                    <!-- Classes will be populated dynamically -->
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="all">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" id="applyFilters">
                                    <i class="fas fa-filter me-1"></i> Apply Filters
                                </button>
                            </div>
                        </div>

                        <!-- Students Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Class/Grade</th>
                                        <th>Gender</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="studentsTableBody">
                                    <!-- Students will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Take Attendance Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Take Attendance</h5>
                        <div>
                            <input type="date" id="attendanceDate" class="form-control form-control-sm" value="">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="attendanceClass" class="form-label">Select Class/Grade</label>
                                <select class="form-select" id="attendanceClass">
                                    <option value="">Select Class</option>
                                    <!-- Classes will be populated dynamically -->
                                </select>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <button class="btn btn-primary" id="loadStudentsForAttendance">
                                    <i class="fas fa-users me-1"></i> Load Students
                                </button>
                            </div>
                        </div>

                        <!-- Attendance Form -->
                        <form id="attendanceForm" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="attendanceTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody id="attendanceTableBody">
                                        <!-- Students for attendance will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Save Attendance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="studentId" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="studentId" required>
                            </div>
                            <div class="col-md-6">
                                <label for="studentName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="studentName" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="studentClass" class="form-label">Class/Grade</label>
                                <select class="form-select" id="studentClass" required>
                                    <option value="">Select Class</option>
                                    <option value="Class 1">Class 1</option>
                                    <option value="Class 2">Class 2</option>
                                    <option value="Class 3">Class 3</option>
                                    <option value="Class 4">Class 4</option>
                                    <option value="Class 5">Class 5</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="studentGender" class="form-label">Gender</label>
                                <select class="form-select" id="studentGender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="studentContact" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="studentContact">
                            </div>
                            <div class="col-md-6">
                                <label for="studentEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="studentEmail">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="parentName" class="form-label">Parent/Guardian Name</label>
                                <input type="text" class="form-control" id="parentName">
                            </div>
                            <div class="col-md-6">
                                <label for="parentContact" class="form-label">Parent/Guardian Contact</label>
                                <input type="tel" class="form-control" id="parentContact">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="studentAddress" class="form-label">Address</label>
                                <textarea class="form-control" id="studentAddress" rows="2"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveStudentBtn">Save Student</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStudentForm">
                        <input type="hidden" id="editStudentIdHidden">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editStudentId" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="editStudentId" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="editStudentName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="editStudentName" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editStudentClass" class="form-label">Class/Grade</label>
                                <select class="form-select" id="editStudentClass" required>
                                    <option value="">Select Class</option>
                                    <option value="Class 1">Class 1</option>
                                    <option value="Class 2">Class 2</option>
                                    <option value="Class 3">Class 3</option>
                                    <option value="Class 4">Class 4</option>
                                    <option value="Class 5">Class 5</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editStudentGender" class="form-label">Gender</label>
                                <select class="form-select" id="editStudentGender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editStudentContact" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="editStudentContact">
                            </div>
                            <div class="col-md-6">
                                <label for="editStudentEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editStudentEmail">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editParentName" class="form-label">Parent/Guardian Name</label>
                                <input type="text" class="form-control" id="editParentName">
                            </div>
                            <div class="col-md-6">
                                <label for="editParentContact" class="form-label">Parent/Guardian Contact</label>
                                <input type="tel" class="form-control" id="editParentContact">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="editStudentAddress" class="form-label">Address</label>
                                <textarea class="form-control" id="editStudentAddress" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editStudentStatus" class="form-label">Status</label>
                                <select class="form-select" id="editStudentStatus" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="updateStudentBtn">Update Student</button>
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
                Operation completed successfully!
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Custom JS -->
    <script src="../assets/js/students.js"></script>
    
    <!-- Simple Footer -->
    <footer class="text-center py-3 mt-4 text-muted">
        <small>© 2025 Attendance Star | All Rights Reserved</small>
    </footer>
</body>
</html>