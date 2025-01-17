<?php

class Signup extends Dbh {

    // Method to insert a new user into the database
    protected function setUser($uid, $pwd, $email) {
        $stmt = $this->getConnection()->prepare('INSERT INTO `user` (`name`, `password`, `email`, `sign_in_date`) VALUES (?, ?, ?, CURRENT_DATE);');

        // Ensure the cost value is set
        if ($this->cost === null) {
            $this->findAppropriateCost();
        }

        // Hash the password before storing it
        $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => $this->cost]);

        // Execute the statement and handle errors
        if (! $stmt->execute([$uid, $hashedPwd, $email])) {
            $stmt = null;
            header("location:../index.php?error-stmtfailed");
            exit();
        }
        $stmt = null;
    }

    // Method to check if a user already exists in the database
    protected function checkUser($uid, $email) {
        $stmt = $this->getConnection()->prepare('SELECT `name` from user WHERE `name` = ? OR `email` = ?;');

        // Execute the statement and handle errors
        if (! $stmt->execute([$uid, $email])) {
            $stmt = null;
            header("location:../index.php?error-stmtfailed");
            exit();
        }

                             // Check if any rows were returned
        $resultCheck = null; // Initialize $resultCheck
        if ($stmt->rowCount() > 0) {
            $resultCheck = false;
        } else {
            $resultCheck = true;
        }

        return $resultCheck;
    }

}