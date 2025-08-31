// Check if user is logged in
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the profile page
    initProfilePage();

    // Set up event listeners
    document.getElementById('saveProfileBtn').addEventListener('click', saveProfileChanges);
    document.getElementById('passwordChangeForm').addEventListener('submit', changePassword);
    document.getElementById('profileImageUrl').addEventListener('input', updatePreviewImage);
});

/**
 * Initialize the profile page with user data
 */
function initProfilePage() {
    // Update navbar profile
    updateNavProfile();
    
    // Load notifications
    loadNotifications();
    
    // Setup notification dropdown
    setupNotificationDropdown();
    
    // Get current user data
    const currentUser = getCurrentUser();
    if (!currentUser) return;
    
    // Set profile image
    const profileImage = currentUser.profileImage || 'https://via.placeholder.com/150';
    document.getElementById('profileImage').src = profileImage;
    document.getElementById('editProfileImage').src = profileImage;
    document.getElementById('profileImageUrl').value = profileImage;
    
    // Set profile information
    document.getElementById('profileName').textContent = currentUser.name;
    document.getElementById('profileStudentId').textContent = `Student ID: ${currentUser.studentId}`;
    
    // Set personal information
    document.getElementById('infoName').textContent = currentUser.name;
    document.getElementById('infoStudentId').textContent = currentUser.studentId;
    document.getElementById('infoEmail').textContent = currentUser.email;
    document.getElementById('infoUsername').textContent = currentUser.username;
    
    // Set academic information
    document.getElementById('infoCourse').textContent = currentUser.course || 'Computer Science';
    document.getElementById('infoYear').textContent = currentUser.year || '3';
    
    // Calculate and set attendance rate
    const attendanceStats = calculateAttendanceStats(currentUser.studentId);
    document.getElementById('infoAttendanceRate').textContent = `${attendanceStats.rate}%`;
    
    // Set last attendance date
    const lastAttendance = getLastAttendanceDate(currentUser.studentId);
    document.getElementById('infoLastAttendance').textContent = lastAttendance ? formatDate(lastAttendance) : 'No records';
    
    // Set edit form values
    document.getElementById('editName').value = currentUser.name;
    document.getElementById('editEmail').value = currentUser.email;
    document.getElementById('editUsername').value = currentUser.username;
}

/**
 * Update the navbar profile information
 */
function updateNavProfile() {
    const currentUser = getCurrentUser();
    if (currentUser) {
        document.getElementById('navUsername').textContent = currentUser.name;
        document.getElementById('navProfileImg').src = currentUser.profileImage || 'https://via.placeholder.com/150';
        
        // Add click event to profile image to toggle dropdown manually
        const userDropdownToggle = document.getElementById('userDropdown');
        const profileDropdownMenu = userDropdownToggle.nextElementSibling;
        
        userDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Create dropdown instance if it doesn't exist
            const dropdownInstance = bootstrap.Dropdown.getInstance(userDropdownToggle) || 
                                   new bootstrap.Dropdown(userDropdownToggle);
            
            // Toggle the dropdown
            if (profileDropdownMenu.classList.contains('show')) {
                dropdownInstance.hide();
            } else {
                dropdownInstance.show();
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileDropdownMenu.contains(e.target) && !userDropdownToggle.contains(e.target)) {
                const dropdownInstance = bootstrap.Dropdown.getInstance(userDropdownToggle);
                if (dropdownInstance && profileDropdownMenu.classList.contains('show')) {
                    dropdownInstance.hide();
                }
            }
        });
    }
}

/**
 * Load notifications from localStorage
 */
