// Only frontend toast notification logic for students.php
// Usage: showToast('Your message', 'success'|'error'|'warning');

function showToast(message, type = 'success') {
    const toast = document.getElementById('notificationToast');
    const toastMessage = document.getElementById('toastMessage');
    const toastTime = document.getElementById('toastTime');

    // Set message and style
    toastMessage.textContent = message;
    toastMessage.className = 'toast-body';
    if (type === 'error') {
        toastMessage.classList.add('bg-danger', 'text-white');
    } else if (type === 'warning') {
        toastMessage.classList.add('bg-warning');
    } else {
        toastMessage.classList.add('bg-success', 'text-white');
    }

    // Set time
    toastTime.textContent = 'just now';

    // Show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

// Example: Show toast when 'Apply Filters' button is clicked
document.addEventListener('DOMContentLoaded', function() {
    var applyFiltersBtn = document.getElementById('applyFilters');
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            showToast('Filters applied successfully!', 'success');
        });
    }

    // Show real attendance rows when 'Load Students' is clicked
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

    // Initialize DataTables for students table
    if (window.jQuery && $('#studentsTable').length) {
        $('#studentsTable').DataTable({
            responsive: true,
            order: [[0, 'asc']], // Sort by S/N column (first column) in descending order
            language: {
                emptyTable: "No students found"
            },
            columnDefs: [
                { targets: -1, orderable: false } // Disable sorting on Actions column
            ]
        });
    }
});
// Only frontend toast notification logic for students.php
// Usage: showToast('Your message', 'success'|'error'|'warning');

function showToast(message, type = 'success') {
    const toast = document.getElementById('notificationToast');
    const toastMessage = document.getElementById('toastMessage');
    const toastTime = document.getElementById('toastTime');

    // Set message and style
    toastMessage.textContent = message;
    toastMessage.className = 'toast-body';
    if (type === 'error') {
        toastMessage.classList.add('bg-danger', 'text-white');
    } else if (type === 'warning') {
        toastMessage.classList.add('bg-warning');
    } else {
        toastMessage.classList.add('bg-success', 'text-white');
    }

    // Set time
    toastTime.textContent = 'just now';

    // Show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}