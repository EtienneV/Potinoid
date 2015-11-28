<?php

if(isset($_GET['type']) && $_GET['type'] != '')
{
	if(isset($_GET['mdp']) && $_GET['mdp'] != '')
	{
		$type = $_GET['type'];
		$mdp = $_GET['mdp'];

		$req = $bdd->prepare('SELECT mdp FROM users WHERE id_user = ?'); 
		$req->execute(array($id_user));
		$donnees = $req->fetch();
		$req->closeCursor();

		// Si le mdp est bon
		if(password_verify($mdp ,$donnees['mdp']))
		{
			//Enlever des groupes
			$req = $bdd->prepare('DELETE FROM cor_user_groupe WHERE id_user = ?'); 
			$req->execute(array($id_user));
			$req->closeCursor();

			//enlever des potins
			$req = $bdd->prepare('DELETE FROM cor_potin_users WHERE id_concerne = ?'); 
			$req->execute(array($id_user));
			$req->closeCursor();

			//enlever commentaires
			$req = $bdd->prepare('DELETE FROM commentaires WHERE id_auteur = ?'); 
			$req->execute(array($id_user));
			$req->closeCursor();

			if($type == 'tout')
			{
				//enlever user
				$req = $bdd->prepare('DELETE FROM users WHERE id_user = ?'); 
				$req->execute(array($id_user));
				$req->closeCursor();
			}

			vider_cookie();

			$_SESSION = array();
			session_destroy();
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