<?php
require_once 'Database.php';

class Instructor extends Database {
    public function getAllStudents() {
        $sql = "SELECT * FROM users WHERE role = 'student'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProfile($instructorId, $firstName, $middleName, $lastName, $email, $username, $password = null) {
        $query = "UPDATE users SET username = :username, first_name = :first_name, middle_name = :middle_name, last_name = :last_name, email = :email";
        
        if ($password !== null && $password !== '') {
            $query .= ", password = :password";
        }
    
        $query .= " WHERE id = :id";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':middle_name', $middleName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $instructorId);
    
        if ($password !== null && $password !== '') {
            $stmt->bindParam(':password', $password);
        }
    
        $stmt->execute();
    }

    public function getAllClasses() {
        $query = "SELECT id, class_name, instructor, subject, start_date, end_date, room, schedule FROM classes";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function addClass($className, $subject, $instructor, $startDate, $endDate, $room, $schedule) {
        $query = "INSERT INTO classes (class_name, subject, instructor, start_date, end_date, room, schedule) 
                  VALUES (:class_name, :subject, :instructor, :start_date, :end_date, :room, :schedule)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':class_name', $className);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':instructor', $instructor);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->bindParam(':room', $room);
        $stmt->bindParam(':schedule', $schedule);

        return $stmt->execute();
    }


    public function getClassDetails($classId) {
        $stmt = $this->db->prepare("SELECT * FROM classes WHERE id = ?");
        $stmt->execute([$classId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateClass($classId, $className, $subject, $room, $instructor, $startDate, $endDate, $schedule) {
        $stmt = $this->db->prepare("UPDATE classes SET class_name = ?, subject = ?, room = ?, instructor = ?, start_date = ?, end_date = ?, schedule = ? WHERE id = ?");
        return $stmt->execute([$className, $subject, $room, $instructor, $startDate, $endDate, $schedule, $classId]);
    }
    
    public function deleteClass($class_id) {
        $sql = "DELETE FROM classes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $class_id, PDO::PARAM_INT);
        return $stmt->execute(); // Return true if deletion was successful
    }

    public function getClassById($class_id) {
        $sql = "SELECT * FROM classes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $class_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return class details if found
    }


    // Handle class update request from a form submission
    public function handleClassUpdateForm() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['classId'])) {
            $classId = $_POST['classId'];
            $className = $_POST['class_name'];
            $subject = $_POST['subject'];
            $room = $_POST['room'];
            $instructor = $_POST['instructor'];
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $schedule = $_POST['schedule'];
    
            // Assuming $this->updateClass() is a method that updates the class details in the database
            if ($this->updateClass($classId, $className, $subject, $room, $instructor, $startDate, $endDate, $schedule)) {
                // Success: Output JavaScript to show an alert and reload the page
                echo "<script>
                        Swal.fire({
                            title: 'Success!',
                            text: 'Class updated successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                      </script>";
            } else {
                // Failure: Output JavaScript to show an error alert
                echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update class.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                      </script>";
            }
        }
    }
    public function enrollStudentInClass($studentId, $classId) {
        try {
            $stmt = $this->db->prepare("INSERT INTO class_enrollments (student_id, class_id) VALUES (:student_id, :class_id)");
            $stmt->bindParam(':student_id', $studentId);
            $stmt->bindParam(':class_id', $classId);
            
            // Execute the query
            if ($stmt->execute()) {
                return true;
            } else {
                // Log the error if query fails
                $errorInfo = $stmt->errorInfo();
                echo "SQL Error: " . $errorInfo[2];
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function createGradeEntry($studentId, $subjectId) {
        // SQL to create an entry in the grades table with an initial NULL grade
        $query = "INSERT INTO grades (student_id, subject_id, grade) VALUES (:student_id, :subject_id, NULL)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId);
        $stmt->bindParam(':subject_id', $subjectId);
        return $stmt->execute(); // Returns true if grade entry is created
    }
    
}   
?>
