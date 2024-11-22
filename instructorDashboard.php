<?php 
require_once 'User.php';
require_once 'Instructor.php';

session_start();
$user = new User();

// Check if user is logged in and is an instructor
if (!$user->isLoggedIn() || $_SESSION['role'] !== 'instructor') {
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

$instructor = new Instructor(); // Retrieve students for the instructor's classes

$students = $instructor->getAllStudents();
$classes = $instructor->getAllClasses();

// Check if a class is being deleted
if (isset($_POST['delete_class_id'])) {
    $classIdToDelete = $_POST['delete_class_id'];
    
    // Call the deleteClass method of Instructor class to delete the class
    if ($instructor->deleteClass($classIdToDelete)) {
        echo "<script>alert('Class deleted successfully!'); window.location.href='instructorDashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to delete class.'); window.location.href='instructorDashboard.php';</script>";
    }
}

// Enroll student and create grading system when form is submitted
if (isset($_POST['student_id']) && isset($_POST['class_id'])) {
    $studentId = $_POST['student_id'];
    $classId = $_POST['class_id'];

    // Enroll the student in the class
    if ($instructor->enrollStudentInClass($studentId, $classId)) {
        // Create grading system for the student
        if ($instructor->createGradeEntry($studentId, $classId)) {
            echo "<script>alert('Student enrolled and grading system created successfully!'); window.location.href='instructorDashboard.php';</script>";
        } else {
            echo "<script>alert('Failed to create grading system.'); window.location.href='instructorDashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Failed to enroll student in class.'); window.location.href='instructorDashboard.php';</script>";
    }
}

include 'instructorhtml.php';
?>
