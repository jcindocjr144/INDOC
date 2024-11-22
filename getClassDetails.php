<?php
require_once 'Admin.php';
$admin = new Admin();

if (isset($_GET['classId'])) {
    $classId = $_GET['classId'];
    $classDetails = $admin->getClassById($classId); // Assuming this method exists in the Admin class
    echo json_encode($classDetails); // Return class details as JSON
}
?>
