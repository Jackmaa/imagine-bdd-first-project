<?php

class LoginContr extends Login {

    // Properties to store user ID and password
    private $uid;
    private $pwd;

    // Constructor to initialize the properties
    public function __construct($uid, $pwd) {
        $this->uid = $uid;
        $this->pwd = $pwd;
    }

    // Method to handle user login
    public function loginUser() {
        // Check if any input is empty
        if ($this->emptyInput() == false) {
            // Redirect to index page with error if input is empty
            header("location:../index.php?error=emptyinput");
            exit();
        }

        // Call method to get user with provided credentials
        $this->getUser($this->uid, $this->pwd);
    }

    // Method to check if any input is empty
    private function emptyInput() {
        $result = null;
        // Check if user ID or password is empty
        if (
            empty($this->uid) ||
            empty($this->pwd)) {
            $result = false; // Return false if any input is empty
        } else {
            $result = true; // Return true if no input is empty
        }
        return $result;
    }

}