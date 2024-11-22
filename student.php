<?php
require_once 'Database.php';

class Student extends Database {
    // Method to get student details based on user ID
    public function getStudentDetails($user_id) {
        $stmt = $this->getDb()->prepare("SELECT * FROM users WHERE id = ? AND role = 'student'");
        $stmt->execute([$user_id]);
        $studentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return null if no student is found
        return $studentDetails ?: null;
    }


    // Method to update student profile in the 'students' table
    public function updateStudentProfile($studentId, $firstName, $middleName, $lastName, $email, $birthdate, $newPassword = null) {
        // Start by updating the 'students' table with the new details
        $sql = "UPDATE students SET 
                    first_name = :first_name, 
                    middle_name = :middle_name, 
                    last_name = :last_name, 
                    email = :email,
                    birthdate = :birthdate
                WHERE user_id = :student_id";  // Ensure 'user_id' is used for student ID
        
        // Prepare and bind parameters for the 'students' table
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':middle_name', $middleName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':student_id', $studentId);  // Bind the student ID (user_id)
    
        // Execute the update for the 'students' table
        $updateStudentResult = $stmt->execute();
        
        // If the update on the 'students' table is successful, update the 'users' table as well
        if ($updateStudentResult) {
            // Base SQL for updating the 'users' table without changing the password
            $sql = "UPDATE users SET 
                        first_name = :first_name, 
                        middle_name = :middle_name, 
                        last_name = :last_name, 
                        email = :email,
                        birthdate = :birthdate
                    WHERE id = :student_id AND role = 'student'"; // Ensure the role is 'student'
            
            // Prepare and bind parameters for the 'users' table
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':middle_name', $middleName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':birthdate', $birthdate);
            $stmt->bindParam(':student_id', $studentId);  // Bind the student ID (user_id)
    
            // If a new password is provided, update it as well (without hashing)
            if ($newPassword !== null && $newPassword !== '') {
                // Directly use the plain password (without hashing)
                $sql = "UPDATE users SET 
                            first_name = :first_name, 
                            middle_name = :middle_name, 
                            last_name = :last_name, 
                            email = :email,
                            birthdate = :birthdate,
                            password = :password
                        WHERE id = :student_id AND role = 'student'"; // Ensure the role is 'student'
                
                // Prepare and bind parameters for the 'users' table
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':first_name', $firstName);
                $stmt->bindParam(':middle_name', $middleName);
                $stmt->bindParam(':last_name', $lastName);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':birthdate', $birthdate);
                $stmt->bindParam(':student_id', $studentId);  // Bind the student ID (user_id)
                $stmt->bindParam(':password', $newPassword); // Use the plain password directly
                
                // Execute the update for the 'users' table with the new password
                return $stmt->execute();
            }
    
            // If no new password is provided, simply execute the update without changing the password
            return $stmt->execute();
        }
    
        // If updating the 'students' table fails, return false
        return false;
    }
    public function getGradesByStudentId($studentId) {
        $query = "SELECT g.grade, c.class_name, c.subject 
          FROM grades g 
          JOIN classes c ON g.class_id = c.id  -- changed subject_id to class_id
          WHERE g.student_id = :student_id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}    

?>
