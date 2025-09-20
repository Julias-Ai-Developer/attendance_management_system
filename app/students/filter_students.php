<?php
// Include database connection
include_once '../../includes/config.php';

// Initialize response array
$response = [
    'status' => 'success',
    'message' => 'Filters applied successfully',
    'data' => [],
    'count' => 0
];

// Get filter parameters
$class = isset($_POST['class']) ? $_POST['class'] : 'all';
$status = isset($_POST['status']) ? $_POST['status'] : 'all';

// Build query with filters
$query = "SELECT s.*, c.class_name 
FROM students s
LEFT JOIN classes c ON s.class_id = c.id
WHERE s.deleted_at IS NULL";

// Apply class filter
if ($class !== 'all') {
    $query .= " AND s.class_id = '" . $conn->real_escape_string($class) . "'";
}

// Apply status filter
if ($status !== 'all') {
    $active = ($status === 'active') ? 1 : 0;
    $query .= " AND active = " . $active;
}

$query .= " ORDER BY id DESC";

// Execute query
$result = $conn->query($query);

if ($result) {
    $count = 0;
    $html_rows = [];
    
    while ($row = $result->fetch_assoc()) {
        $count++;
        $status_badge = $row['active'] ? 
            '<span class="badge bg-success">Active</span>' : 
            '<span class="badge bg-danger">Inactive</span>';
        
        $html_row = '<tr>
            <td>' . $count . '</td>
            <td>' . htmlspecialchars($row['student_school_id']) . '</td>
            <td>' . htmlspecialchars($row['full_name']) . '</td>
            <td>' . htmlspecialchars($row['class_name']) . '</td>
            <td>' . htmlspecialchars($row['gender']) . '</td>
            <td>' . htmlspecialchars($row['contact_number']) . '</td>
            <td>' . $status_badge . '</td>
            <td>
                <button class="btn btn-sm btn-primary edit-student" data-id="' . $row['id'] . '" data-bs-toggle="modal" data-bs-target="#editStudentModal">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-student" data-id="' . $row['id'] . '">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>';
        
        $html_rows[] = $html_row;
    }
    
    $response['data'] = $html_rows;
    $response['count'] = $count;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $conn->error;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;