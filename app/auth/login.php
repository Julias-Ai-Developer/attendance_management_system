<?php
// Handles loging in of users
session_start();
require_once '../../includes/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['submit'])) {
        $email = isset($_POST['userEmail']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['userEmail']))) : '';
        $phone_number = isset($_POST['userPhonenumber']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['userPhonenumber']))) : '';
        $password = isset($_POST['userPhonenumber']) ? mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['password']))) : '';
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        // if(empty($email or $))

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?? or phone_number = ?? AND  deleted_at IS NULL");
        $stmt->bind_param("ss", $phone_number, $email);
        if ($stmt->execute()) {
            $_SESSION['toast_message'] = 'Hello user you have logged in successfully';
        } else {
            $_SESSION['toast_message'] = 'invalid email or phone number or password' . $conn->error;
        }


        // Close the statement and the database connection to free up resources.
        $stmt->close();
        $conn->close();
    }
}
