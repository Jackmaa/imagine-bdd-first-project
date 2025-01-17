<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Character encoding for the document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design meta tag -->
    <title>Imagine</title> <!-- Title of the webpage -->
    <link rel="stylesheet" href="./asset/styles/styles.css" > <!-- Link to external CSS stylesheet -->
</head>
<body>
<?php
    // Include the database handler class
    include './dbh.class.php';
    // Create a new instance of the database handler
    $connection = new Dbh;
    // Get the database connection
    $bdd  = $connection->getConnection();
    $cost = $connection->findAppropriateCost();
?>
    <header>
        <!-- Link to the homepage with an image -->
        <a href="index.php"><img src="./asset/img/imagine.svg" alt=""></a>
    </header>

    <!-- Sign-up form -->
    <form action="includes/signup.inc.php" method="post">
        <input type="text" name="uid" placeholder="Username"> <!-- Username input field -->
        <input type="password" name="pwd" placeholder="Password"> <!-- Password input field -->
        <input type="password" name="pwdRepeat" placeholder="Repeat Password"> <!-- Repeat password input field -->
        <input type="text" name="email" placeholder="E-mail"> <!-- Email input field -->
        <br>
        <button type="submit" name="submit">SIGN UP</button> <!-- Submit button for sign-up form -->
    </form>

    <!-- Login form -->
    <form action="includes/login.inc.php" method="post">
        <input type="text" name="uid" placeholder="Username"> <!-- Username input field -->
        <input type="password" name="pwd" placeholder="Password"> <!-- Password input field -->
        <br>
        <button type="submit" name="submit">LOGIN</button> <!-- Submit button for login form -->
    </form>
</body>
</html>