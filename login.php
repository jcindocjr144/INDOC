<?php
require_once 'User.php';
$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Login failed! Please check your username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="login.css" rel="stylesheet"> <!-- Link to external custom CSS -->
    <link rel="shortcut icon" href="images/CTU.png">
  
</head>
<body>
    <div class="container-fluid vh-100 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-md-6 d-flex justify-content-center align-items-center text-center text-white">
                <div class="text-container">
                <h1>Cebu Technological University</h1>
                <h3>Cordova Campus</h3>
                    <p>Login to access your grades, and profile settings.</p>
                    <p>Experience a seamless learning journey with us.</p>
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-center">
                <div class="card shadow-lg p-4 rounded-lg" style="width: 100%; max-width: 400px;">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                        <hr>
                        <div class="d-flex justify-content-center mt-2">
                        <button  class="btn btn-info btn-block w-50"><a href="register.php">Create new account</a></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
