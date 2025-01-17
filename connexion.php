<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=imagine', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4')); //array (MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4' permet d'afficher)
} catch (PDOException $e) {
    echo $e->getMessage();
}