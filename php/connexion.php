<?php

require "function.php";

//si la session membre existe alors je redirige vers l'acceuil:

if(isset($_SESSION['membre'])) {
    header('location:../home.php');
}
//si le form est posté:

if($_POST) {
    
    //je vérifie si je réupere bien les infos
    // var_dump($_POST);

    //je récupere les infos correspondants à l'email dans la table
    $r = $pdo->query("SELECT * FROM joueur where login= '$_POST[login]'");

    //si le nb de résultat est plus grans que 1 , alors le compte existe:
    if($r->rowCount() >=1) {
        //je stocke toutes les infos sous forme d'array
        $membre= $r->fetch(PDO::FETCH_ASSOC);
        // je verifie 
        // print_r($membre);
        //si le mdp correspond à celui présent dans $membre:
        if(password_verify($_POST['mdp'], $membre['mdp'])) {
            //je teste si le mdp fonctionne:
            $content .= '<p>email + MDP : ok</p>';
            //j'enregistre les infos dans la session:
            $_SESSION['membre']['pseudo'] = $membre['pseudo'];
            $_SESSION['membre']['login'] = $membre['login'];

            //je redirige vers la page d'acceuil
            header('location:../home.php');

        } else {
            //le mdp est incorrect :
            $content .= '<p>Mot de passe incorrect</p>';
        }
    } else {
        $content .= '<p>compte inexistant</p>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style_login.css">
    <title>Connexion</title>
</head>
<body  class="page-game">
<div class="container_login">
    <?php echo $content;?>
    <div id="container_connexion">
        <h1>CHINO BATTLE</h1>
        <div class="container_img">
            <div class="container_form">
            <form class="form_connexion" method="post">
                <div class="identifiant">
                    <label for="login">Identifiant</label>
                    <input type="login" name="login" id="login" placeholder="Identifiant" required>
                </div>
                <div class="mdp">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" name="mdp" id="mdp" placeholder="Mot de passe" required>
                    <p class="mdp-oubli">Mot de passe oublie</p>
                    <p class="inscrire"><a href="inscription.php">S'inscrire</a> </p>
                </div>
                
            </form>
        </div>
        
    </div>
    </div>

    </div>
    <script src="game.js"></script> 
</body>
</html>