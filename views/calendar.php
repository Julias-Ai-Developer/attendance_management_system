<?php include_once '../includes/topnav.php'?>
    <title>Attendance Calendar</title>
    
    
</head>
<body>
    

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Attendance Calendar</h5>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-outline-light me-2" id="prevMonth">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span id="currentMonthYear" class="mx-3 fw-semibold">Month Year</span>
                            <button class="btn btn-sm btn-outline-light ms-2" id="nextMonth">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Calendar Legend -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-center flex-wrap">
                                    <div class="me-3 mb-2">
                                        <span class="badge bg-success">
                                            <span class="status-indicator bg-white"></span>Present
                                        </span>
                                    </div>
                                    <div class="me-3 mb-2">
                                        <span class="badge bg-danger">
                                            <span class="status-indicator bg-white"></span>Absent
                                        </span>
                                    </div>
                                    <div class="me-3 mb-2">
                                        <span class="badge bg-warning text-dark">
                                            <span class="status-indicator bg-dark"></span>Late
                                        </span>
                                    </div>
                                    <div class="me-3 mb-2">
                                        <span class="badge bg-info text-dark">
                                            <span class="status-indicator bg-dark"></span>Excused
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark">
                                            <span class="status-indicator bg-secondary"></span>No Record
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
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

    <!-- Footer -->
    <div class="footer-sticky">
        <div class="text-muted">
            <i class="fas fa-calendar-check me-2"></i>
            © 2024 AttendanceTracker. All rights reserved.
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Calendar functionality
        class AttendanceCalendar {
            constructor() {
                this.currentDate = new Date();
                this.currentYear = this.currentDate.getFullYear();
                this.currentMonth = this.currentDate.getMonth();
                this.today = new Date();
                
                // Sample attendance data - in a real app, this would come from your backend
                this.attendanceData = this.generateSampleData();
                
                this.init();
            }

            init() {
                this.bindEvents();
                this.renderCalendar();
            }

            bindEvents() {
                document.getElementById('prevMonth').addEventListener('click', () => {
                    this.currentMonth--;
                    if (this.currentMonth < 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    }
                    this.renderCalendar();
                });

                document.getElementById('nextMonth').addEventListener('click', () => {
                    this.currentMonth++;
                    if (this.currentMonth > 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    }
                    this.renderCalendar();
                });
            }

            generateSampleData() {
                const data = {};
                const statuses = ['present', 'absent', 'late', 'excused'];
                const now = new Date();
                
                // Generate data for current month and previous month
                for (let monthOffset = -1; monthOffset <= 1; monthOffset++) {
                    const targetDate = new Date(now.getFullYear(), now.getMonth() + monthOffset, 1);
                    const year = targetDate.getFullYear();
                    const month = targetDate.getMonth();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();

                    for (let day = 1; day <= daysInMonth; day++) {
                        const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        
                        // Skip future dates and weekends for more realistic data
                        const date = new Date(year, month, day);
                        if (date > now || date.getDay() === 0 || date.getDay() === 6) continue;
                        
                        // 80% chance of having attendance data
                        if (Math.random() > 0.2) {
                            const status = statuses[Math.floor(Math.random() * statuses.length)];
                            data[dateKey] = {
                                status: status,
                                timeIn: status !== 'absent' ? this.generateRandomTime(8, 9) : null,
                                timeOut: status !== 'absent' ? this.generateRandomTime(17, 18) : null,
                                duration: status !== 'absent' ? this.calculateDuration() : null,
                                notes: this.generateNotes(status)
                            };
                        }
                    }
                }
                
                return data;
            }

            generateRandomTime(startHour, endHour) {
                const hour = startHour + Math.floor(Math.random() * (endHour - startHour));
                const minute = Math.floor(Math.random() * 60);
                return `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
            }

            calculateDuration() {
                const hours = 8 + Math.floor(Math.random() * 2);
                const minutes = Math.floor(Math.random() * 60);
                return `${hours}h ${minutes}m`;
            }

            generateNotes(status) {
                const notes = {
                    'present': ['On time', 'Good attendance', ''],
                    'absent': ['Sick leave', 'Personal leave', 'Medical appointment'],
                    'late': ['Traffic jam', 'Overslept', 'Emergency'],
                    'excused': ['Approved leave', 'Company meeting', 'Training']
                };
                const statusNotes = notes[status] || [''];
                return statusNotes[Math.floor(Math.random() * statusNotes.length)];
            }

            renderCalendar() {
                const monthNames = [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];

                // Update header
                document.getElementById('currentMonthYear').textContent = 
                    `${monthNames[this.currentMonth]} ${this.currentYear}`;

                // Clear previous calendar
                const calendarBody = document.getElementById('calendarBody');
                calendarBody.innerHTML = '';

                // Calculate first day of month and number of days
                const firstDay = new Date(this.currentYear, this.currentMonth, 1);
                const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDayOfWeek = firstDay.getDay();

                // Calculate days from previous month
                const prevMonth = new Date(this.currentYear, this.currentMonth, 0);
                const daysInPrevMonth = prevMonth.getDate();

                let date = 1;
                let nextMonthDate = 1;

                // Create calendar rows
                for (let week = 0; week < 6; week++) {
                    const row = document.createElement('tr');
                    
                    // Create cells for each day of the week
                    for (let dayOfWeek = 0; dayOfWeek < 7; dayOfWeek++) {
                        const cell = document.createElement('td');
                        cell.className = 'calendar-day p-0';
                        
                        let cellDate, cellMonth, cellYear, isCurrentMonth = true;
                        
                        if (week === 0 && dayOfWeek < startingDayOfWeek) {
                            // Previous month's days
                            cellDate = daysInPrevMonth - startingDayOfWeek + dayOfWeek + 1;
                            cellMonth = this.currentMonth === 0 ? 11 : this.currentMonth - 1;
                            cellYear = this.currentMonth === 0 ? this.currentYear - 1 : this.currentYear;
                            cell.classList.add('other-month');
                            isCurrentMonth = false;
                        } else if (date > daysInMonth) {
                            // Next month's days
                            cellDate = nextMonthDate++;
                            cellMonth = this.currentMonth === 11 ? 0 : this.currentMonth + 1;
                            cellYear = this.currentMonth === 11 ? this.currentYear + 1 : this.currentYear;
                            cell.classList.add('other-month');
                            isCurrentMonth = false;
                        } else {
                            // Current month's days
                            cellDate = date++;
                            cellMonth = this.currentMonth;
                            cellYear = this.currentYear;
                        }

                        // Check if it's today
                        if (cellYear === this.today.getFullYear() && 
                            cellMonth === this.today.getMonth() && 
                            cellDate === this.today.getDate()) {
                            cell.classList.add('today');
                        }

                        // Create date string for lookup
                        const dateKey = `${cellYear}-${String(cellMonth + 1).padStart(2, '0')}-${String(cellDate).padStart(2, '0')}`;
                        
                        // Get attendance data
                        const attendanceRecord = this.attendanceData[dateKey];
                        
                        // Build cell content
                        let cellContent = `<div class="day-number">${cellDate}</div>`;
                        
                        if (attendanceRecord && isCurrentMonth) {
                            cell.classList.add(attendanceRecord.status);
                            cellContent += `<div class="attendance-status badge bg-${this.getStatusColor(attendanceRecord.status)}">${attendanceRecord.status}</div>`;
                            
                            // Add click event for modal
                            cell.style.cursor = 'pointer';
                            cell.addEventListener('click', () => {
                                this.showAttendanceDetails(dateKey, attendanceRecord);
                            });
                        }
                        
                        cell.innerHTML = cellContent;
                        row.appendChild(cell);
                    }
                    
                    calendarBody.appendChild(row);
                    
                    // Break if we've filled all days and started next month
                    if (date > daysInMonth && nextMonthDate > 7) {
                        break;
                    }
                }
            }

            getStatusColor(status) {
                const colors = {
                    'present': 'success',
                    'absent': 'danger',
                    'late': 'warning',
                    'excused': 'info'
                };
                return colors[status] || 'secondary';
            }

            showAttendanceDetails(dateKey, record) {
                const [year, month, day] = dateKey.split('-');
                const date = new Date(year, month - 1, day);
                
                document.getElementById('modalDate').textContent = date.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                document.getElementById('modalStatus').innerHTML = 
                    `<span class="badge bg-${this.getStatusColor(record.status)}">${record.status.toUpperCase()}</span>`;
                document.getElementById('modalTimeIn').textContent = record.timeIn || 'N/A';
                document.getElementById('modalTimeOut').textContent = record.timeOut || 'N/A';
                document.getElementById('modalDuration').textContent = record.duration || 'N/A';
                document.getElementById('modalNotes').textContent = record.notes || 'No notes';
                
                const modal = new bootstrap.Modal(document.getElementById('attendanceDetailsModal'));
                modal.show();
            }

            showToast(message, type = 'info') {
                const toast = document.getElementById('notificationToast');
                const toastMessage = document.getElementById('toastMessage');
                const toastTime = document.getElementById('toastTime');
                
                toastMessage.textContent = message;
                toastTime.textContent = 'just now';
                
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }
        }

        // Initialize calendar when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new AttendanceCalendar();
        });
    </script>
<?php include_once '../includes/footer.php'?>