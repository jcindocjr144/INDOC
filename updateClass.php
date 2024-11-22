<?php
require_once 'Admin.php';
$admin = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve the posted data
    $classId = $_POST['classId'];
    $className = $_POST['class_name'];
    $subject = $_POST['subject'];
    $room = $_POST['room'];
    $instructor = $_POST['instructor'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $schedule = $_POST['schedule'];
    
    // Update the class using the Admin class
    if ($admin->updateClass($classId, $className, $subject, $room, $instructor, $startDate, $endDate, $schedule)) {
        echo 'success';  // Return success if the update is successful
    } else {
        echo 'error';    // Return error if something goes wrong
    }
}
?>
