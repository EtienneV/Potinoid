<?php

$mail = $_POST['mail'];


// ---------------------------------------------------------------------
//	Générer un mot de passe aléatoire
// ---------------------------------------------------------------------
function genererMDP ($longueur){
	// initialiser la variable $mdp
	$mdp = "";
 
	// Définir tout les caractères possibles dans le mot de passe, 
	// Il est possible de rajouter des voyelles ou bien des caractères spéciaux
	$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
 
	// obtenir le nombre de caractères dans la chaîne précédente
	// cette valeur sera utilisé plus tard
	$longueurMax = strlen($possible);
 
	if ($longueur > $longueurMax) {
		$longueur = $longueurMax;
	}
 
	// initialiser le compteur
	$i = 0;
 
	// ajouter un caractère aléatoire à $mdp jusqu'à ce que $longueur soit atteint
	while ($i < $longueur) {
		// prendre un caractère aléatoire
		$caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);
 
		// vérifier si le caractère est déjà utilisé dans $mdp
		if (!strstr($mdp, $caractere)) {
			// Si non, ajouter le caractère à $mdp et augmenter le compteur
			$mdp .= $caractere;
			$i++;
		}
	}
 
	// retourner le résultat final
	return $mdp;
}


function envoi_mail_mdp($mail, $mdp)
{
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
	{
		$passage_ligne = "\r\n";
	}
	else
	{
		$passage_ligne = "\n";
	}
	//=====Déclaration des messages au format texte et au format HTML.
	$message_txt = "Bonjour,\n\nVoici tes nouveaux identifiants pour accéder à Potinoid : \nMail : ".$mail." \nMot de passe : ".$mdp."\n\nConnecte-toi vite sur www.potinoid.fr !\n";
	$message_html = "<html><head></head><body>Bonjour,<br><br>Voici tes nouveaux identifiants pour accéder à Potinoïd : <br>Mail : ".$mail." <br>Mot de passe : ".$mdp.'<br><br>Connecte-toi vite sur <a href="http://www.potinoid.fr">www.potinoid.fr</a> !<br></body></html>';
	//==========
	 
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	 
	//=====Définition du sujet.
	$sujet = "Nouveau mot de passe Potinoid";
	//=========
	 
	//=====Création du header de l'e-mail.
	$header = "From: \"Potinoid\"<noreply@potinoid.fr>".$passage_ligne;
	$header.= "Reply-to: \"Potinoid\" <noreply@potinoid.fr>".$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	//==========
	 
	//=====Création du message.
	$message = $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format texte.
	$message.= "Content-Type: text/plain; charset=\"utf-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_txt.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format HTML
	$message.= "Content-Type: text/html; charset=\"utf-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_html.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	//==========
	 
	//=====Envoi de l'e-mail.
	mail($mail,$sujet,$message,$header);
	//==========
}



// Vérification si le mail existe
$req = $bdd->prepare('SELECT COUNT(*) FROM users WHERE mail = ?'); 
$req->execute(array($mail));
$donnees = $req->fetch();
$req->closeCursor();

if($donnees['COUNT(*)'] != 0)// Si oui
{
	// Generation mdp
	$mdp = genererMDP(8);

	$mdp_crypte = password_hash($mdp, PASSWORD_DEFAULT);

	// Envoi par mail
	envoi_mail_mdp($mail, $mdp);

	// Changement du mdp
	$req = $bdd->prepare('UPDATE users SET mdp = ? WHERE mail = ?'); 
    $req->execute(array($mdp_crypte, $mail));
    $req->closeCursor();

	// Envoi de confirmation de succes
	echo 'ok';
}
else // Si non
{
	echo 'ko'; // Envoi message d'erreur
}




