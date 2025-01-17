<?php

if (isset($_POST["submit"])) {
    // Grabs data from the form
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];

    // Include necessary files and create an instance of the LoginContr class
    include "../dbh.class.php";
    include "../classes/login.class.php";
    include "../classes/login-contr.class.php";
    $login = new LoginContr($uid, $pwd);

    // Run error handlers and user login
    $login->loginUser();

    // Redirect to the front page with a success message
    header("location:../index.php?error-none");
}