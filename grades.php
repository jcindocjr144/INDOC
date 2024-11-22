<?php
// Database connection setup
$host = 'localhost';
$username = 'root';  // Replace with your DB username
$password = '';  // Replace with your DB password
$dbname = 'ctuCordb';  // Replace with your DB name

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];
    $grade = $_POST['grade'];

    // Validate inputs
    if (empty($student_id) || empty($class_id) || empty($grade)) {
        die("Student, class, and grade are required.");
    }

    // Insert grade record into the database
    $sql = "INSERT INTO grades (student_id, class_id, grade) VALUES (?, ?, ?)"; // Updated to 'grades' table
    $stmt = $conn->prepare($sql);

    // Check if the statement preparation is successful
    if ($stmt === false) {
        die('SQL error: ' . $conn->error);  // Output the error message from MySQL if the preparation fails
    }

    // Bind parameters and execute the query
    $stmt->bind_param("iis", $student_id, $class_id, $grade);  // "i" for integer, "s" for string (grade is a string if it includes decimals)
    if ($stmt->execute()) {
        // Success: Show success message and redirect to admin dashboard
        echo "<script>
                Swal.fire({
                    title: 'Grade Enrollment Successful!',
                    text: 'The student has been successfully enrolled with a grade.',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    // Redirect to the admin dashboard page after success
                    window.location.href = 'adminDashboard.php';  // Replace 'adminDashboard.php' with your admin dashboard URL
                });
            </script>";
    } else {
        // Error: Show error message
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an issue enrolling the student.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            </script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
