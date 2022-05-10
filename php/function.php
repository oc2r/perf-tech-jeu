<?php
require "init.php";

function getUrrentUser(string $pseudo){
    global $pdo;
    $currentUser = $pdo->query("SELECT * FROM joueur WHERE pseudo = '$pseudo'");
    $currentUser->execute();
    return $currentUser->fetch();
}

?>