<?php
$conn = new mysqli("localhost", "root", "ceo@2005", "attendance_management_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>