<?php include_once '../includes/topnav.php';

$fetch_students = $conn->query("SELECT * from students WHERE deleted_at IS NULL ORDER BY id DESC");
$fetch_classes_attendance = $conn->query("SELECT class_name FROM student_classes WHERE deleted_at IS NULL ORDER BY id DESC");
$fetch_classes_add = $conn->query("SELECT class_name FROM student_classes WHERE deleted_at IS NULL ORDER BY id DESC");
$fetch_classes_edit = $conn->query("SELECT class_name FROM student_classes WHERE deleted_at IS NULL ORDER BY id DESC");
$fetch_classes_filter = $conn->query("SELECT class_name FROM student_classes WHERE deleted_at IS NULL ORDER BY id DESC");
$fetch_gender = $conn->query("SELECT gender from students WHERE deleted_at IS NULL");

?>

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
                                <?php
                                while ($class_row = $fetch_classes_filter->fetch_assoc()) {
                                    $class_val = htmlspecialchars($class_row['class_name']);
                                    echo '<option value="' . $class_val . '">' . $class_val . '</option>';
                                }
                                ?>
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
                        <div class="d-flex justify-content-between align-items-center mb-2" id="studentsTableControls"></div>
                        <table class="table table-striped table-hover" id="studentsTable">
                            <thead>
                                <tr>
                                    <th scope="col">S/N</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Class/Grade</th>
                                    <th>Gender</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentsTableBody">
                                <?php
                                $i = 1;
                                while ($row = $fetch_students->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($row['student_school_id']); ?></td>
                                        <td><?= htmlspecialchars($row['full_name']); ?></td>
                                        <td><?= htmlspecialchars($row['class']); ?></td>
                                        <td>
                                            <?php
                                            if ($row['gender'] == '2') {
                                                echo 'Male';
                                            } elseif ($row['gender'] == '1') {
                                                echo 'Female';
                                            } else {
                                                echo htmlspecialchars($row['gender']);
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['phone_number']); ?></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editStudentModal"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteStudentModal"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>

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
                        <input type="date" id="attendanceDate" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="attendanceClass" class="form-label">Select Class/Grade</label>
                            <select class="form-select" id="attendanceClass" name="attendanceClass">
                                <option value="">Select Class</option>
                                <?php
                                while ($class_row = $fetch_classes_attendance->fetch_assoc()) {
                                    $class_val = htmlspecialchars($class_row['class_name']);
                                    echo '<option value="' . $class_val . '">' . $class_val . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-8 d-flex align-items-end">
                            <button class="btn btn-primary" id="loadStudentsForAttendance" name="load_students" >
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
                                    <?php
                                   

                                    ?>
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
                <form id="addStudentForm" action="../app/students/add-student.php" method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="studentId" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="studentId" name="studentId" required>
                        </div>
                        <div class="col-md-6">
                            <label for="studentName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="studentName" name="studentName" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="studentClass" class="form-label">Class/Grade</label>
                            <select class="form-select" id="studentClass" name="studentClass" required>
                                <option value="">Select Class</option>
                                <?php
                                while ($class_row = $fetch_classes_add->fetch_assoc()) {
                                    $class_val = htmlspecialchars($class_row['class_name']);
                                    echo '<option value="' . $class_val . '">' . $class_val . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="studentGender" class="form-label">Gender</label>
                            <select class="form-select" id="studentGender" name="studentGender" required>
                                <?php
                                // Always show Female (1) first, then Male (2)
                                echo '<option value="2">Male</option>';
                                echo '<option value="1">Female</option>';
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="studentContact" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="studentContact" name="studentContact">
                        </div>
                        <div class="col-md-6">
                            <label for="studentEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="studentEmail" name="studentEmail">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="parentName" class="form-label">Parent/Guardian Name</label>
                            <input type="text" class="form-control" id="parentName" name="parentName">
                        </div>
                        <div class="col-md-6">
                            <label for="parentContact" class="form-label">Parent/Guardian Contact</label>
                            <input type="tel" class="form-control" id="parentContact" name="parentContact">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="studentAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="studentAddress" name="studentAddress" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveStudentBtn" name="submit">Save Student</button>
                    </div>
                </form>
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
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <label for="editStudentId" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="editStudentId" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="editStudentName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editStudentName" required>
                        </div>
                        <div class="col-md-3">
                            <label for="editStudentClass" class="form-label">Class/Grade</label>
                            <select class="form-select" id="editStudentClass" required>
                                <option value="">Select Class</option>
                                <?php
                                while ($class_row = $fetch_classes_edit->fetch_assoc()) {
                                    $class_val = htmlspecialchars($class_row['class_name']);
                                    echo '<option value="' . $class_val . '">' . $class_val . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="editStudentGender" class="form-label">Gender</label>
                            <select class="form-select" id="editStudentGender" required>
                                <option value="">Select Gender</option>
                                <option value="1">Male</option>
                                <option value="0">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 align-items-center mt-2">
                        <div class="col-md-3">
                            <label for="editStudentContact" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="editStudentContact">
                        </div>
                        <div class="col-md-3">
                            <label for="editStudentEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editStudentEmail">
                        </div>
                        <div class="col-md-3">
                            <label for="editParentName" class="form-label">Parent/Guardian Name</label>
                            <input type="text" class="form-control" id="editParentName">
                        </div>
                        <div class="col-md-3">
                            <label for="editParentContact" class="form-label">Parent/Guardian Contact</label>
                            <input type="tel" class="form-control" id="editParentContact">
                        </div>
                    </div>
                    <div class="row g-3 align-items-center mt-2">
                        <div class="col-md-9">
                            <label for="editStudentAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="editStudentAddress" rows="4"></textarea>
                        </div>
                        <div class="col-md-3">
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

<!-- Delete Student Modal -->
<div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteStudentModalLabel">Delete Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this student?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteStudentBtn">Delete</button>
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
<?php unset($_SESSION['toast_message']);
endif; ?>


<script src="../assets/js/students.js"></script>
<script>
var loadStudentsBtn = document.getElementById('loadStudentsForAttendance');
if (loadStudentsBtn) {
    loadStudentsBtn.addEventListener('click', function() {
        var attendanceForm = document.getElementById('attendanceForm');
        var attendanceTableBody = document.getElementById('attendanceTableBody');
        var selectedClass = document.getElementById('attendanceClass').value;
        if (attendanceForm && attendanceTableBody && selectedClass) {
            attendanceForm.classList.remove('d-none');
            // AJAX request to PHP
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../app/attendance/load_students_by_class.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    attendanceTableBody.innerHTML = xhr.responseText;
                } else {
                    attendanceTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error loading students.</td></tr>';
                }
            };
            xhr.send('class=' + encodeURIComponent(selectedClass));
        } else {
            attendanceTableBody.innerHTML = '<tr><td colspan="8" class="text-center text-warning">Please select a class.</td></tr>';
        }
    });
}
</script>

<?php include_once '../includes/footer.php' ?>