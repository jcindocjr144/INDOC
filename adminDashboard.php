<?php 
require_once 'User.php';
require_once 'Admin.php';

session_start();
$user = new User();

// Check if user is logged in and is an admin
if (!$user->isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Retrieve user details
$userDetails = $user->getUserDetails($_SESSION['user_id']);

// If no user details are found, show an error message and exit
if (empty($userDetails)) {
    echo "<div class='alert alert-danger'>User details not found. Please contact support.</div>";
    exit();
}

$admin = new Admin();
$students = $admin->getAllStudents();
$classes = $admin->getAllClasses(); // Retrieve all classes for display
// Handling the form submission
$admin->handleClassUpdateForm();

include 'adminhtml.php';
?>
