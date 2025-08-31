/**
 * Student Management and Attendance Tracking JavaScript
 * This file handles student registration, management, and attendance tracking functionality
 */

// DOM Elements
let studentsTable;
let attendanceTable;

// Initialize when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {

    // Update navbar profile
    updateNavbarProfile();

    // Load notifications
    loadNotifications();
    setupNotificationDropdown();

    // Set today's date as default for attendance
    document.getElementById('attendanceDate').valueAsDate = new Date();

    // Initialize DataTables
    initStudentsTable();

    // Load classes for filters
    loadClassesForFilters();

    // Load students
    loadStudents();

    // Event listeners
    document.getElementById('applyFilters').addEventListener('click', applyFilters);
    document.getElementById('saveStudentBtn').addEventListener('click', saveStudent);
    document.getElementById('updateStudentBtn').addEventListener('click', updateStudent);
    document.getElementById('loadStudentsForAttendance').addEventListener('click', loadStudentsForAttendance);
    document.getElementById('attendanceForm').addEventListener('submit', saveAttendance);
});

/**
 * Initialize the students DataTable
 */
function initStudentsTable() {
    studentsTable = $('#studentsTable').DataTable({
        responsive: true,
        order: [[1, 'asc']], // Sort by name by default
        language: {
            emptyTable: "No students found"
        },
        columnDefs: [
            { targets: -1, orderable: false } // Disable sorting on actions column
        ]
    });
}

/**
 * Load classes for filter dropdowns
 */
function loadClassesForFilters() {
    const classes = getUniqueClasses();
    const classFilters = document.querySelectorAll('#classFilter, #attendanceClass, #studentClass, #editStudentClass');
    
    classFilters.forEach(select => {
        // Keep the first option (All Classes or Select Class)
        const firstOption = select.options[0];
        select.innerHTML = '';
        select.appendChild(firstOption);
        
        // Add class options
        classes.forEach(className => {
            const option = document.createElement('option');
            option.value = className;
            option.textContent = className;
            select.appendChild(option);
        });
    });
}

/**
 * Get unique classes from students data
 */
function getUniqueClasses() {
    const students = getStudentsFromStorage();
    const classSet = new Set();
    
    // Add default classes if no students exist yet
    if (students.length === 0) {
        return ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'];
    }
    
    students.forEach(student => {
        if (student.class) {
            classSet.add(student.class);
        }
    });
    
    return Array.from(classSet).sort();
}

/**
 * Load students from localStorage
 */
function loadStudents() {
    const students = getStudentsFromStorage();
    displayStudents(students);
}

/**
 * Get students from localStorage
 */
function getStudentsFromStorage() {
    return JSON.parse(localStorage.getItem('students') || '[]');
}

/**
 * Save students to localStorage
 */
function saveStudentsToStorage(students) {
    localStorage.setItem('students', JSON.stringify(students));
}

/**
 * Display students in the table
 */
function displayStudents(students) {
    studentsTable.clear();
    
    students.forEach(student => {
        const statusBadge = getStatusBadge(student.status || 'active');
        const actions = `
            <button class="btn btn-sm btn-primary me-1" onclick="editStudent('${student.id}')"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-danger" onclick="deleteStudent('${student.id}')"><i class="fas fa-trash"></i></button>
        `;
        
        studentsTable.row.add([
            student.id,
            student.name,
            student.class,
            student.gender,
            student.contact || '-',
            statusBadge,
            actions
        ]);
    });
    
    studentsTable.draw();
}

/**
 * Get status badge HTML
 */