function loadNotifications() {
    const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
    const unreadCount = notifications.filter(notification => !notification.read).length;
    
    // Update notification badge
    const badge = document.getElementById('notificationBadge');
    badge.textContent = unreadCount;
    badge.style.display = unreadCount > 0 ? 'block' : 'none';
    
    // Update notifications dropdown
    const notificationsList = document.getElementById('notificationsList');
    notificationsList.innerHTML = ''; // Clear all notifications first
    
    // Add notification header
    const header = document.createElement('li');
    header.innerHTML = '<h6 class="dropdown-header">Notifications</h6>';
    notificationsList.appendChild(header);
    
    // Add divider
    const divider = document.createElement('li');
    divider.innerHTML = '<hr class="dropdown-divider">';
    notificationsList.appendChild(divider);
    
    // Add notifications to dropdown
    if (notifications.length === 0) {
        const noNotificationsItem = document.createElement('li');
        noNotificationsItem.innerHTML = '<span class="dropdown-item text-muted">No notifications</span>';
        notificationsList.appendChild(noNotificationsItem);
    } else {
        notifications.slice(0, 5).forEach(notification => {
            const notificationItem = document.createElement('li');
            const notificationLink = document.createElement('a');
            notificationLink.className = 'dropdown-item d-flex align-items-center' + (notification.read ? '' : ' fw-bold');
            notificationLink.href = '#';
            notificationLink.innerHTML = `
                <div class="me-3">
                    <i class="bi bi-bell-fill ${notification.read ? 'text-muted' : 'text-primary'}"></i>
                </div>
                <div>
                    <div class="small text-gray-500">${formatDate(new Date(notification.date))}</div>
                    <div>${notification.message}</div>
                </div>
            `;
            notificationItem.appendChild(notificationLink);
            notificationsList.appendChild(notificationItem);
            
            // Mark notification as read when clicked
            notificationLink.addEventListener('click', function() {
                markNotificationAsRead(notification.id);
                loadNotifications(); // Reload notifications
            });
        });
        
        // Add view all link if there are more than 5 notifications
        if (notifications.length > 5) {
            const viewAllItem = document.createElement('li');
            viewAllItem.innerHTML = '<a class="dropdown-item text-center" href="#">View all</a>';
            notificationsList.appendChild(viewAllItem);
        }
    }
}

/**
 * Mark a notification as read
 * @param {string} id - The notification ID
 */
function markNotificationAsRead(id) {
    const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
    const updatedNotifications = notifications.map(notification => {
        if (notification.id === id) {
            return { ...notification, read: true };
        }
        return notification;
    });
    
    localStorage.setItem('notifications', JSON.stringify(updatedNotifications));
}

/**
 * Set up notification dropdown toggle functionality
 */
function setupNotificationDropdown() {
    const notificationToggle = document.getElementById('notificationDropdown');
    const notificationMenu = document.querySelector('.dropdown-menu[aria-labelledby="notificationDropdown"]');
    
    // Add click event to notification bell to toggle dropdown manually
    notificationToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Create dropdown instance if it doesn't exist
        const dropdownInstance = bootstrap.Dropdown.getInstance(notificationToggle) || 
                               new bootstrap.Dropdown(notificationToggle);
        
        // Toggle the dropdown
        if (notificationMenu.classList.contains('show')) {
            dropdownInstance.hide();
        } else {
            dropdownInstance.show();
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationMenu.contains(e.target) && !notificationToggle.contains(e.target)) {
            const dropdownInstance = bootstrap.Dropdown.getInstance(notificationToggle);
            if (dropdownInstance && notificationMenu.classList.contains('show')) {
                dropdownInstance.hide();
            }
        }
    });
    
    // Demo: Add a new notification every 30 seconds for testing
    setInterval(function() {
        addDemoNotification();
    }, 30000);
}

/**
 * Add a demo notification for testing purposes
 */
function addDemoNotification() {
    const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
    const newNotification = {
        id: 'demo-' + Date.now(),
        message: 'Profile update reminder: Please verify your information',
        date: new Date().toISOString(),
        read: false
    };
    
    notifications.unshift(newNotification);
    localStorage.setItem('notifications', JSON.stringify(notifications));
    loadNotifications();
}

/**
 * Save profile changes
 */
function saveProfileChanges() {
    const name = document.getElementById('editName').value.trim();
    const email = document.getElementById('editEmail').value.trim();
    const username = document.getElementById('editUsername').value.trim();
    const profileImage = document.getElementById('profileImageUrl').value.trim();
    
    // Validate inputs
    if (!name || !email || !username) {
        showToast('Please fill in all required fields', 'error');
        return;
    }
    
    // Get current user data
    const users = JSON.parse(localStorage.getItem('users')) || [];
    const currentUser = getCurrentUser();
    
    if (!currentUser) {
        showToast('User not found', 'error');
        return;
    }
    
    // Update user data
    const updatedUsers = users.map(user => {
        if (user.id === currentUser.id) {
            return {
                ...user,
                name,
                email,
                username,
                profileImage: profileImage || user.profileImage
            };
        }
        return user;
    });
    
    // Save updated users to localStorage
    localStorage.setItem('users', JSON.stringify(updatedUsers));
    
    // Update session user
    const sessionUser = JSON.parse(localStorage.getItem('currentUser'));
    sessionUser.name = name;
    sessionUser.email = email;
    sessionUser.username = username;
    sessionUser.profileImage = profileImage || sessionUser.profileImage;
    localStorage.setItem('currentUser', JSON.stringify(sessionUser));
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
    modal.hide();
    
    // Refresh profile page
    initProfilePage();
    
    // Show success toast
    showToast('Profile updated successfully');
}

