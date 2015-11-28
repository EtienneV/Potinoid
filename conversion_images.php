<?php
include('modele/image/modifications_image.php');

set_time_limit (0);

echo 'Debut conversion ...<br>';

// Pour chaque user
$req = $bdd->prepare('SELECT * FROM users where id_user = 90'); 
$req->execute();

while ($donnees = $req->fetch()) {
	echo $donnees['prenom'].' ';

	// Si il a une photo de profil
	if($donnees['avatar'] != '')
	{
		$source = imagecreatefromjpeg($donnees['avatar']); // Chargement de la photo

		$nom_image = make_tous_profiles($source); //Conversion de la photo

		// Rentrage du nom dans la bdd
		$req2 = $bdd->prepare('UPDATE users SET avatar = ? WHERE id_user = ?'); 
		$req2->execute(array($nom_image, $donnees['id_user']));
		$req2->closeCursor();

		echo 'OK';
	}
	echo '<br>';
}

$req->closeCursor();

echo 'Terminé !';