function getStatusBadge(status) {
    const badgeClass = status === 'active' ? 'bg-success' : 'bg-secondary';
    return `<span class="badge ${badgeClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
}

/**
 * Apply filters to students table
 */
function applyFilters() {
    const classFilter = document.getElementById('classFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    let students = getStudentsFromStorage();
    
    // Apply class filter
    if (classFilter !== 'all') {
        students = students.filter(student => student.class === classFilter);
    }
    
    // Apply status filter
    if (statusFilter !== 'all') {
        students = students.filter(student => (student.status || 'active') === statusFilter);
    }
    
    displayStudents(students);
    showToast('Filters applied successfully');
}

/**
 * Save new student
 */
function saveStudent() {
    const studentId = document.getElementById('studentId').value.trim();
    const studentName = document.getElementById('studentName').value.trim();
    const studentClass = document.getElementById('studentClass').value;
    const studentGender = document.getElementById('studentGender').value;
    
    // Validate required fields
    if (!studentId || !studentName || !studentClass || !studentGender) {
        showToast('Please fill all required fields', 'error');
        return;
    }
    
    // Check if ID already exists
    const students = getStudentsFromStorage();
    if (students.some(student => student.id === studentId)) {
        showToast('Student ID already exists', 'error');
        return;
    }
    
    // Create new student object
    const newStudent = {
        id: studentId,
        name: studentName,
        class: studentClass,
        gender: studentGender,
        contact: document.getElementById('studentContact').value.trim(),
        email: document.getElementById('studentEmail').value.trim(),
        parentName: document.getElementById('parentName').value.trim(),
        parentContact: document.getElementById('parentContact').value.trim(),
        address: document.getElementById('studentAddress').value.trim(),
        status: 'active',
        createdAt: new Date().toISOString()
    };
    
    // Add to storage
    students.push(newStudent);
    saveStudentsToStorage(students);
    
    // Refresh table
    loadStudents();
    
    // Reset form and close modal
    document.getElementById('addStudentForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('addStudentModal')).hide();
    
    // Show success message
    showToast('Student added successfully');
    
    // Add notification
    addNotification(`New student ${studentName} has been registered`);
    
    // Refresh class filters
    loadClassesForFilters();
}

/**
 * Edit student - open modal with student data
 */
function editStudent(studentId) {
    const students = getStudentsFromStorage();
    const student = students.find(s => s.id === studentId);
    
    if (!student) {
        showToast('Student not found', 'error');
        return;
    }
    
    // Fill form with student data
    document.getElementById('editStudentIdHidden').value = student.id;
    document.getElementById('editStudentId').value = student.id;
    document.getElementById('editStudentName').value = student.name;
    document.getElementById('editStudentClass').value = student.class;
    document.getElementById('editStudentGender').value = student.gender;
    document.getElementById('editStudentContact').value = student.contact || '';
    document.getElementById('editStudentEmail').value = student.email || '';
    document.getElementById('editParentName').value = student.parentName || '';
    document.getElementById('editParentContact').value = student.parentContact || '';
    document.getElementById('editStudentAddress').value = student.address || '';
    document.getElementById('editStudentStatus').value = student.status || 'active';
    
    // Open modal
    const editModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
    editModal.show();
}

/**
 * Update student data
 */
function updateStudent() {
    const studentId = document.getElementById('editStudentIdHidden').value;
    const studentName = document.getElementById('editStudentName').value.trim();
    const studentClass = document.getElementById('editStudentClass').value;
    const studentGender = document.getElementById('editStudentGender').value;
    
    // Validate required fields
    if (!studentName || !studentClass || !studentGender) {
        showToast('Please fill all required fields', 'error');
        return;
    }
    
    // Update student data
    const students = getStudentsFromStorage();
    const studentIndex = students.findIndex(s => s.id === studentId);
    
    if (studentIndex === -1) {
        showToast('Student not found', 'error');
        return;
    }
    
    // Update student object
    students[studentIndex] = {
        ...students[studentIndex],
        name: studentName,
        class: studentClass,
        gender: studentGender,
        contact: document.getElementById('editStudentContact').value.trim(),
        email: document.getElementById('editStudentEmail').value.trim(),
        parentName: document.getElementById('editParentName').value.trim(),
        parentContact: document.getElementById('editParentContact').value.trim(),
        address: document.getElementById('editStudentAddress').value.trim(),
        status: document.getElementById('editStudentStatus').value,
        updatedAt: new Date().toISOString()
    };
    
    // Save to storage
    saveStudentsToStorage(students);
    
    // Refresh table
    loadStudents();
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('editStudentModal')).hide();
    
    // Show success message
    showToast('Student updated successfully');
    
    // Add notification
    addNotification(`Student ${studentName} information has been updated`);
}

/**
 * Delete student
 */
function deleteStudent(studentId) {
    if (!confirm('Are you sure you want to delete this student?')) {
        return;
    }
    
    const students = getStudentsFromStorage();
    const studentIndex = students.findIndex(s => s.id === studentId);
    
    if (studentIndex === -1) {
        showToast('Student not found', 'error');
        return;
    }
    
    const studentName = students[studentIndex].name;
    
    // Remove student
    students.splice(studentIndex, 1);
    saveStudentsToStorage(students);
    
    // Refresh table
    loadStudents();
    
    // Show success message
    showToast('Student deleted successfully');
    
    // Add notification
    addNotification(`Student ${studentName} has been removed from the system`);
}

/**
 * Load students for attendance
 */
function loadStudentsForAttendance() {
    const selectedClass = document.getElementById('attendanceClass').value;
    const attendanceDate = document.getElementById('attendanceDate').value;
    
    if (!selectedClass) {
        showToast('Please select a class', 'error');
        return;
    }
    
    if (!attendanceDate) {
        showToast('Please select a date', 'error');
        return;
    }
    
    // Get students of selected class
    const students = getStudentsFromStorage().filter(student => 
        student.class === selectedClass && 
        (student.status === 'active' || !student.status)
    );
    
    if (students.length === 0) {
        showToast('No active students found in this class', 'error');
        return;
    }
    
    // Check if attendance already exists for this class and date
    const existingAttendance = getAttendanceByClassAndDate(selectedClass, attendanceDate);
    
    // Display attendance form
    displayAttendanceForm(students, existingAttendance, attendanceDate);
}

/**
 * Get attendance records by class and date
 */
function getAttendanceByClassAndDate(className, date) {
    const attendanceRecords = JSON.parse(localStorage.getItem('attendanceRecords') || '[]');
    return attendanceRecords.filter(record => 
        record.class === className && 
        record.date === date
    );
}

/**
 * Display attendance form with students
 */
function displayAttendanceForm(students, existingAttendance, date) {
    const attendanceTableBody = document.getElementById('attendanceTableBody');
    attendanceTableBody.innerHTML = '';
    
    students.forEach(student => {
        // Check if student already has attendance for this date
        const existingRecord = existingAttendance.find(record => record.studentId === student.id);
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${student.id}</td>
            <td>${student.name}</td>
            <td>
                <select class="form-select form-select-sm attendance-status" data-student-id="${student.id}">
                    <option value="present" ${existingRecord && existingRecord.status === 'present' ? 'selected' : ''}>Present</option>
                    <option value="absent" ${existingRecord && existingRecord.status === 'absent' ? 'selected' : ''}>Absent</option>
                    <option value="late" ${existingRecord && existingRecord.status === 'late' ? 'selected' : ''}>Late</option>
                    <option value="excused" ${existingRecord && existingRecord.status === 'excused' ? 'selected' : ''}>Excused</option>
                </select>
            </td>
            <td>
                <input type="time" class="form-control form-control-sm time-in" data-student-id="${student.id}" 
                    value="${existingRecord && existingRecord.timeIn ? existingRecord.timeIn : ''}" 
                    ${existingRecord && (existingRecord.status === 'absent' || existingRecord.status === 'excused') ? 'disabled' : ''}>
            </td>
            <td>
                <input type="time" class="form-control form-control-sm time-out" data-student-id="${student.id}" 
                    value="${existingRecord && existingRecord.timeOut ? existingRecord.timeOut : ''}" 
                    ${existingRecord && (existingRecord.status === 'absent' || existingRecord.status === 'excused') ? 'disabled' : ''}>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm attendance-notes" data-student-id="${student.id}" 
                    value="${existingRecord && existingRecord.notes ? existingRecord.notes : ''}">
            </td>
        `;
        
        attendanceTableBody.appendChild(row);
    });
    
    // Show attendance form
    document.getElementById('attendanceForm').classList.remove('d-none');
    
    // Add event listeners to status dropdowns
    document.querySelectorAll('.attendance-status').forEach(select => {
        select.addEventListener('change', function() {
            const studentId = this.getAttribute('data-student-id');
            const timeInInput = document.querySelector(`.time-in[data-student-id="${studentId}"]`);
            const timeOutInput = document.querySelector(`.time-out[data-student-id="${studentId}"]`);
            
            if (this.value === 'absent' || this.value === 'excused') {
                timeInInput.disabled = true;
                timeOutInput.disabled = true;
                timeInInput.value = '';
                timeOutInput.value = '';
            } else {
                timeInInput.disabled = false;
                timeOutInput.disabled = false;
            }
        });
    });
}

