<?php
    require_once 'instructorDashboard.php';
    require_once 'instructor.php';
    require_once 'database.php';
    require_once 'User.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <link rel="shortcut icon" href="images/CTU.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-danger">
        <a class="navbar-brand text-white" href="#">Instructor Dashboard</a>
        <button class="navbar-toggler bg-white" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="#" class="nav-link text-white" onclick="home()">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white" onclick="toggleClasses()">All Classes</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white" onclick="toggleStudents()">All Students</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white" onclick="toggleDetails()">Profile</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div id="homeSection" class="text-center">
        <h2>Welcome to the Instructor Dashboard</h2>
        <p>Select an option from the menu to begin.</p>
    </div>

    
    <div class="container">
        <!-- User Details Section -->
        <div id="detailsSection" class="card mt-4" style="display: none;">
            <div class="card-header text-center bg-dark text-white">
                <h2>My Profile</h2>
            </div>
            <div class="card-body">
                <?php if ($userDetails): ?>
                    <form action="update_profile.php" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($userDetails['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="form-text text-muted">Leave blank to keep the current password.</small>
                        </div>
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" value="<?php echo htmlspecialchars($userDetails['first_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="middleName">Middle Name</label>
                            <input type="text" class="form-control" id="middleName" name="middle_name" value="<?php echo htmlspecialchars($userDetails['middle_name']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="last_name" value="<?php echo htmlspecialchars($userDetails['last_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userDetails['email']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger">User details not found. Please contact support.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Students Section -->
        <div id="studentsSection" class="card mt-4" style="display: none;">
            <div class="card-header text-center bg-dark text-white">
                <h2>Students</h2></div>
            <div class="card-body">
                <?php if (!empty($students)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">No students found.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Classes Section -->
        <div id="classesSection" class="card mt-4" style="display: none;">
            <div class="card-header text-center bg-dark text-white">
                <h2>Classes</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($classes)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Class ID</th>
                                <th>Class Name</th>
                                <th>Subject</th>
                                <th>Room</th>
                                <th>Schedule</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($classes as $class): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($class['id']); ?></td>
                                    <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                                    <td><?php echo htmlspecialchars($class['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($class['room']); ?></td>
                                    <td><?php echo htmlspecialchars($class['schedule']); ?></td>
                                    <td>
                                        <!-- Enroll Student Form -->
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addStudentModal" onclick="setClassId(<?php echo $class['id']; ?>)">Add Student</button>
                                        <form action="instructorDashboard.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="delete_class_id" value="<?php echo $class['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">No classes found.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal for adding students -->
        <div class="modal" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Enroll Student in Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addStudentForm" method="POST" action="instructorDashboard.php">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="studentSelect">Select Student</label>
                                <select class="form-control" id="studentSelect" name="student_id" required>
                                    <?php foreach ($students as $student): ?>
                                        <option value="<?php echo $student['id']; ?>"><?php echo htmlspecialchars($student['first_name']) . ' ' . htmlspecialchars($student['last_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <input type="hidden" id="classId" name="class_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Enroll Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
<!-- Form to enroll student and create a grade entry -->
<div id="gradingSection" class="card mt-4" style="display: none;">
    <div class="card-header text-center bg-dark text-white">
        <h2>Enroll Student & Create Grade Entry</h2>
    </div>
    <div class="card-body">
        <form action="instructorDashboard.php" method="POST">
            <div class="form-group">
                <label for="student_id">Select Student</label>
                <select class="form-control" id="student_id" name="student_id" required>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['id']; ?>"><?php echo htmlspecialchars($student['first_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="class_id">Select Class</label>
                <select class="form-control" id="class_id" name="class_id" required>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enroll and Create Grade Entry</button>
        </form>
    </div>
</div>

    <script>
        function setClassId(classId) {
            document.getElementById('classId').value = classId;
        }
    </script>

    <script src="js/instructor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
