
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style_login.css">
    
    <title>Document</title>
</head>
<body class="index_body">

<div class="container_homepage">
    <div class="container_home">

        <div class="container_hometext">



        <?php
        require "./php/function.php";

        $user = $_SESSION['membre']["login"] ?? "";
        $currentUsers =  getUrrentUser($user);


        
                if(isset($_SESSION['membre'])) {
                header('location:index.php');

            ?>
         
            <?php
                } else {
            ?>
            <div class="menu-connexion">

            <a href="./php/inscription.php" class="button">Inscription</a>

            <a href="./php/connexion.php" class="button">Connexion</a>
            </div>
            <?php
            }
            ?>
        </div>   
    </div>
</div>   
    <script src="character_template.js"></script>
</body>
</html>

