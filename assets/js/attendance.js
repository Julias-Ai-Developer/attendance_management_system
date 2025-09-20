// Function to load students for attendance
function loadStudentsForAttendance() {
    console.log('loadStudentsForAttendance function called');
    const selectedClass = $('#attendanceClass').val();
    const selectedDate = $('#attendanceDate').val() || new Date().toISOString().split('T')[0];
    
    if (!selectedClass) {
        alert('Please select a class');
        return;
    }
    
    console.log('Loading students for class:', selectedClass, 'date:', selectedDate);
    
    // Set hidden form values
    $('#attendance_date').val(selectedDate);
    $('#class').val(selectedClass);
    
    // Show loading indicator
    $('#attendanceTableBody').html('<tr><td colspan="6" class="text-center">Loading students...</td></tr>');
    
    // Load students via AJAX
    $.ajax({
        url: '../app/attendance/load_students_by_class.php',
        type: 'POST',
        data: {
            class: selectedClass,
            date: selectedDate
        },
        success: function(response) {
            console.log('AJAX Success');
            $('#attendanceTableBody').html(response);
            $('#attendanceForm').removeClass('d-none');
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr, status, error);
            alert('Error loading students: ' + error);
        }
    });
}

$(document).ready(function() {
    console.log('Attendance JS loaded');
    
    // Initialize with current date
    const today = new Date().toISOString().split('T')[0];
    $('#attendanceDate').val(today);
    
    // Populate year dropdown
    const currentYear = new Date().getFullYear();
    const yearSelect = $('#yearFilter');
    yearSelect.append(`<option value="all">All Years</option>`);
    for (let year = currentYear; year >= currentYear - 5; year--) {
        yearSelect.append(`<option value="${year}">${year}</option>`);
    }
    yearSelect.val(currentYear); // Set current year as default
    
    // Apply filters when button is clicked
    $('#applyFilters').on('click', function() {
        const month = $('#monthFilter').val();
        const year = $('#yearFilter').val();
        const status = $('#statusFilter').val();
        
        // Show loading indicator
        $('tbody').html('<tr><td colspan="8" class="text-center">Loading records...</td></tr>');
        
        // Apply filters via AJAX
        $.ajax({
            url: '../app/attendance/filter_attendance.php',
            type: 'POST',
            data: {
                month: month,
                year: year,
                status: status
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Update table
                    if (response.data.length > 0) {
                        $('tbody').html(response.data.join(''));
                    } else {
                        $('tbody').html('<tr><td colspan="8" class="text-center">No records found</td></tr>');
                    }
                    
                    // Update summary
                    $('#summaryPresent').text(response.summary.present);
                    $('#summaryAbsent').text(response.summary.absent);
                    $('#summaryLate').text(response.summary.late);
                    $('#summaryExcused').text(response.summary.excused);
                    
                    // Show toast notification
                    $('#toastMessage').text('Filters applied successfully. Found ' + response.count + ' records.');
                    $('#toastMessage').removeClass().addClass('toast-body bg-success text-white');
                    $('#toastTime').text('just now');
                    var toast = new bootstrap.Toast(document.getElementById('notificationToast'));
                    toast.show();
                } else {
                    // Show error toast
                    $('#toastMessage').text('Error applying filters: ' + response.message);
                    $('#toastMessage').removeClass().addClass('toast-body bg-danger text-white');
                    $('#toastTime').text('just now');
                    var toast = new bootstrap.Toast(document.getElementById('notificationToast'));
                    toast.show();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr, status, error);
                // Show error toast
                $('#toastMessage').text('Error applying filters: ' + error);
                $('#toastMessage').removeClass().addClass('toast-body bg-danger text-white');
                $('#toastTime').text('just now');
                var toast = new bootstrap.Toast(document.getElementById('notificationToast'));
                toast.show();
            }
        });
    });
    
    // Load students for attendance when button is clicked
    $(document).on('click', '#loadStudentsForAttendance', function() {
        console.log('Load students button clicked');
        const selectedClass = $('#attendanceClass').val();
        const selectedDate = $('#attendanceDate').val() || today;
        
        if (!selectedClass) {
            alert('Please select a class');
            return;
        }
        
        console.log('Loading students for class:', selectedClass, 'date:', selectedDate);
        
        // Set hidden form values
        $('#attendance_date').val(selectedDate);
        $('#class').val(selectedClass);
        
        // Show loading indicator
        $('#attendanceTableBody').html('<tr><td colspan="6" class="text-center">Loading students...</td></tr>');
        
        // Load students via AJAX
        $.ajax({
            url: '../app/attendance/load_students_by_class.php',
            type: 'POST',
            data: {
                class: selectedClass,
                date: selectedDate
            },
            success: function(response) {
                console.log('AJAX Success');
                $('#attendanceTableBody').html(response);
                $('#attendanceForm').removeClass('d-none');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr, status, error);
                alert('Error loading students: ' + error);
            }
        });
    });
    
    // Handle attendance form submission
    $('#attendanceForm').on('submit', function(e) {
        // 不阻止表单提交，让表单正常提交并由 PHP 处理重定向
        // 表单提交后，PHP 将设置 session 消息并重定向到 attendance.php
    });
});