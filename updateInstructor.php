<?php
require_once 'instructor.php';
session_start();

$instructor = new Instructor();

// Check if the session is set for user ID
if (!isset($_SESSION['user_id'])) {
    // Redirect if user is not logged in
    header("Location: login.php");
    exit();
}

// Get the submitted data
$instructorId = $_SESSION['user_id'];
$firstName = $_POST['first_name'] ?? null;
$middleName = $_POST['middle_name'] ?? null;
$lastName = $_POST['last_name'] ?? null;
$email = $_POST['email'] ?? null;
$username = $_POST['username'] ?? null; // Capture username
$password = $_POST['password'] ?? null; // Capture password (can be empty)

// Call updateProfile with the captured data
$instructor->updateProfile($instructorId, $firstName, $middleName, $lastName, $email, $username, $password);
header("Location: instructorDashboard.php");
exit();
?>
