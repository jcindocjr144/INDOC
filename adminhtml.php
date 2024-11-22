
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="shortcut icon" href="images/CTU.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-danger ">
        <a class="navbar-brand text-white text-center p-1" href="#">Admin Dashboard</a>
        <button class="navbar-toggler bg-white" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item "><a href="#" onclick="home()" class="nav-link text-white">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Class</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" onclick="toggleStudents()">All Students</a>
                        <a class="dropdown-item" href="#" onclick="toggleClasses()">All Classes</a> 
                        <a class="dropdown-item" href="#" onclick="toggleGrades()">Add Grades</a> 
                        <a class="dropdown-item" href="#" onclick="toggleViewGrades()">View Grades</a> 
                    </div>
                </li>
                <li class="nav-item"><a href="#" class="nav-link text-white" onclick="toggleDetails()">Profile</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <!-- Grades Section -->
<div id="grade" class="container" style="display:none;">
    <div class="card mt-4">
        <div class="card-header text-center bg-dark text-white">
            <h2>Grades</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="grades.php">
                <div class="form-group">
                    <label for="student_id">Select Student</label>
                    <select id="student_id" name="student_id" class="form-control" required>
                        <option value="">Select a student</option>
                        <?php
                        // Fetch all students
                        $students = $admin->getAllStudents();
                        foreach ($students as $student) {
                            echo "<option value=\"" . $student['id'] . "\">" . $student['first_name'] . " " . $student['last_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="class_id">Select Subject</label>
                    <select id="class_id" name="class_id" class="form-control" required>
                        <option value="">Select a subject</option>
                        <?php
                        // Fetch all classes (subjects)
                        $classes = $admin->getAllClasses();
                        foreach ($classes as $class) {
                            echo "<option value=\"" . $class['id'] . "\">" . $class['class_name'] . " (" . $class['subject'] . ")</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
    <label for="grade">Grade</label>
    <input type="number" id="grade" name="grade" class="form-control" placeholder="Enter grade (1.0 - 5.0)" min="1" max="5" step="0.1" required>
</div>


                <button type="submit" class="btn btn-primary">Add Grade</button>
            </form>
        </div>
    </div>
</div>
<!-- View Grades Section -->
<div id="view-grades" class="container mt-4" style="display:none;">
    <div class="card">
        <div class="card-header text-center bg-dark text-white">
            <h2>View Grades</h2>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Subject</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all grades
                    $grades = $admin->getAllGrades(); // Replace with your method to get all grades

                    foreach ($grades as $grade) {
                        // Fetch student and class names using grade's student_id and class_id
                        $student = $admin->getStudentById($grade['student_id']);
                        $class = $admin->getClassById($grade['class_id']);
                    ?>
                        <tr>
                            <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                            <td><?php echo $class['class_name'] . ' (' . $class['subject'] . ')'; ?></td>
                            <td><?php echo number_format((float)$grade['grade'], 1); ?></td>
                           
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Updating Grade -->
<div class="modal fade" id="updateGradeModal" tabindex="-1" role="dialog" aria-labelledby="updateGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateGradeModalLabel">Update Grade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateGradeForm" method="POST" action="updateGrade.php">
                    <input type="hidden" id="gradeId" name="id" value="">
                    <div class="form-group">
                        <label for="studentName">Student Name</label>
                        <input type="text" class="form-control" id="studentName" disabled>
                    </div>
                    <div class="form-group">
                        <label for="className">Subject</label>
                        <input type="text" class="form-control" id="className" disabled>
                    </div>
                    <div class="form-group">
                        <label for="grade">Grade</label>
                        <input type="number" id="grade" name="grade" class="form-control" min="1" max="5" step="0.1" required>
                    </div>
                    <button type="submit" class="btn btn-success">Update Grade</button>
                </form>
            </div>
        </div>
    </div>
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

        <!-- All Students Section -->
        <div id="studentsSection" class="card mt-4" style="display: none;">
            <div class="card-header text-center bg-dark text-white">
                <h2>All Students</h2></div>
            <div class="card-body">
                <?php if (!empty($students)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Birth Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['middle_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo htmlspecialchars($student['birthdate']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">No students found.</div>
                <?php endif; ?>
            </div>
        </div>

<!-- All Classes Section -->
<div id="classesSection" class="card mt-4" style="display: none;">
<div class="p-3 d-flex justify-content-end">
<button class="btn btn-primary btn-sm" href="#" onclick="toggleAddClass()">ADD CLASS <i class="fa-solid fa-plus"></i></button>
    </div>
    <div class="card-header text-center bg-dark text-white">
        <h2>All Classes</h2></div>
    <div class="card-body">
        <?php if (!empty($classes)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Class ID</th>
                        <th>Class Name</th>
                        <th>Subject</th>
                        <th>Room</th>
                        <th>Instructor</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Schedule</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                        <tr id="class-<?php echo $class['id']; ?>">
                            <td><?php echo htmlspecialchars($class['id']); ?></td>
                            <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                            <td><?php echo htmlspecialchars($class['subject']); ?></td>
                            <td><?php echo htmlspecialchars($class['room']); ?></td>
                            <td><?php echo htmlspecialchars($class['instructor']); ?></td>
                            <td><?php echo htmlspecialchars($class['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($class['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($class['schedule']); ?></td>
                            <td>
 <button class="btn btn-primary btn-sm" onclick="classes()">View</button>
 <button class="btn btn-warning btn-sm" onclick="showUpdateModal(<?php echo $class['id']; ?>)">Update</button>
 <button class="btn btn-danger btn-sm" onclick="deleteClass(<?php echo $class['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Update Class Modal -->
<div class="modal fade" id="updateClassModal" tabindex="-1" aria-labelledby="updateClassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateClassModalLabel">Update Class Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateClassForm" method="POST">
                    <input type="hidden" id="classId" name="classId">
                    <div class="form-group">
                        <label for="class_name">Class Name</label>
                        <input type="text" class="form-control" id="class_name" name="class_name" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="room">Room</label>
                        <input type="text" class="form-control" id="room" name="room" required>
                    </div>
                    <div class="form-group">
                        <label for="instructor">Instructor</label>
                        <input type="text" class="form-control bg-white" id="instructor" name="instructor" readonly>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                    <div class="form-group">
                        <label for="schedule">Schedule</label>
                        <select class="form-control" id="schedule" name="schedule" required>
                            <option value="MWF">MWF</option>
                            <option value="TTH">TTH</option>
                            <option value="SAT">SAT</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Class</button>
                </form>
            </div>
        </div>
    </div>
</div>
                <?php else: ?>
            <div class="alert alert-warning">No classes found.</div>
        <?php endif; ?>
    </div>
</div>
        <!-- Add Class Section -->
        <div id="addClassSection" class="card mt-4" style="display: none;">
            <div class="card-header text-center bg-primary text-white">
                <h2>Add New Class</h2></div>
            <div class="card-body"><form method="POST" action="addClass.php">
    <div class="form-group">
        <label for="class_name">Class Name</label>
        <input type="text" id="class_name" name="class_name" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="room">Room</label>
        <input type="text" id="room" name="room" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="start_date">Start Date</label>
        <input type="date" id="start_date" name="start_date" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="end_date">End Date</label>
        <input type="date" id="end_date" name="end_date" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="schedule">Schedule</label>
        <select id="schedule" name="schedule" class="form-control" required>
            <option value="MWF">MWF</option>
            <option value="TTH">TTH</option>
            <option value="SAT">SAT</option>
        </select>
    </div>

    

    <!-- instructor Selection from Admins -->
    <div class="form-group">
        <label for="instructor">instructor</label>
        <select id="instructor" name="instructor" class="form-control" required>
            <option value="">Select a instructor</option>
            <?php
                // Fetch the list of admins (instructors)
                $instructors = $admin->getAllAdmins(); // Get all admins with their first names
                foreach ($instructors as $instructor) {
                    echo "<option value=\"" . htmlspecialchars($instructor['first_name']) . "\">" . htmlspecialchars($instructor['first_name']) . "</option>";
                }
            ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Add Class</button>
</form>
            </div>
        </div>
    </div>

<script src="js/admin.js"></script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    </script>
</body>
</html>
