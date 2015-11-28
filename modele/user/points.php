<?php

include_once('modele/user/connexion.php');

function add_pt_connexion_du_jour($id_user)
{
	global $bdd;

	if(nb_connexions_day($id_user) == 0) // Si l'user ne s'est pas déjà connecté aujourd'hui
	{
		// On lui ajoute un point
		$req = $bdd->prepare('INSERT INTO points(id_user, valeur, date, nature) VALUES(?, 1, NOW(), "connexion")'); 
  		$req->execute(array($id_user));
	}
}