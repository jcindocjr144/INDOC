<?php
require_once 'User.php';
$user = new User();
$message = ""; // Initialize the message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $birthDate = $_POST['birthdate'];

    // Perform registration
    if ($user->register($username, $password, $role, $email, $firstName, $middleName, $lastName, $birthDate)) {
        // Set a session variable or a flag if needed
        $_SESSION['registration_success'] = true; // Optional
        header('Location: login.php'); // Redirect to a success page
        exit; // Ensure no further code is executed
    } else {
        // Set an error message to be shown after redirection
        $_SESSION['registration_error'] = "Registration failed!";
        header('Location: register.php'); // Redirect back to the registration form
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="max-width: 800px; width: 100%;">
            <h2 class="text-center mb-4">Register</h2>
            <?php if (isset($_SESSION['registration_error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['registration_error']; unset($_SESSION['registration_error']); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birth Date</label>
                            <input type="date" name="birthdate" id="birthdate" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="form-group" style="display:none;">
                    <label for="role">Role</label>
                    <select name="role" id="role" class="form-control">
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <p class="text-center">Already have an account? <a href="login.php">Login</a></p>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
