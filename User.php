<?php
require_once 'Database.php';

class User extends Database {
    public function register($username, $password, $role, $email, $firstName, $middleName, $lastName, $birthDate) {
        try {
            // Begin a transaction
            $this->getDb()->beginTransaction();
    
            // Insert data into the `users` table
            $stmt = $this->getDb()->prepare("INSERT INTO users (username, password, role, email, first_name, middle_name, last_name, birthdate) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $password, $role, $email, $firstName, $middleName, $lastName, $birthDate]);
    
            // Get the last inserted user ID
            $userId = $this->getDb()->lastInsertId();
    
            // If the role is 'student', insert into the `students` table
            if ($role === 'student') {
                $stmt = $this->getDb()->prepare("INSERT INTO students (user_id, first_name, middle_name, last_name, email, birthdate) 
                                                VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $firstName, $middleName, $lastName, $email, $birthDate]);
            }
    
            // Commit the transaction
            $this->getDb()->commit();
            return true;
    
        } catch (PDOException $e) {
            // Rollback the transaction in case of error
            $this->getDb()->rollBack();
            error_log($e->getMessage());  // Log error to file for debugging
            $_SESSION['registration_error'] = "Error: " . $e->getMessage();
            return false;
        }
    }
        
    public function login($username, $password) {
        $stmt = $this->getDb()->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Compare plain text password directly
        if ($user && $password === $user['password']) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['birthdate'] = $user['birthdate']; // Corrected key for consistency
            return true;
        }
        return false;
    }

    public function getAllStudents() {
        $sql = "SELECT * FROM users WHERE role = 'student'";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }

    public function getUserDetails($user_id) {
        $stmt = $this->getDb()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to update user profile details (username and password)
    public function updateUserProfile($userId, $username, $password = null) {
        // Start building the query
        $sql = "UPDATE users SET username = :username";

        // If password is provided, include it in the update
        if ($password) {
            $sql .= ", password = :password";
        }

        $sql .= " WHERE id = :id";

        // Prepare statement
        $stmt = $this->getDb()->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':id', $userId);

        // Bind the password if it's provided
        if ($password) {
            // Directly bind the plain password without hashing
            $stmt->bindParam(':password', $password);
        }
        
        // Execute and return result
        return $stmt->execute();
    }

    // Method to update user's personal details (first name, middle name, last name, email)
    public function updateProfile($userId, $firstName, $middleName, $lastName, $email) {
        $query = "UPDATE users SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name, email = :email WHERE id = :id";
        $stmt = $this->getDb()->prepare($query);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':middle_name', $middleName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }
}
?>
