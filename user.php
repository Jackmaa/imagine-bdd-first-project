<?php
    session_start();           // Start the session
    include './dbh.class.php'; // Include the database connection class
    include './temp.php';      // Include the functions file

    $connection = new Dbh;                      // Create a new database connection
    $bdd        = $connection->getConnection(); // Get the database connection
    if (isset($_GET['id'])) {                   // Check if the 'id' parameter is set in the URL
        $id = $_GET['id'];                          // Get the 'id' parameter from the URL
    } else {
        header('location: error-404.php'); // Redirect to error page if 'id' is not set
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set the viewport for responsive design -->
    <link rel="stylesheet" href="./assets/styles.css"> <!-- Link to the stylesheet -->
    <title>Document</title> <!-- Set the title of the document -->
</head>
<body>
<header>
        <a href="index.php"><img src="./assets/img/imagine.svg" alt=""></a> <!-- Link to the homepage with logo -->
        <ul class="menu-member">
            <?php
                if (isset($_SESSION["userid"])) {                                                                                  // Check if the user is logged in
                    $extension      = getUserProfilePictureExtension($_SESSION["useruid"]);                                            // Get the profile picture extension
                    $profilePicPath = $extension ? "assets/img/" . $_SESSION["useruid"] . "." . $extension : "assets/img/default.png"; // Set the profile picture path
                ?>
            <li class="flex-item">
                <img class="profile-pic" src="<?php echo $profilePicPath; ?>" alt="Profile Picture" width="50" height="50"> <!-- Display the profile picture -->
                <a href="./user.php?id=<?php echo $_SESSION["userid"] ?>"> <!-- Link to the user's profile page -->
                    <?php echo $_SESSION["useruid"]; ?> <!-- Display the username -->
                </a>
            </li>
            <li><a href="includes/logout.inc.php">LOGOUT</a></li> <!-- Link to logout -->
            <?php
                } else {
                ?>
                <li><a href="sign-in.php">SIGN UP</a></li> <!-- Link to sign up -->
                <li><a href="sign-in.php">LOGIN</a></li> <!-- Link to login -->
            <?php
                }
            ?>
        </ul>
</header>
<!-- Profile picture upload form -->
<?php if (isset($_SESSION["userid"])): ?> <!-- Check if the user is logged in -->
    <form action="upload_profile_picture.php" method="post" enctype="multipart/form-data"> <!-- Form to upload profile picture -->
        <label for="profilePicture">Upload Profile Picture:</label> <!-- Label for the file input -->
        <input type="file" name="profilePicture" id="profilePicture" required> <!-- File input for profile picture -->
        <button type="submit" name="submit">Upload</button> <!-- Submit button -->
    </form>
<?php endif; ?>
</body>
</html>