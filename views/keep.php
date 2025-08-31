<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables Horizontal Alignment</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
    
    <style>
        /* Enhanced DataTables horizontal alignment */
        #studentsTable_wrapper .dataTables_wrapper .row {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            margin: 0 !important;
            padding: 0.75rem 0 !important;
            flex-wrap: wrap !important;
        }
        
        #studentsTable_wrapper .dataTables_length,
        #studentsTable_wrapper .dataTables_filter {
            display: flex !important;
            align-items: center !important;
            margin: 0 !important;
        }
        
        #studentsTable_wrapper .dataTables_length label,
        #studentsTable_wrapper .dataTables_filter label {
            display: flex !important;
            align-items: center !important;
            margin: 0 !important;
            white-space: nowrap !important;
            font-weight: normal !important;
        }
        
        #studentsTable_wrapper .dataTables_length select {
            margin-left: 0.5rem !important;
            margin-right: 0.5rem !important;
            width: auto !important;
            display: inline-block !important;
        }
        
        #studentsTable_wrapper .dataTables_filter input[type="search"] {
            margin-left: 0.5rem !important;
            width: auto !important;
            display: inline-block !important;
            min-width: 200px !important;
        }
        
        /* Override Bootstrap column classes */
        #studentsTable_wrapper .col-sm-12.col-md-6:first-child {
            flex: 0 0 auto !important;
            max-width: none !important;
            width: auto !important;
        }
        
        #studentsTable_wrapper .col-sm-12.col-md-6:last-child {
            flex: 0 0 auto !important;
            max-width: none !important;
            width: auto !important;
            margin-left: auto !important;
        }
        
        /* Alternative approach - force flexbox layout */
        .datatable-controls-flex {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            gap: 1rem !important;
            padding: 0.75rem 0 !important;
        }
        
        .datatable-controls-flex .dataTables_length,
        .datatable-controls-flex .dataTables_filter {
            display: flex !important;
            align-items: center !important;
            margin: 0 !important;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            #studentsTable_wrapper .dataTables_wrapper .row {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
            }
            
            #studentsTable_wrapper .col-sm-12.col-md-6:last-child {
                margin-left: 0 !important;
            }
            
            #studentsTable_wrapper .dataTables_filter input[type="search"] {
                min-width: 150px !important;
            }
            
            .datatable-controls-flex {
                flex-direction: column !important;
                align-items: stretch !important;
            }
        }
        
        /* Additional fixes for common issues */
        #studentsTable_wrapper .dataTables_wrapper {
            width: 100% !important;
        }
        
        /* Remove default margins that might interfere */
        #studentsTable_wrapper .dataTables_length,
        #studentsTable_wrapper .dataTables_filter,
        #studentsTable_wrapper .dataTables_info,
        #studentsTable_wrapper .dataTables_paginate {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* Demo styling */
        .container {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        
        .table {
            margin-top: 1rem;
        }
        
        .demo-section {
            margin-bottom: 3rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }
        
        .demo-title {
            color: #495057;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">DataTables Horizontal Alignment Solutions</h1>
        
        <div class="demo-section">
            <h3 class="demo-title">Solution 1: Enhanced CSS Targeting</h3>
            <p>This table uses the enhanced CSS that targets DataTables wrapper elements more specifically:</p>
            <table id="studentsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Grade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>John Doe</td><td>john@email.com</td><td>Mathematics</td><td>A</td><td>Active</td></tr>
                    <tr><td>Jane Smith</td><td>jane@email.com</td><td>Physics</td><td>B+</td><td>Active</td></tr>
                    <tr><td>Bob Johnson</td><td>bob@email.com</td><td>Chemistry</td><td>A-</td><td>Active</td></tr>
                    <tr><td>Alice Brown</td><td>alice@email.com</td><td>Biology</td><td>B</td><td>Active</td></tr>
                    <tr><td>Charlie Wilson</td><td>charlie@email.com</td><td>History</td><td>A</td><td>Active</td></tr>
                    <tr><td>Diana Davis</td><td>diana@email.com</td><td>Literature</td><td>B+</td><td>Active</td></tr>
                    <tr><td>Edward Miller</td><td>edward@email.com</td><td>Geography</td><td>A-</td><td>Active</td></tr>
                    <tr><td>Fiona Garcia</td><td>fiona@email.com</td><td>Art</td><td>B</td><td>Active</td></tr>
                </tbody>
            </table>
        </div>
        
        <div class="demo-section">
            <h3 class="demo-title">Solution 2: JavaScript-Enhanced Layout</h3>
            <p>This table uses JavaScript to force the correct layout structure:</p>
            <table id="studentsTable2" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Year</th>
                        <th>GPA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>001</td><td>Michael Anderson</td><td>Computer Science</td><td>3rd</td><td>3.8</td></tr>
                    <tr><td>002</td><td>Sarah Thompson</td><td>Engineering</td><td>2nd</td><td>3.6</td></tr>
                    <tr><td>003</td><td>David Lee</td><td>Business</td><td>4th</td><td>3.9</td></tr>
                    <tr><td>004</td><td>Emma Rodriguez</td><td>Medicine</td><td>1st</td><td>3.7</td></tr>
                    <tr><td>005</td><td>James White</td><td>Law</td><td>3rd</td><td>3.5</td></tr>
                    <tr><td>006</td><td>Lisa Johnson</td><td>Psychology</td><td>2nd</td><td>3.8</td></tr>
                </tbody>
            </table>
        </div>
        
        <div class="alert alert-info">
            <h5>Troubleshooting Tips:</h5>
            <ul class="mb-0">
                <li><strong>Use !important:</strong> DataTables applies its own CSS that might override your styles</li>
                <li><strong>Target specific wrapper:</strong> Use the table ID in your selectors for specificity</li>
                <li><strong>Check Bootstrap conflicts:</strong> Bootstrap's grid system might interfere</li>
                <li><strong>Use browser dev tools:</strong> Inspect the generated HTML structure</li>
                <li><strong>Load order matters:</strong> Your custom CSS should come after DataTables CSS</li>
            </ul>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize first table with standard options
            $('#studentsTable').DataTable({
                "pageLength": 5,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                "responsive": true,
                "autoWidth": false
            });
            
            // Initialize second table with enhanced layout correction
            const table2 = $('#studentsTable2').DataTable({
                "pageLength": 5,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                "responsive": true,
                "autoWidth": false,
                "initComplete": function() {
                    // Force correct layout structure after initialization
                    const wrapper = $('#studentsTable2_wrapper');
                    const topRow = wrapper.find('.row').first();
                    
                    // Add custom class for enhanced styling
                    topRow.addClass('datatable-controls-flex');
                    
                    // Ensure proper flex layout
                    setTimeout(() => {
                        topRow.css({
                            'display': 'flex',
                            'justify-content': 'space-between',
                            'align-items': 'center',
                            'flex-wrap': 'wrap'
                        });
                    }, 100);
                }
            });
        });
    </script>
</body>
</html>