/**
 * Update preview image when URL is changed
 */
function updatePreviewImage() {
    const imageUrl = document.getElementById('profileImageUrl').value.trim();
    if (imageUrl) {
        document.getElementById('editProfileImage').src = imageUrl;
    } else {
        document.getElementById('editProfileImage').src = 'https://via.placeholder.com/150';
    }
}

/**
 * Change user password
 * @param {Event} e - The form submit event
 */
function changePassword(e) {
    e.preventDefault();
    
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    // Validate inputs
    if (!currentPassword || !newPassword || !confirmPassword) {
        showToast('Please fill in all password fields', 'error');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showToast('New passwords do not match', 'error');
        return;
    }
    
    // Get current user data
    const users = JSON.parse(localStorage.getItem('users')) || [];
    const currentUser = getCurrentUser();
    
    if (!currentUser) {
        showToast('User not found', 'error');
        return;
    }
    
    // Find user in users array
    const userIndex = users.findIndex(user => user.id === currentUser.id);
    
    if (userIndex === -1) {
        showToast('User not found', 'error');
        return;
    }
    
    // Check if current password is correct
    if (users[userIndex].password !== currentPassword) {
        showToast('Current password is incorrect', 'error');
        return;
    }
    
    // Update password
    users[userIndex].password = newPassword;
    
    // Save updated users to localStorage
    localStorage.setItem('users', JSON.stringify(users));
    
    // Clear form
    document.getElementById('passwordChangeForm').reset();
    
    // Show success toast
    showToast('Password changed successfully');
}

/**
 * Calculate attendance statistics for a student
 * @param {string} studentId - The student ID
 * @returns {Object} - Attendance statistics
 */
function calculateAttendanceStats(studentId) {
    const attendanceRecords = JSON.parse(localStorage.getItem('attendance')) || [];
    const studentRecords = attendanceRecords.filter(record => record.studentId === studentId);
    
    if (studentRecords.length === 0) {
        return { total: 0, present: 0, absent: 0, late: 0, rate: 0 };
    }
    
    const present = studentRecords.filter(record => record.status === 'present').length;
    const absent = studentRecords.filter(record => record.status === 'absent').length;
    const late = studentRecords.filter(record => record.status === 'late').length;
    const total = studentRecords.length;
    
    // Calculate attendance rate (present + late) / total
    const rate = Math.round(((present + late) / total) * 100);
    
    return { total, present, absent, late, rate };
}

/**
 * Get the last attendance date for a student
 * @param {string} studentId - The student ID
 * @returns {string|null} - The last attendance date or null if no records
 */
function getLastAttendanceDate(studentId) {
    const attendanceRecords = JSON.parse(localStorage.getItem('attendance')) || [];
    const studentRecords = attendanceRecords.filter(record => record.studentId === studentId);
    
    if (studentRecords.length === 0) {
        return null;
    }
    
    // Sort records by date (newest first)
    studentRecords.sort((a, b) => new Date(b.date) - new Date(a.date));
    
    return studentRecords[0].date;
}

/**
 * Format a date string to a readable format
 * @param {string} dateString - The date string to format
 * @returns {string} - The formatted date
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

/**
 * Show a toast notification
 * @param {string} message - The message to display
 * @param {string} type - The type of toast (success, error)
 */
function showToast(message, type = 'success') {
    const toastEl = document.getElementById('notificationToast');
    const toast = new bootstrap.Toast(toastEl);
    
    document.getElementById('toastMessage').textContent = message;
    document.getElementById('toastTime').textContent = 'just now';
    
    // Set toast color based on type
    if (type === 'error') {
        toastEl.classList.add('bg-danger', 'text-white');
    } else {
        toastEl.classList.remove('bg-danger', 'text-white');
    }
    
    toast.show();
}