/**
 * Save attendance records
 */
function saveAttendance(event) {
    event.preventDefault();
    
    const selectedClass = document.getElementById('attendanceClass').value;
    const attendanceDate = document.getElementById('attendanceDate').value;
    const currentUser = getCurrentUser();
    
    // Get all attendance records
    const allAttendanceRecords = JSON.parse(localStorage.getItem('attendanceRecords') || '[]');
    
    // Remove existing records for this class and date
    const filteredRecords = allAttendanceRecords.filter(record => 
        !(record.class === selectedClass && record.date === attendanceDate)
    );
    
    // Collect new attendance records
    const newRecords = [];
    const statusElements = document.querySelectorAll('.attendance-status');
    
    statusElements.forEach(statusElement => {
        const studentId = statusElement.getAttribute('data-student-id');
        const status = statusElement.value;
        const timeIn = document.querySelector(`.time-in[data-student-id="${studentId}"]`).value;
        const timeOut = document.querySelector(`.time-out[data-student-id="${studentId}"]`).value;
        const notes = document.querySelector(`.attendance-notes[data-student-id="${studentId}"]`).value;
        
        // Get student name
        const students = getStudentsFromStorage();
        const student = students.find(s => s.id === studentId);
        
        if (!student) return;
        
        // Create attendance record
        const attendanceRecord = {
            id: generateUniqueId(),
            studentId: studentId,
            studentName: student.name,
            class: selectedClass,
            date: attendanceDate,
            status: status,
            timeIn: status === 'absent' || status === 'excused' ? '' : timeIn,
            timeOut: status === 'absent' || status === 'excused' ? '' : timeOut,
            notes: notes,
            recordedBy: currentUser.username,
            recordedAt: new Date().toISOString()
        };
        
        newRecords.push(attendanceRecord);
    });
    
    // Save combined records
    const updatedRecords = [...filteredRecords, ...newRecords];
    localStorage.setItem('attendanceRecords', JSON.stringify(updatedRecords));
    
    // Show success message
    showToast('Attendance saved successfully');
    
    // Add notification
    addNotification(`Attendance for ${selectedClass} on ${formatDate(attendanceDate)} has been recorded`);
    
    // Hide attendance form
    document.getElementById('attendanceForm').classList.add('d-none');
}

