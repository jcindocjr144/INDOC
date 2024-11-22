<?php 
require_once 'User.php';

session_start();
$user = new User();

if (!$user->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] == 'student') {
    header("Location: studentDashboard.php");
    exit();
} elseif ($_SESSION['role'] == 'admin') {
    header("Location: adminDashboard.php");
    exit();
}elseif ($_SESSION['role'] == 'instructor') {
    header("Location: instructorDashboard.php");
    exit();
}
?>
