<?php

class Login extends Dbh {

    protected function getUser($uid, $pwd) {
        // Prepare a SQL statement to select the password from the user table where the name or email matches the provided uid
        $stmt = $this->getConnection()->prepare('SELECT `password` FROM `user` WHERE `name` = ? OR `email` = ?;');

        // Execute the statement with the provided uid
        if (! $stmt->execute([$uid, $uid])) {
            $stmt = null;                                     // Set the statement to null
            header("location:../index.php?error-stmtfailed"); // Redirect to the index page with an error message
            exit();                                           // Exit the script
        }

        // Check if no rows were returned
        if ($stmt->rowCount() == 0) {
            $stmt = null;                               // Set the statement to null
            header("location: ../index.php?error=sup"); // Redirect to the index page with an error message
            exit();                                     // Exit the script
        }

        // Fetch the hashed password from the result
        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Verify the provided password with the hashed password
        $checkPwd = password_verify($pwd, $pwdHashed[0]["password"]);

        // Check if the password verification failed
        if ($checkPwd == false) {
            $stmt = null;                                               // Set the statement to null
            header("location: ../index.php?error=somethingisnotright"); // Redirect to the index page with an error message
            exit();                                                     // Exit the script
        } else if ($checkPwd == true) {
            // Prepare a SQL statement to select all user details where the name or email matches the provided uid and the password matches the hashed password
            $stmt = $this->getConnection()->prepare('SELECT * FROM `user` WHERE (`name` = ? OR `email` = ?) AND `password` = ?;');

            // Execute the statement with the provided uid and hashed password
            if (! $stmt->execute([$uid, $uid, $pwdHashed[0]["password"]])) {
                $stmt = null;                                     // Set the statement to null
                header("location:../index.php?error-stmtfailed"); // Redirect to the index page with an error message
                exit();                                           // Exit the script
            }

            // Check if no rows were returned
            if ($stmt->rowCount() == 0) {
                $stmt = null;                                        // Set the statement to null
                header("location: ../index.php?error=usernotfound"); // Redirect to the index page with an error message
                exit();                                              // Exit the script
            }

            // Fetch the user details from the result
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            session_start();                            // Start the session
            $_SESSION["userid"]  = $user[0]["id_user"]; // Set the user ID in the session
            $_SESSION["useruid"] = $user[0]["name"];    // Set the username in the session

            $stmt = null; // Set the statement to null
        }

        $stmt = null; // Set the statement to null
    }

}
?>