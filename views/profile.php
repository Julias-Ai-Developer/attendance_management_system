<?php include_once '../includes/topnav.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$userData = null;
if ($userId) {
    $stmt = $conn->prepare("SELECT first_name, last_name, email, phone_number, user_name FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();
}
?>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Profile Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="profile-header text-center py-5">
                    <img src="../assets/img/CEO.jpg" alt="Profile" class="profile-img mb-3" id="profileImage">
                    <h2 id="profileName"><?php echo $userData ? htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']) : 'User'; ?></h2>
                    <p class="text-light mb-0" id="profileStudentId">Username: <?php echo $userData ? htmlspecialchars($userData['user_name']) : '-'; ?></p>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Full Name:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext" id="infoName"><?php echo $userData ? htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']) : '-'; ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Username:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext" id="infoUsername"><?php echo $userData ? htmlspecialchars($userData['user_name']) : '-'; ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Email:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext" id="infoEmail"><?php echo $userData ? htmlspecialchars($userData['email']) : '-'; ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Phone Number:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext" id="infoPhoneNumber"><?php echo $userData ? htmlspecialchars($userData['phone_number']) : '-'; ?></p>
                            </div>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fas fa-edit me-1"></i> Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Academic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Course:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext" id="infoCourse">Computer Science</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Year:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext" id="infoYear">3</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Attendance Rate:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext" id="infoAttendanceRate">85%</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Last Attendance:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext" id="infoLastAttendance">May 15, 2023</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Change Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="mb-0">Security</h5>
                    </div>
                    <div class="card-body">
                        <form id="passwordChangeForm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="currentPassword" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="currentPassword" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="newPassword" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirmPassword" required>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key me-1"></i> Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm">
                        <div class="mb-3 text-center">
                            <img src="" alt="Profile" class="rounded-circle mb-3" width="100" height="100" id="editProfileImage">
                            <div>
                                <label for="profileImageUrl" class="form-label">Profile Image URL</label>
                                <input type="text" class="form-control" id="profileImageUrl">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveProfileBtn">Save Changes</button>
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
                Profile updated successfully!
            </div>
        </div>
    </div>


    <script src="../assets/js/profile.js"></script>
    
   <?php include_once '../includes/footer.php';?>