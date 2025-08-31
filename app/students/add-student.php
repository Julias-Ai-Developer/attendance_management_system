<?php
// add-student.php
// Handles adding a new student from the Add Student modal form

session_start();
require_once '../../includes/config.php';

// First, check if the request method is POST. This is a best practice to ensure
// the script only runs when a form is submitted.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Next, check if the specific submit button was pressed.
    if (isset($_POST['submit'])) {

        // Collect and sanitize form data from the $_POST superglobal.
    // htmlspecialchars() is used to prevent XSS attacks. mysqli_real_escape_string for SQL safety.
    $full_name = isset($_POST['studentName']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['studentName']))) : '';
    $student_school_id = isset($_POST['studentId']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['studentId']))) : '';
    $class = isset($_POST['studentClass']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['studentClass']))) : '';
    $gender = isset($_POST['studentGender']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['studentGender']))) : '';
    $phone_number = isset($_POST['studentContact']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['studentContact']))) : '';
    $status = '1';
    $email = isset($_POST['studentEmail']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['studentEmail']))) : '';
    $parent_name = isset($_POST['parentName']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['parentName']))) : '';
    $parent_phone_number = isset($_POST['parentContact']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['parentContact']))) : '';
    $address = isset($_POST['studentAddress']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['studentAddress']))) : '';

        // TODO: Add more input validation here (e.g., check for empty fields, valid email format).

        // Prepare and execute the SQL insert query using prepared statements.
        // This is crucial for preventing SQL injection.
        $stmt = $conn->prepare("INSERT INTO students (full_name, student_school_id, class, gender, phone_number, status, email, parent_name, parent_phone_number, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $full_name, $student_school_id, $class, $gender, $phone_number, $status, $email, $parent_name, $parent_phone_number, $address);
        
        if ($stmt->execute()) {
            $_SESSION['toast_message'] = 'Student added successfully!';
        } else {
            $_SESSION['toast_message'] = 'Error adding student: ' . $conn->error;
        }
        
        // Close the statement and the database connection to free up resources.
        $stmt->close();
        $conn->close();

        // Redirect the user back to the students page after processing the form.
        $_SESSION['toast_message'] = 'Student added successfully!';
        header('Location: ../../views/students.php');
        exit();

    } else {
        // This block handles cases where the POST request didn't come from
        // an expected form button, which can indicate an issue.
        $_SESSION['toast_message'] = 'Invalid form submission.';
        header('Location: ../../views/students.php');
        exit();
    }
} else {
    // If the page is accessed directly via a GET request, redirect to the students page.
    header('Location: ../../views/students.php');
    exit();
}

?>
