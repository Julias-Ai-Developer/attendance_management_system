<?php
include_once '../../includes/config.php';

if (isset($_POST['class'])) {
    $selected_class = $_POST['class'];
    $stmt = $conn->prepare("SELECT * FROM students WHERE class = ? AND deleted_at IS NULL ORDER BY id DESC");
    $stmt->bind_param("s", $selected_class);
    $stmt->execute();
    $result = $stmt->get_result();

    $sn = 1;
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['student_school_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
        echo '<td>';
        echo '<select class="form-select form-select-sm" name="attendance_status_' . htmlspecialchars($row['student_school_id']) . '">';
        echo '<option value="present">Present</option>';
        echo '<option value="absent">Absent</option>';
        echo '<option value="late">Late</option>';
        echo '<option value="excused">Excused</option>';
        echo '</select>';
        echo '</td>';
        echo '<td><input type="time" class="form-control form-control-sm" name="time_in_' . htmlspecialchars($row['student_school_id']) . '"></td>';
        echo '<td><input type="time" class="form-control form-control-sm" name="time_out_' . htmlspecialchars($row['student_school_id']) . '"></td>';
        echo '<td><input type="text" class="form-control form-control-sm" name="notes_' . htmlspecialchars($row['student_school_id']) . '"></td>';
        echo '</tr>';
    }
}
?>
