<?php
include_once('modele/requetes_vote.php');

	if(isset($_POST['vote_potin']) && ($_POST['vote_potin'] == 'ok')) // Si on a voté négativement
	{
		if(!deja_vote($_POST['numero_potin'], $id_user, $bdd)) // Si on a pas déjà voté
		{
			include_once('modele/infos_potin.php');

			if($id_user != auteur_du_potin($_POST['numero_potin'], $bdd)) // Si on est pas l'auteur du potin
			{
				if(isset($_POST['vote_positif']) && ($_POST['vote_positif'] == 'vote_positif'))
				{
					$req = $bdd->prepare('INSERT INTO vote_potin(id_auteur, id_potin, valeur) VALUES(?, ?, ?)'); 
	    			$req->execute(array($id_user, $_POST['numero_potin'], 1));
				}
				else if(isset($_POST['vote_negatif']) && ($_POST['vote_negatif'] == 'vote_negatif'))
				{
					$req = $bdd->prepare('INSERT INTO vote_potin(id_auteur, id_potin, valeur) VALUES(?, ?, ?)'); 
	    			$req->execute(array($id_user, $_POST['numero_potin'], -1));
				}
				else if(isset($_POST['vote_neutre']) && ($_POST['vote_neutre'] == 'vote_neutre'))
				{
					$req = $bdd->prepare('INSERT INTO vote_potin(id_auteur, id_potin, valeur) VALUES(?, ?, ?)'); 
	    			$req->execute(array($id_user, $_POST['numero_potin'], 0));
				}

				// On ajoute un point
    			$req = $bdd->prepare('INSERT INTO points(id_user, valeur, date) VALUES(?, ?, NOW())'); 
    			$req->execute(array($id_user, 1));

				// On regarde si l'auteur a gagné des points
				
			}
			else
			{
				echo 'Vous ne pouvez pas voter sur ce potin car vous en êtes l\'auteur';
			}
		}
		else
		{
			echo 'Vous avez déjà voté pour ce potin !';
		}
	}


?>