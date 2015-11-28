<?php

include_once('modele/appartient_au_groupe.php');
include_once('modele/infos_user.php');
include_once('modele/infos_groupe.php');

// recherche de tous les users
$reqa = $bdd->prepare('SELECT id_user FROM users'); 
$reqa->execute();

/*
while($dest = $req->fetch())
{
	$id_destinataire = $dest['id_user'];
	echo $id_destinataire;
}*/

$i = 0;

while($dest = $reqa->fetch())
{
	$i++;

	$id_destinataire = $dest['id_user'];
	
	$mail_html = '<html><head></head><body>';
	$mail_texte = '';
	
	$infos_destinataire = infos_user($id_destinataire, $bdd);
	
	$mail_html .= 'Bonjour '.$infos_destinataire['prenom'].' !<br><br>';
	$mail_texte .= 'Bonjour '.$infos_destinataire['prenom'].' !\n\n';
	
	$mail_html .= 'Connectes-toi sur Potinoïd pour découvrir tous les potins de tes amis :<br><br>';
	$mail_texte .= 'Connectes-toi sur Potinoïd pour découvrir tous les potins de tes amis :\n\n';
	
	$groupes_user = groupes_d_un_user($id_destinataire, $bdd);
	
	if($groupes_user != 'rien_en_commun')
	{
		foreach ($groupes_user as $key => $value) {
			$groupe_courant = infos_groupe($value, $bdd);
			$nb_potins = nb_potins_ds_gpe($value, $bdd);
		
			$mail_html .= $groupe_courant['nom'].' : '.$nb_potins['COUNT(*)'].' potins !<br>';
			$mail_texte .= $groupe_courant['nom'].' : '.$nb_potins['COUNT(*)'].' potins !\n';
		}
	}
	else
	{
		$mail_html .= 'Aucun groupe';
		$mail_texte .= 'Aucun groupe';
	}
	
	// On cherche le nombre de potins concernant "user"
	$req = $bdd->prepare('SELECT COUNT(*) FROM cor_potin_users WHERE id_concerne = ?'); 
	$req->execute(array($id_destinataire));
	$donnees = $req->fetch();
	$req->closeCursor(); 
	
	if($donnees > 0)
	{
		$mail_html .= '<br>Il y a '.$donnees['COUNT(*)'].' potins sur toi !<br><br>
		Raconte des anecdotes sur tes amis, partage des photos dossier ou vote pour gagner des points et les débloquer !<br><br>';
	
		$mail_texte .= '<br>Il y a '.$donnees['COUNT(*)'].' potins sur toi !<br><br>
		Raconte des anecdotes sur tes amis, partage des photos dossier ou vote pour gagner des points et les débloquer !\n\n';
	}
	else
	{
		$mail_html .= '<br>(Raconte des anecdotes sur tes amis, partage des photos dossier, commente et vote !)<br>';
		$mail_texte .= '<br>(Raconte des anecdotes sur tes amis, partage des photos dossier, commente et vote !)\n';
	}
	
	$mail_html .= 'Alors à bientôt sur <a href="www.potinoid.fr">Potinoïd.fr</a> ! :)<br><br>';
	$mail_texte .= 'Alors à bientôt sur www.potinoid.fr ! :)\n\n';
	
	$req = $bdd->prepare('SELECT * FROM users WHERE id_user = ?'); 
	$req->execute(array($id_destinataire));
	$donnees = $req->fetch();
	$req->closeCursor();
	
	//$mail_html .= 'Rappel de tes identifiants : '.$donnees['mail'].' et '.$donnees['mdp'];
	//$mail_texte .= 'Rappel de tes identifiants : '.$donnees['mail'].' et '.$donnees['mdp'];
	
	$mail_html .= '</body></html>';
	
	
	
	$mail = $donnees['mail']; // Déclaration de l'adresse de destination.
	//$mail = 'vouillon.thibaud@gmail.com'; // Déclaration de l'adresse de destination.
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
	{
		$passage_ligne = "\r\n";
	}
	else
	{
		$passage_ligne = "\n";
	}
	//=====Déclaration des messages au format texte et au format HTML.
	//$message_txt = "Alerte, alerte, la mayonnique a pris le pouvoir !STOP.\nTout est hors contrôle, je répète, tout est hors contrôle.STOP.\nPour des mesures de sécurité, ce message s'enverra automatiquement toutes les minutes.STOP.\n";
	//$message_html = "<html><head></head><body>Alerte, alerte, la mayonnique a pris le pouvoir !STOP.<br>Tout est hors contrôle, je répète, tout est hors contrôle.STOP.<br>Pour des mesures de sécurité, ce message s'enverra automatiquement toutes les minutes.STOP.<br></body></html>";
	
	$message_txt = $mail_texte;
	$message_html = $mail_html;
	
	//==========
	 
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	 
	//=====Définition du sujet.
	$sujet = "Pleins de nouveaux potins sur Potinoid !";
	//=========
	 
	//=====Création du header de l'e-mail.
	//$header = "From: \"Comité Mayonnique\"<comite@mayonnique.fr>".$passage_ligne;
	//$header.= "Reply-to: \"Comité Mayonnique\" <comite@mayonnique.fr>".$passage_ligne;
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
	

	echo $infos_destinataire['prenom'].' '.$infos_destinataire['nom'].'<br>';

}

echo $i;

$reqa->closeCursor(); 

?>