<?php
include_once '../../includes/config.php';

// Debug information
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple response for debugging
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_class = isset($_POST['class']) ? $_POST['class'] : '';
    $selected_date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
    
    if (empty($selected_class)) {
        echo '<tr><td colspan="6" class="text-center">No class selected</td></tr>';
        exit;
    }
    
    // Get students from the selected class
    $stmt = $conn->prepare("SELECT * FROM students WHERE class = ? AND deleted_at IS NULL ORDER BY full_name ASC");
    $stmt->bind_param("s", $selected_class);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any students
    if ($result->num_rows === 0) {
        echo '<tr><td colspan="6" class="text-center">No students found in this class</td></tr>';
    } else {
        while ($row = $result->fetch_assoc()) {
            $student_id = htmlspecialchars($row['student_school_id']);
            
            // Check if attendance already exists for this student on this date
            $att_stmt = $conn->prepare("SELECT * FROM attendance WHERE student_id = ? AND attendance_date = ?");
            $att_stmt->bind_param("ss", $student_id, $selected_date);
            $att_stmt->execute();
            $att_result = $att_stmt->get_result();
            $attendance = $att_result->fetch_assoc();
            
            // Default values
            $status = $attendance ? $attendance['status'] : 'present';
            $time_in = $attendance ? $attendance['time_in'] : '';
            $time_out = $attendance ? $attendance['time_out'] : '';
            $notes = $attendance ? $attendance['notes'] : '';
            
            echo '<tr>';
            echo '<input type="hidden" name="student_id[]" value="' . $student_id . '">';
            echo '<td>' . $student_id . '</td>';
            echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
            echo '<td>';
            echo '<select class="form-select form-select-sm" name="status[]">';
            echo '<option value="present" ' . ($status == 'present' ? 'selected' : '') . '>Present</option>';
            echo '<option value="absent" ' . ($status == 'absent' ? 'selected' : '') . '>Absent</option>';
            echo '<option value="late" ' . ($status == 'late' ? 'selected' : '') . '>Late</option>';
            echo '<option value="excused" ' . ($status == 'excused' ? 'selected' : '') . '>Excused</option>';
            echo '</select>';
            echo '</td>';
            echo '<td><input type="time" class="form-control form-control-sm" name="time_in[]" value="' . $time_in . '"></td>';
            echo '<td><input type="time" class="form-control form-control-sm" name="time_out[]" value="' . $time_out . '"></td>';
            echo '<td><input type="text" class="form-control form-control-sm" name="notes[]" value="' . $notes . '"></td>';
            echo '</tr>';
        }
    }
} else {
    echo '<tr><td colspan="6" class="text-center">Please select a class and date</td></tr>';
}
?>
