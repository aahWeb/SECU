<?php

$user = "root";
$pass = "antoine";

$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $dbh = new PDO('mysql:host=localhost;dbname=db_blog', $user, $pass, $options);

} catch (PDOException $e) {
    echo "error MySQL connect";

    exit;
}

$username = "alan";
$password = "' or 1 -- ";

$sql = "SELECT * FROM `users` WHERE `username`='$username' AND `password`='$password'" ;
echo $sql ;
// SELECT * FROM `users` WHERE `username`='alan' AND `password`='' or 1 -- '
$sth = $dbh->query($sql);

var_dump($sth->fetch() );


// Solution méthode prepare

/**
 * Exercice
 * Trouver/rechercher une solution pour vous protégez de ce type d'attaque 
 * 
 */