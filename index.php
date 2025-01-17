<?php
    session_start();           // Start the session
    include './dbh.class.php'; // Include the database connection class
    include './temp.php';      // Include the functions file

    $connection = new Dbh;                      // Create a new database connection
    $bdd        = $connection->getConnection(); // Get the database connection
                                                // Execute a query to get the latest 10 products
    $req = $bdd->query(
        'SELECT `id_product`,`name`, `description`, `price`
    FROM `product`
    ORDER BY `id_product` DESC
    LIMIT 10;');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set the character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Set the viewport for responsive design -->
    <meta name="description" content="Description"> <!-- Add a description meta tag -->
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
<div class="grid-container">
<?php
    while ($product = $req->fetch(PDO::FETCH_ASSOC)) { // Fetch each product from the query result
    ?>
    <article>
        <h2><?php echo $product['name'] ?></h2> <!-- Display the product name -->
        <p><?php echo $product['description'] ?></p> <!-- Display the product description -->
        <p>Prix :<?php echo $product['price'] ?></p> <!-- Display the product price -->
        <a href="./product.php?id=<?php echo $product['id_product'] ?>">En savoir plus</a> <!-- Link to the product details page -->
    </article>
<?php
    }
?>
</div>
</body>
</html>