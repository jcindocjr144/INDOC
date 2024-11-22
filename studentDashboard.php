<?php
require_once 'User.php';
require_once 'Student.php';
session_start();

$user = new User();

// Check if the user is logged in and is a student
if (!$user->isLoggedIn() || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student = new Student();
$studentDetails = $student->getStudentDetails($_SESSION['user_id']);

// Handle form submission for updating the profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'] ?? '';
    $middleName = $_POST['middle_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? ''; // Optional password

    // Update the user and student profiles
    $userUpdateResult = $user->updateUserProfile($_SESSION['user_id'], $username, $password);
    $studentUpdateResult = $student->updateStudentProfile($_SESSION['user_id'], $firstName, $middleName, $lastName, $email, $birthdate);

    if ($userUpdateResult && $studentUpdateResult) {
        $success = "Profile updated successfully.";
        $studentDetails = $student->getStudentDetails($_SESSION['user_id']);
    } else {
        $error = "Profile update failed. Please try again.";
    }
}
include 'studenthtml.php';
?>
