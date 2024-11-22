<?php
require_once 'admin.php';

session_start();
$admin = new Admin();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $className = isset($_POST['class_name']) ? htmlspecialchars(trim($_POST['class_name'])) : null;
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : null;
    $room = isset($_POST['room']) ? htmlspecialchars(trim($_POST['room'])) : null;
    $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;
    $schedule = isset($_POST['schedule']) ? $_POST['schedule'] : null;
    $instructor = isset($_POST['instructor']) ? htmlspecialchars(trim($_POST['instructor'])) : null;

    // Validate that all fields are provided
    if (empty($className) || empty($subject) || empty($room) || empty($startDate) || empty($endDate) || empty($schedule) || empty($instructor)) {
        header("Location: adminDashboard.php?error=All fields are required");
        exit();
    }

    // Add the class
    $classAdded = $admin->addClass($className, $subject, $instructor, $startDate, $endDate, $room, $schedule);

    if ($classAdded) {
        header("Location: adminDashboard.php?success=Class added successfully");
    } else {
        header("Location: adminDashboard.php?error=Failed to add class");
    }
    exit();
}
?>

