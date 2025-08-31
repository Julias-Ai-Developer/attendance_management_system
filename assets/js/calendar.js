// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get current user
    const currentUser = getCurrentUser();
    if (!currentUser) return;
    
    // Update navbar profile
    updateNavbarProfile(currentUser);
    
    // Load notifications
    loadNotifications();
    
    // Setup dynamic notification dropdown
    setupNotificationDropdown();
    
    // Initialize calendar with current month
    const today = new Date();
    generateCalendar(today.getFullYear(), today.getMonth(), currentUser.id);
    
    // Add event listeners for month navigation
    document.getElementById('prevMonth').addEventListener('click', function() {
        const [year, month] = document.getElementById('currentMonthYear').dataset.date.split('-');
        let prevMonth = parseInt(month) - 1;
        let prevYear = parseInt(year);
        
        if (prevMonth < 0) {
            prevMonth = 11;
            prevYear--;
        }
        
        generateCalendar(prevYear, prevMonth, currentUser.id);
    });
    
    document.getElementById('nextMonth').addEventListener('click', function() {
        const [year, month] = document.getElementById('currentMonthYear').dataset.date.split('-');
        let nextMonth = parseInt(month) + 1;
        let nextYear = parseInt(year);
        
        if (nextMonth > 11) {
            nextMonth = 0;
            nextYear++;
        }
        
        generateCalendar(nextYear, nextMonth, currentUser.id);
    });
});

// Update navbar profile information
function updateNavbarProfile(user) {
    document.getElementById('navUsername').textContent = user.name;
    document.getElementById('navProfileImg').src = user.profileImage;
    
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

// Load notifications from localStorage
function loadNotifications() {
    const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
    const unreadCount = notifications.filter(n => !n.read).length;
    
    // Update notification badge
    const badge = document.getElementById('notificationBadge');
    badge.textContent = unreadCount;
    badge.style.display = unreadCount > 0 ? 'block' : 'none';
    
    // Clear existing notifications
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
        const noNotifications = document.createElement('li');
        noNotifications.innerHTML = '<span class="dropdown-item text-muted">No notifications</span>';
        notificationsList.appendChild(noNotifications);
    } else {
        notifications.forEach(notification => {
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
            notificationLink.addEventListener('click', function() {
                markNotificationAsRead(notification.id);
            });
            notificationItem.appendChild(notificationLink);
            notificationsList.appendChild(notificationItem);
        });
    }
}

// Mark notification as read
function markNotificationAsRead(notificationId) {
    const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
    const updatedNotifications = notifications.map(notification => {
        if (notification.id === notificationId) {
            return { ...notification, read: true };
        }
        return notification;
    });
    
    localStorage.setItem('notifications', JSON.stringify(updatedNotifications));
    loadNotifications();
}

// Add dynamic notification dropdown toggle
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
    if (window.location.search.includes('demo=true')) {
        setInterval(function() {
            addDemoNotification();
        }, 30000);
    }
}

// Add a demo notification for testing
function addDemoNotification() {
    const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
    const newNotification = {
        id: 'notification_' + Date.now(),
        message: 'New calendar event added',
        date: new Date().toISOString(),
        read: false
    };
    
    notifications.unshift(newNotification);
    localStorage.setItem('notifications', JSON.stringify(notifications));
    loadNotifications();
}

