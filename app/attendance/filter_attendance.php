<?php
include_once '../../includes/config.php';

// Get filter parameters
$month = isset($_POST['month']) ? $_POST['month'] : 'all';
$year = isset($_POST['year']) ? $_POST['year'] : date('Y');
$status = isset($_POST['status']) ? $_POST['status'] : 'all';

// Prepare the response
$response = [
    'status' => 'success',
    'message' => 'Filters applied successfully',
    'data' => [],
    'count' => 0,
    'summary' => [
        'present' => 0,
        'absent' => 0,
        'late' => 0,
        'excused' => 0
    ]
];

// Build the query
$query = "
    SELECT a.*, s.full_name 
    FROM attendance a
    JOIN students s ON a.student_id = s.student_school_id
    WHERE a.deleted_at IS NULL
";

// Add filters
$params = [];
$types = "";

// Month filter
if ($month !== 'all') {
    $query .= " AND MONTH(a.attendance_date) = ?";
    $params[] = $month + 1; // JavaScript months are 0-based
    $types .= "i";
}

// Year filter
if ($year !== 'all') {
    $query .= " AND YEAR(a.attendance_date) = ?";
    $params[] = $year;
    $types .= "i";
}

// Status filter
if ($status !== 'all') {
    $query .= " AND a.status = ?";
    $params[] = $status;
    $types .= "s";
}

// Order by
$query .= " ORDER BY a.attendance_date DESC, s.full_name ASC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Process results
if ($result->num_rows > 0) {
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $count++;
        // Determine badge color based on status
        $badge_class = 'bg-secondary';
        if ($row['status'] == 'present') {
            $badge_class = 'bg-success';
            $response['summary']['present']++;
        } elseif ($row['status'] == 'absent') {
            $badge_class = 'bg-danger';
            $response['summary']['absent']++;
        } elseif ($row['status'] == 'late') {
            $badge_class = 'bg-warning text-dark';
            $response['summary']['late']++;
        } elseif ($row['status'] == 'excused') {
            $badge_class = 'bg-info';
            $response['summary']['excused']++;
        }
        
        // Format time
        $time_in = $row['time_in'] ? date('h:i A', strtotime($row['time_in'])) : '-';
        $time_out = $row['time_out'] ? date('h:i A', strtotime($row['time_out'])) : '-';
        
        // Build the HTML row
        $html_row = '<tr>';
        $html_row .= '<td>' . $count . '</td>';
        $html_row .= '<td>' . date('Y-m-d', strtotime($row['attendance_date'])) . '</td>';
        $html_row .= '<td>' . htmlspecialchars($row['student_id']) . '</td>';
        $html_row .= '<td>' . htmlspecialchars($row['full_name']) . '</td>';
        $html_row .= '<td>' . htmlspecialchars($row['class']) . '</td>';
        $html_row .= '<td><span class="badge ' . $badge_class . '">' . ucfirst($row['status']) . '</span></td>';
        $html_row .= '<td>' . $time_in . '</td>';
        $html_row .= '<td>' . $time_out . '</td>';
        $html_row .= '<td>' . htmlspecialchars($row['notes']) . '</td>';
        $html_row .= '<td>
            <a href="edit-attendance.php?id=' . $row['id'] . '" class="btn btn-sm btn-primary">
                <i class="fas fa-edit"></i>
            </a>
            <a href="../app/attendance/delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\')">
                <i class="fas fa-trash"></i>
            </a>
        </td>';
        $html_row .= '</tr>';
        
        $response['data'][] = $html_row;
    }
    $response['count'] = $count;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>