/**
 * Generate a unique ID
 */
function generateUniqueId() {
    return Date.now().toString(36) + Math.random().toString(36).substr(2, 5);
}

/**
 * Format date for display
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

/**
 * Show toast notification
 */
function showToast(message, type = 'success') {
    const toast = document.getElementById('notificationToast');
    const toastMessage = document.getElementById('toastMessage');
    
    // Set message and style based on type
    toastMessage.textContent = message;
    toastMessage.className = 'toast-body';
    if (type === 'error') {
        toastMessage.classList.add('bg-danger', 'text-white');
    } else if (type === 'warning') {
        toastMessage.classList.add('bg-warning');
    } else {
        toastMessage.classList.add('bg-success', 'text-white');
    }
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

/**
 * Add a notification
 */
function addNotification(message) {
    const currentUser = getCurrentUser();
    const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
    
    const newNotification = {
        id: generateUniqueId(),
        message: message,
        timestamp: new Date().toISOString(),
        read: false,
        user: currentUser.username
    };
    
    notifications.unshift(newNotification);
    
    // Keep only the latest 50 notifications
    const trimmedNotifications = notifications.slice(0, 50);
    
    localStorage.setItem('notifications', JSON.stringify(trimmedNotifications));
    
    // Update notification badge
    updateNotificationBadge();
}

/**
 * Load notifications
 */
function loadNotifications() {
    const currentUser = getCurrentUser();
    const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
    const userNotifications = notifications.filter(notification => notification.user === currentUser.username);
    
    const notificationsList = document.getElementById('notificationsList');
    
    // Clear existing notifications except header and divider
    while (notificationsList.children.length > 2) {
        notificationsList.removeChild(notificationsList.lastChild);
    }
    
    // Add notifications or empty message
    if (userNotifications.length === 0) {
        const emptyItem = document.createElement('li');
        emptyItem.className = 'dropdown-item text-center text-muted';
        emptyItem.textContent = 'No notifications';
        notificationsList.appendChild(emptyItem);
    } else {
        userNotifications.slice(0, 5).forEach(notification => {
            const item = document.createElement('li');
            item.className = 'dropdown-item notification-item' + (notification.read ? ' read' : '');
            item.setAttribute('data-notification-id', notification.id);
            
            const content = document.createElement('div');
            content.className = 'd-flex flex-column';
            
            const message = document.createElement('span');
            message.textContent = notification.message;
            
            const time = document.createElement('small');
            time.className = 'text-muted';
            time.textContent = formatNotificationTime(notification.timestamp);
            
            content.appendChild(message);
            content.appendChild(time);
            item.appendChild(content);
            
            item.addEventListener('click', function() {
                markNotificationAsRead(notification.id);
            });
            
            notificationsList.appendChild(item);
        });
        
        // Add view all link if there are more than 5 notifications
        if (userNotifications.length > 5) {
            const divider = document.createElement('li');
            divider.innerHTML = '<hr class="dropdown-divider">';
            notificationsList.appendChild(divider);
            
            const viewAll = document.createElement('li');
            viewAll.className = 'dropdown-item text-center';
            viewAll.innerHTML = '<small>View all notifications</small>';
            notificationsList.appendChild(viewAll);
        }
    }
    
    // Update notification badge
    updateNotificationBadge();
}

/**
 * Mark notification as read
 */
function markNotificationAsRead(notificationId) {
    const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
    
    const updatedNotifications = notifications.map(notification => {
        if (notification.id === notificationId) {
            return { ...notification, read: true };
        }
        return notification;
    });
    
    localStorage.setItem('notifications', JSON.stringify(updatedNotifications));
    
    // Update UI
    const notificationItem = document.querySelector(`.notification-item[data-notification-id="${notificationId}"]`);
    if (notificationItem) {
        notificationItem.classList.add('read');
    }
    
    // Update notification badge
    updateNotificationBadge();
}

/**
 * Update notification badge count
 */
function updateNotificationBadge() {
    const currentUser = getCurrentUser();
    const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
    const unreadCount = notifications.filter(notification => 
        notification.user === currentUser.username && !notification.read
    ).length;
    
    const badge = document.getElementById('notificationBadge');
    badge.textContent = unreadCount;
    badge.style.display = unreadCount > 0 ? 'block' : 'none';
}

/**
 * Format notification time
 */
function formatNotificationTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diffMs = now - date;
    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHour = Math.floor(diffMin / 60);
    const diffDay = Math.floor(diffHour / 24);
    
    if (diffSec < 60) {
        return 'just now';
    } else if (diffMin < 60) {
        return `${diffMin} minute${diffMin > 1 ? 's' : ''} ago`;
    } else if (diffHour < 24) {
        return `${diffHour} hour${diffHour > 1 ? 's' : ''} ago`;
    } else if (diffDay < 7) {
        return `${diffDay} day${diffDay > 1 ? 's' : ''} ago`;
    } else {
        return date.toLocaleDateString();
    }
}

