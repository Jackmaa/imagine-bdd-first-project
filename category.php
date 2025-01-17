<?php
    session_start();                            // Start the session
    include './dbh.class.php';                  // Include the database connection class
    include './temp.php';                       // Include the temp file (assuming it contains some helper functions)
    $connection = new Dbh;                      // Create a new instance of the database connection class
    $bdd        = $connection->getConnection(); // Get the database connection

    // Check if the category ID is set in the URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        header('location: error-404.php'); // Redirect to error page if ID is not set
    }

    // Prepare and execute the query to get products in the category
    $req = $bdd->prepare(
        'SELECT
            product.name,
            product.description,
            product.price,
            product.date_published,
            product.id_product,
            category.id_category,
            category.name AS category
        FROM
            product
        JOIN
            product_category ON product.id_product = product_category.id_product
        JOIN
            category ON category.id_category = product_category.id_category
        WHERE
            category.id_category= :id;');
    $req->bindParam(':id', $id, PDO::PARAM_INT);
    $req->execute();

    // Prepare and execute the query to get category details
    $reqName = $bdd->prepare(
        'SELECT
            category.name AS category,
            category.description AS details
        FROM
            category
        WHERE
            category.id_category= :id;');
    $reqName->bindParam(':id', $id, PDO::PARAM_INT);
    $reqName->execute();
    $category_name = $reqName->fetch(PDO::FETCH_ASSOC); // Fetch the category details
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category_name['category'] ?></title> <!-- Display the category name as the page title -->
    <link rel="stylesheet" href="./assets/styles.css"> <!-- Link to the stylesheet -->
</head>
<body>
<header>
        <a href="index.php"><img src="./assets/img/imagine.svg" alt=""></a> <!-- Logo -->
        <ul class="menu-member">
            <?php
                if (isset($_SESSION["userid"])) {                                                                                  // Check if the user is logged in
                    $extension      = getUserProfilePictureExtension($_SESSION["useruid"]);                                            // Get the profile picture extension
                    $profilePicPath = $extension ? "assets/img/" . $_SESSION["useruid"] . "." . $extension : "assets/img/default.png"; // Set the profile picture path
                ?>
            <li class="flex-item">
                <img class="profile-pic" src="<?php echo $profilePicPath; ?>" alt="Profile Picture" width="50" height="50"> <!-- Display the profile picture -->
                <a href="./user.php?id=<?php echo $_SESSION["userid"] ?>"> <!-- Link to the user profile -->
                    <?php echo $_SESSION["useruid"]; ?> <!-- Display the username -->
                </a>
            </li>
            <li><a href="includes/logout.inc.php">LOGOUT</a></li> <!-- Logout link -->
            <?php
                } else {
                ?>
                <li><a href="sign-in.php">SIGN UP</a></li> <!-- Sign up link -->
                <li><a href="sign-in.php">LOGIN</a></li> <!-- Login link -->
            <?php
                }
            ?>
        </ul>
</header>
<h1><?php echo $category_name['category'] ?></h1> <!-- Display the category name -->
<p><?php echo $category_name['details'] ?></p> <!-- Display the category details -->
<?php
    while ($products = $req->fetch(PDO::FETCH_ASSOC)) { // Loop through the products
    ?>

    <article>
        <h2><?php echo $products['name'] ?></h2> <!-- Display the product name -->
        <p><?php echo $products['description'] ?></p> <!-- Display the product description -->
        <p>Prix :<?php echo $products['price'] ?></p> <!-- Display the product price -->
        <a href="./product.php?id=<?php echo $products['id_product'] ?>">En savoir plus</a> <!-- Link to the product details page -->
    </article>
<?php
    }
?>

</body>
</html>