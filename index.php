
<?php
require "./php/function.php";

$user = $_SESSION['membre']["login"] ?? "";
$currentUsers =  getUrrentUser($user);


    // var_dump($currentUser);
        if(isset($_SESSION['membre'])) {
        header('location:index.php');

    ?>
    	<!-- <a href="?action=deconnexion">Déconnexion</a>
		<br> -->
  
    
    <?php
        } else {
    ?>
    <div class="menu-connexion">

    <a href="./php/inscription.php" class="button">inscription</a>

    <a href="./php/connexion.php" class="button">connexion</a>
    </div>
    <?php
       }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">
    
    <title>Document</title>
</head>
<body>
    
    <script src="character_template.js"></script>
</body>
</html>