/**
 * Setup notification dropdown
 */
function setupNotificationDropdown() {
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    
    notificationsDropdown.addEventListener('show.bs.dropdown', function() {
        loadNotifications();
    });
}

/**
 * Get current logged in user
 */
function getCurrentUser() {
    return JSON.parse(localStorage.getItem('currentUser') || '{}');
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return localStorage.getItem('currentUser') !== null;
}

/**
 * Update navbar profile
 */
function updateNavbarProfile() {
    const currentUser = getCurrentUser();
    
    if (currentUser && currentUser.username) {
        document.getElementById('navUsername').textContent = currentUser.username;
        
        // Set profile image based on user role or default
        const profileImg = document.getElementById('navProfileImg');
        if (currentUser.role === 'admin') {
            profileImg.src = 'img/admin-avatar.png';
        } else if (currentUser.role === 'teacher') {
            profileImg.src = 'img/teacher-avatar.png';
        } else {
            profileImg.src = 'img/user-avatar.png';
        }
    }
}

/**
 * Logout function
 */
function logout() {
    localStorage.removeItem('currentUser');
    window.location.href = 'index.html';
}

// Add demo notifications if none exist
function addDemoNotifications() {
    const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
    
    if (notifications.length === 0) {
        const currentUser = getCurrentUser();
        
        if (!currentUser || !currentUser.username) return;
        
        const demoNotifications = [
            {
                id: 'demo1',
                message: 'Welcome to Student Management System',
                timestamp: new Date().toISOString(),
                read: false,
                user: currentUser.username
            },
            {
                id: 'demo2',
                message: 'You can register new students and take attendance',
                timestamp: new Date(Date.now() - 3600000).toISOString(), // 1 hour ago
                read: false,
                user: currentUser.username
            }
        ];
        
        localStorage.setItem('notifications', JSON.stringify(demoNotifications));
        updateNotificationBadge();
    }
}

// Call this function after checking login
addDemoNotifications();