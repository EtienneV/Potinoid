<?php

// Si on reçoit les infos du formulaire de connexion
if(isset($_POST['val_connexion']) && ($_POST['val_connexion'] == 'ok'))
{
	// Traitement du formulaire de connexion

	if(isset($_POST['mail']) && ($_POST['mail'] != '')) //On regarde si on a bien reçu un mail
	{
		$mail = $_POST['mail'];
		// On teste si le mail n'est bien présent qu'une seule fois
		$req = $bdd->prepare('SELECT COUNT(*) AS nbr FROM users WHERE mail = ?'); 
        $req->execute(array($mail));
        $retour = $req->fetch();
        $req->closeCursor();

		if($retour['nbr'] == 0)
		{
			$erreur_mail_existe = 1;
		}
		else // Si le mail n'est présent qu'une fois dans la bdd
		{
			if(isset($_POST['mdp']) && ($_POST['mdp'] != '')) // Si on a reçu un mot de passe
			{
				// On récupère la ligne correspondant à l'utilisateur
				$req = $bdd->prepare('SELECT * FROM users WHERE mail = ?'); 
		        $req->execute(array($mail));
		        $retour = $req->fetch();
		        $req->closeCursor();

				//if($_POST['mdp'] == $retour['mdp']) // On vérifie que le mdp est bien le bon
				//if(password_verify ( $retour['mdp'] , $_POST['mdp'] ))
				if(password_verify($_POST['mdp'] ,$retour['mdp']))
				{
					// On crée la session
					$_SESSION['membre_id'] = $retour['id_user'];
					$_SESSION['membre_mdp'] = $retour['mdp'];
					$_SESSION['membre_prenom'] = $retour['prenom'];
					$_SESSION['membre_nom'] = $retour['nom'];

					// On ajoute un point si c'est la première connexion de l'user dans la journée
					include_once('modele/user/points.php');
					add_pt_connexion_du_jour($retour['id_user']);

					// On enregistre la connexion
					include_once('modele/user/connexion.php');
      				enregistrement_connexion($retour['id_user']);
					
					// On active les cookies si on en a fait la demande
					if(isset($_POST['cookie']) && $_POST['cookie'] == 'on')
					{
						setcookie('membre_id', $retour['id_user'], time()+365*24*3600, '/', '', '', '');
						setcookie('membre_mdp', $retour['mdp'], time()+365*24*3600, '/', '', '', '');
					}


					header('Location: '.ROOTPATH.'/'.INDEX);
					exit();
				}
				else
				{
					$erreur_mauvais_mdp = 1;
				}
			}
			else
			{	
				$erreur_pas_mdp = 1;
			}
		}
	}
	else
	{
		$erreur_pas_mail = 1;
	}
}

include('controleur/page_connexion.php'); // Si il y a une erreur de connexion
?>