// Generate calendar for a specific month and year
function generateCalendar(year, month, userId) {
    // Update month and year display
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const monthYearElement = document.getElementById('currentMonthYear');
    monthYearElement.textContent = `${monthNames[month]} ${year}`;
    monthYearElement.dataset.date = `${year}-${month}`;
    
    // Get attendance records for the user
    const attendanceRecords = JSON.parse(localStorage.getItem('attendanceRecords')) || [];
    
    // Get first day of the month and total days in month
    const firstDay = new Date(year, month, 1).getDay(); // 0 = Sunday, 1 = Monday, etc.
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    
    // Generate calendar grid
    const calendarBody = document.getElementById('calendarBody');
    calendarBody.innerHTML = '';
    
    let date = 1;
    for (let i = 0; i < 6; i++) { // Maximum 6 rows for a month
        // Create a row
        const row = document.createElement('tr');
        
        for (let j = 0; j < 7; j++) { // 7 days in a week
            const cell = document.createElement('td');
            cell.className = 'calendar-day';
            
            if (i === 0 && j < firstDay) {
                // Empty cells before the first day of the month
                cell.innerHTML = '';
            } else if (date > daysInMonth) {
                // Empty cells after the last day of the month
                cell.innerHTML = '';
            } else {
                // Valid day cell
                const currentDate = new Date(year, month, date);
                const dateString = currentDate.toISOString().split('T')[0];
                
                // Find attendance record for this date
                const record = attendanceRecords.find(r => 
                    r.userId === userId && r.date === dateString
                );
                
                // Create day cell content
                const dayContent = document.createElement('div');
                dayContent.className = 'd-flex justify-content-between align-items-start';
                
                const dayNumber = document.createElement('span');
                dayNumber.textContent = date;
                dayContent.appendChild(dayNumber);
                
                // Add status indicator if record exists
                if (record) {
                    const statusIndicator = document.createElement('span');
                    statusIndicator.className = `badge ${getStatusBadgeClass(record.status)}`;
                    statusIndicator.textContent = record.status.charAt(0).toUpperCase() + record.status.slice(1);
                    dayContent.appendChild(statusIndicator);
                    
                    // Add status class to cell
                    cell.classList.add(record.status);
                    
                    // Add click event to show details
                    cell.addEventListener('click', function() {
                        showAttendanceDetails(record);
                    });
                    cell.style.cursor = 'pointer';
                }
                
                cell.appendChild(dayContent);
                
                // Add time information if available
                if (record && record.timeIn) {
                    const timeInfo = document.createElement('div');
                    timeInfo.className = 'small text-muted mt-2';
                    timeInfo.textContent = `In: ${record.timeIn || 'N/A'}`;
                    cell.appendChild(timeInfo);
                }
                
                // Highlight today's date
                const today = new Date();
                if (date === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                    cell.classList.add('bg-light');
                    cell.style.fontWeight = 'bold';
                }
                
                date++;
            }
            
            row.appendChild(cell);
        }
        
        calendarBody.appendChild(row);
        
        // Stop if we've reached the end of the month
        if (date > daysInMonth) {
            break;
        }
    }
    
    // Show toast
    showToast(`Calendar for ${monthNames[month]} ${year} loaded`);
}

// Get status badge class
function getStatusBadgeClass(status) {
    const badgeClasses = {
        present: 'bg-success',
        absent: 'bg-danger',
        late: 'bg-warning text-dark',
        excused: 'bg-info text-dark'
    };
    
    return badgeClasses[status] || 'bg-light text-dark';
}

// Show attendance details in modal
function showAttendanceDetails(record) {
    // Format date
    const date = new Date(record.date);
    const formattedDate = date.toLocaleDateString('en-US', { 
        weekday: 'long',
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    // Calculate duration if time in and time out are available
    let duration = 'N/A';
    if (record.timeIn && record.timeOut) {
        duration = calculateDuration(record.timeIn, record.timeOut);
    }
    
    // Update modal content
    document.getElementById('modalDate').textContent = formattedDate;
    document.getElementById('modalStatus').innerHTML = `<span class="badge ${getStatusBadgeClass(record.status)}">${record.status.charAt(0).toUpperCase() + record.status.slice(1)}</span>`;
    document.getElementById('modalTimeIn').textContent = record.timeIn || 'N/A';
    document.getElementById('modalTimeOut').textContent = record.timeOut || 'N/A';
    document.getElementById('modalDuration').textContent = duration;
    document.getElementById('modalNotes').textContent = record.notes || 'No notes';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('attendanceDetailsModal'));
    modal.show();
}

// Calculate duration between time in and time out
function calculateDuration(timeIn, timeOut) {
    const [inHour, inMinute] = timeIn.split(':').map(Number);
    const [outHour, outMinute] = timeOut.split(':').map(Number);
    
    let durationHours = outHour - inHour;
    let durationMinutes = outMinute - inMinute;
    
    if (durationMinutes < 0) {
        durationHours--;
        durationMinutes += 60;
    }
    
    return `${durationHours}h ${durationMinutes}m`;
}

// Show toast notification
function showToast(message) {
    const toastEl = document.getElementById('notificationToast');
    const toast = new bootstrap.Toast(toastEl);
    
    document.getElementById('toastMessage').textContent = message;
    document.getElementById('toastTime').textContent = 'just now';
    
    toast.show();
}

// Format date for notifications
function formatDate(date) {
    const now = new Date();
    const diffMs = now - date;
    const diffSec = Math.round(diffMs / 1000);
    const diffMin = Math.round(diffSec / 60);
    const diffHour = Math.round(diffMin / 60);
    const diffDay = Math.round(diffHour / 24);
    
    if (diffSec < 60) {
        return 'just now';
    } else if (diffMin < 60) {
        return `${diffMin} minute${diffMin > 1 ? 's' : ''} ago`;
    } else if (diffHour < 24) {
        return `${diffHour} hour${diffHour > 1 ? 's' : ''} ago`;
    } else if (diffDay < 7) {
        return `${diffDay} day${diffDay > 1 ? 's' : ''} ago`;
    } else {
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    }
}