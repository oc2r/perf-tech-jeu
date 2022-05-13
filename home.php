<?php
require "./php/function.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">
    <title>Page d'accueil</title>
</head>
<body>
    <?php

$user = $_SESSION['membre']["login"] ?? "";
$currentUsers =  getUrrentUser($user);

    // var_dump($currentUser);
        if(isset($_SESSION['membre'])) {
        
    ?>
        <h2>Bonjour <?php echo $_SESSION['membre']['pseudo'];?> !</h2>
    <?php
    
        
       }
    ?>


<div class="deco">
    <a href="?action=deconnexion" class="btn_deco">Déconnexion</a>
    </div>