<?php

class SignupContr extends Signup {

    private $uid;
    private $pwd;
    private $pwdRepeat;
    private $email;

    // Constructor to initialize the properties
    public function __construct($uid, $pwd, $pwdRepeat, $email) {
        $this->uid       = $uid;
        $this->pwd       = $pwd;
        $this->pwdRepeat = $pwdRepeat;
        $this->email     = $email;
    }

    // Method to handle user sign up
    public function signupUser() {
        if ($this->emptyInput() == false) {
            header("location:../index.php?error=emptyinput");
            exit();
        }
        if ($this->invalidUid() == false) {
            header("location:../index.php?error=username");
            exit();
        }
        if ($this->invalidEmail() == false) {
            header("location:../index.php?error=email");
            exit();
        }
        if ($this->pwdMatch() == false) {
            header("location:../index.php?error=passwordmatch");
            exit();
        }
        if ($this->uidTakenCheck() == false) {
            header("location:../index.php?error=useroremailtaken");
            exit();
        }

        // Set the user in the database
        $this->setUser($this->uid, $this->pwd, $this->email);
    }

    // Method to check if any input fields are empty
    private function emptyInput() {
        $result = null;
        if (
            empty($this->uid) ||
            empty($this->pwd) ||
            empty($this->pwdRepeat) ||
            empty($this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    // Method to validate the username
    private function invalidUid() {
        $result = null;

        if (! preg_match("/^[a-zA-Z0-9]*$/", $this->uid)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    // Method to validate the email
    private function invalidEmail() {
        $result = null;
        if (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    // Method to check if the passwords match
    private function pwdMatch() {
        $result = null;
        if ($this->pwd !== $this->pwdRepeat) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    // Method to check if the username or email is already taken
    private function uidTakenCheck() {
        $result = null;
        if (! $this->checkUser($this->uid, $this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}