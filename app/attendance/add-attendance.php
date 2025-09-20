<?php
// Include database connection
include_once '../../includes/config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the date
    $attendance_date = isset($_POST['attendance_date']) ? $_POST['attendance_date'] : date('Y-m-d');
    $class = isset($_POST['class']) ? $_POST['class'] : '';

    // Initialize response array
    $response = [
        'status' => 'error',
        'message' => 'An error occurred while saving attendance'
    ];

    // Validate class
    if (empty($class)) {
        $response['message'] = 'Class is required';
        echo json_encode($response);
        exit;
    }

    // Get student data from POST
    $student_ids = isset($_POST['student_id']) ? $_POST['student_id'] : [];
    $statuses = isset($_POST['status']) ? $_POST['status'] : [];
    $time_ins = isset($_POST['time_in']) ? $_POST['time_in'] : [];
    $time_outs = isset($_POST['time_out']) ? $_POST['time_out'] : [];
    $notes = isset($_POST['notes']) ? $_POST['notes'] : [];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Loop through each student
        for ($i = 0; $i < count($student_ids); $i++) {
            $student_id = $student_ids[$i];
            $status = $statuses[$i] ?? 'absent';
            $time_in = !empty($time_ins[$i]) ? $time_ins[$i] : null;
            $time_out = !empty($time_outs[$i]) ? $time_outs[$i] : null;
            $note = $notes[$i] ?? '';

            // Check if attendance record already exists for this student on this date
            $check_query = "SELECT id FROM attendance WHERE student_id = ? AND attendance_date = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("ss", $student_id, $attendance_date);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                // Update existing record
                $attendance_id = $check_result->fetch_assoc()['id'];
                $update_query = "UPDATE attendance SET 
                                status = ?, 
                                time_in = ?, 
                                time_out = ?, 
                                notes = ?,
                                updated_at = NOW() 
                                WHERE id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("ssssi", $status, $time_in, $time_out, $note, $attendance_id);
                $update_stmt->execute();
            } else {
                // Insert new record
                $insert_query = "INSERT INTO attendance (student_id, attendance_date, class, status, time_in, time_out, notes, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("sssssss", $student_id, $attendance_date, $class, $status, $time_in, $time_out, $note);
                $insert_stmt->execute();
            }
        }

        // Commit transaction
        $conn->commit();

        // Set success message in session for toast
        session_start();
        $_SESSION['toast_message'] = 'Attendance saved successfully';
        $_SESSION['toast_type'] = 'success';
        
        // Redirect to attendance page
        header('Location: ../../views/attendance.php');
        exit;
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        // Set error message in session for toast
        session_start();
        $_SESSION['toast_message'] = 'Error: ' . $e->getMessage();
        $_SESSION['toast_type'] = 'error';
        
        // Redirect back to attendance page
        header('Location: ../../views/attendance.php');
        exit;
    }
}

// If not a POST request, redirect to the students page
header('Location: ../../views/students.php');
exit;
