<?php
    session_start(); // Start the session
    include './dbh.class.php';
    include './temp.php';
    $connection = new Dbh;
    $bdd        = $connection->getConnection();

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        header('location: error-404.php');
    }
    // REQ POUR JUSTE LE PRODUIT
    // $req = $bdd->prepare(
    //     'SELECT `name`, `description`, `price`,`date_published`
    //     FROM `product`
    //     WHERE `id_product` = :id');

    //REQ AVEC PLUSIEURS VARIABLES EN PASSANT UN TABLEAU
    // $req = $bdd->prepare(
    //     'SELECT `name`, `description`, `price`,`date_published`
    //     FROM `product`
    //     WHERE `id_product` = ? AND test = ?');
    // $req->execute([$id, $test]);

    // REQ POUR DISPLAY LE NOM DE LA CATEGORY DU PRODUIT
    //DOUBLE INNER JOIN NECESSAIRE CAR ON A UNE TABLE INTERMEDIARE

    $req = $bdd->prepare(
        'SELECT
            product.name,
            product.description,
            product.price,
            product.date_published,
            GROUP_CONCAT(CONCAT(category.id_category, \':\',category.name) SEPARATOR \',\') AS category
        FROM
            product
        JOIN
            product_category ON product.id_product = product_category.id_product
        JOIN
            category ON category.id_category = product_category.id_category
        WHERE
            product.id_product = :id;');
    $req->bindParam(':id', $id, PDO::PARAM_INT);
    $req->execute();

    $product = $req->fetch(PDO::FETCH_ASSOC);

    //on explode la string retourner par GROUP CONCAT SEPARATOR
    $cat = explode(',', $product['category']);

    //MA SOLUCE
    // $link = "";
    // foreach ($cat as $key => $value) {
    //     $catLink = explode(':', $value);
    //     $link .= '<a href="category.php?id=' . $catLink[0] . '">' . $catLink[1] . '</a> ';
    // }

    //SOLUCE A CEDRIC
    $catArray = [];
    foreach ($cat as $c) {
        $t          = explode(':', $c);                                            //list($catId, $catName) = explode(':', $c);
        $catArray[] = '<a href="category.php?id=' . $t[0] . '">' . $t[1] . '</a>'; //$catArray[] = '<a href="category.php?id=' . $catId . '">' . $catName . '</a>';
    }
    $link = implode(', ', $catArray);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imagine |                                                                                                                                                                                                                                                                                                             <?php echo $product['name'] ?></title>
</head>
<body>
<header>
        <a href="index.php"><img src="./assets/img/imagine.svg" alt=""></a>
        <ul class="menu-member">
            <?php
                if (isset($_SESSION["userid"])) {
                    $extension      = getUserProfilePictureExtension($_SESSION["useruid"]);
                    $profilePicPath = $extension ? "assets/img/" . $_SESSION["useruid"] . "." . $extension : "assets/img/default.png";
                ?>
            <li class="flex-item">
                <img class="profile-pic" src="<?php echo $profilePicPath; ?>" alt="Profile Picture" width="50" height="50">
                <a href="./user.php?id=<?php echo $_SESSION["userid"] ?>">
                    <?php echo $_SESSION["useruid"]; ?>
                </a>
            </li>
            <li><a href="includes/logout.inc.php">LOGOUT</a></li>
            <?php
                } else {
                ?>
                <li><a href="sign-in.php">SIGN UP</a></li>
                <li><a href="sign-in.php">LOGIN</a></li>
            <?php
                }
            ?>
        </ul>
</header>
    <h1><?php echo $product['name'] ?></h1>
    <p><?php echo $product['description'] ?></p>
    <p>Catégorie :
        <?php echo $link ?></p> <!--A FAIRE SEPARER LES CATEGORIES EN UTILISANT EXPLODE CAR GROUP CONCAT RENVOIE UNE STRING-->
</body>
</html>