<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'task_manager';

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
