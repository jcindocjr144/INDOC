<?php
// Include your database connection and the Admin class (if using a class-based approach)
require_once 'Admin.php';  // Assuming you have the Admin class handling DB operations
$admin = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['classId'])) {
    $classId = $_POST['classId'];
    
    // Perform deletion (you can extend this logic to delete related data if necessary)
    $deleteClass = $admin->deleteClass($classId);

    if ($deleteClass) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
