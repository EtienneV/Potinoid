<?php
include_once('controleur/includes/fonctions/calculs_points.php');

if(isset($_POST['decouvrir_potin']) && ($_POST['decouvrir_potin'] == 'ok'))
{
	if(calculer_points($id_user, $bdd) >= 10) // Si on a assez de points
	{
		if(isset($_POST['numero_potin']))
		{
			$req = $bdd->prepare('UPDATE cor_potin_users SET decouvert = 1 WHERE id_potin = ? AND id_concerne = ?'); 
	    	$req->execute(array($_POST['numero_potin'], $id_user));
		}
	}
}

?>