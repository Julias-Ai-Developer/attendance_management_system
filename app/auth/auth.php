<?php
// Handles loging in of users
session_start();
require_once '../../includes/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $credential = mysqli_real_escape_string($conn, trim($_POST['user_credential']));
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE (email = ? OR phone_number = ?) AND deleted_at IS NULL LIMIT 1");
    $stmt->bind_param("ss", $credential, $credential);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_phone_number'] = $user['phone_number'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_is_active'] = $user['is_active'];
            $_SESSION['toast_message'] = 'Hello ' . $_SESSION['user_name'] . ', you have logged in successfully';
            $stmt->close();
            $conn->close();
            header('Location: ../../views/dashboard.php');
            exit;
        } else {
            $_SESSION['toast_message'] = 'Invalid password';
            $stmt->close();
            $conn->close();
            header('Location: ../../views/login.php');
            exit;
        }
    } else {
        $_SESSION['toast_message'] = 'No user found with that email or phone number.';
        $stmt->close();
        $conn->close();
        header('Location: ../../views/login.php');
        exit;
    }
}
?>
