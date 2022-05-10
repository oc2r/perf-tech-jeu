<?php

require "function.php";



// Si la session membre existe, alors je redirige vers l'accueil :
if(isset($_SESSION['membre'])) {
	header('location:index.php');
}

// Si le form a été posté :
if($_POST) {
	// Je vérifie si je récupère bien les valeurs des champs :
	// print_r($_POST);

	// Je défini une variable pour afficher les erreurs :
	$erreur = '';

	// Si le prenom n'est pas trop court ou trop long :
	if(strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 20) {
		$erreur .= '<p>Taille de prénom invalide.</p>';
	}

    if(strlen($_POST['login']) < 3 || strlen($_POST['login']) > 40) {
		$erreur .= '<p>Taille de pseudo invalide.</p>';
	}

	// Si les caractères utilisées dans le champs prénoms sont valides :
	if(!preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo'])) {
		$erreur .= '<p>Format de prénom invalide.</p>';
	}

    // Si les caractères utilisées dans le champs prénoms sont valides :
	if(!preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['login'])) {
		$erreur .= '<p>Format de pseudo invalide.</p>';
	}

	// Je vérifie si le pseudo n'est pas déjà présent dans la base :
	$r = $pdo->query("SELECT * FROM joueur WHERE pseudo = '$_POST[pseudo]'");
	// S'il y a un ou plusieurs résultats :
	if($r->rowCount() >= 1) {
		$erreur .= '<p>pseudo déjà utilisé.</p>';
	}

    $r = $pdo->query("SELECT * FROM joueur WHERE login = '$_POST[login]'");
	// S'il y a un ou plusieurs résultats :
	if($r->rowCount() >= 1) {
		$erreur .= '<p>login déjà utilisé.</p>';
	}

	// Je gère les problèmes d'apostrophes pour chaque champs grâce à une boucle :
	foreach($_POST as $indice => $valeur) {
		$_POST[$indice] = addslashes($valeur);
	}

	// Je hash le mot de passe :
	$_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

	// Si $erreur est vide (fonction empty() vérifie si une variable est vide) :
	if(empty($erreur)) {
		// J'envois les infos dans la table en BDD :
		$pdo->exec("INSERT INTO joueur (login, pseudo, mdp) VALUES ('$_POST[login]', '$_POST[pseudo]', '$_POST[mdp]')");
		// J'ajoute un message de validation :
		$content .= '<p>Inscription validée !</p>';

		header('location:connexion.php');
	}

	// J'ajoute le contenu de $erreur à l'interieur de $content :
	$content .= $erreur;
	if (!$erreur) {
		$CurrentUser = $pdo->query("SELECT * FROM joueur WHERE pseudo = '$_POST[pseudo]'");
	}

}
?>

<?php echo $content; ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link rel="stylesheet" href="style2.css">
     <title>Inscription</title>
 </head>
 <body>
     <h1>Insription</h1>

     <h3>Veuillez rentrer les champs ci dessous</h3>
	 <div class="inscription-form">
	 	<form method="post">
            <label for="login">Login</label>
            <input type="text" name = "login" required>
            <br><br>
			<label for="pseudo">pseudo</label>
			<input type="text" name="pseudo" id="pseudo" required>
			<br><br>
			<label for="mdp">Mot de passe</label>
			<input type="password" name="mdp" id="mdp" required>
			<br><br>
			<input type="submit" class="button" value="S'inscrire">
		</form>
	 </div>
 </body>
 </html>