<?php

if(isset($_GET['id_groupe']) && $_GET['id_groupe'] != '')
{
	if(isset($_GET['mdp']) && $_GET['mdp'] != '')
	{
		$id_groupe = $_GET['id_groupe'];
		$mdp = $_GET['mdp'];

		echo $mdp;

		$req = $bdd->prepare('SELECT mdp FROM users WHERE id_user = ?'); 
		$req->execute(array($id_user));
		$donnees = $req->fetch();
		$req->closeCursor();

		// Si le mdp est bon
		if(password_verify($mdp ,$donnees['mdp']))
		{
			//Enlever du groupe
			$req = $bdd->prepare('DELETE FROM cor_user_groupe WHERE id_user = ? AND id_groupe = ?'); 
			$req->execute(array($id_user, $id_groupe));
			$req->closeCursor();

			//enlever des potins du groupe
			$req = $bdd->prepare('DELETE cor_potin_users 
									FROM cor_potin_users 
									INNER JOIN potins
									ON cor_potin_users.id_potin = potins.id_potin
									WHERE cor_potin_users.id_concerne = ? AND potins.id_groupe = ?'); 
			$req->execute(array($id_user, $id_groupe));
			$req->closeCursor();

			//enlever commentaires
			$req = $bdd->prepare('DELETE commentaires 
									FROM commentaires 
									INNER JOIN potins
									ON commentaires.id_potin = potins.id_potin
									WHERE commentaires.id_auteur = ? AND potins.id_groupe = ?'); 
			$req->execute(array($id_user, $id_groupe));
			$req->closeCursor();
		}
		else
		{
			echo 'error_mdp';
		}
	}
	else
	{
		echo 'error_mdp';
	}
}