<?php
include_once('controleur/includes/fonctions/calculs_points.php');
include_once('modele/rechercher_potins.php');
include_once('vue/potin/affichage_potin.php');
include_once('modele/potin_externe.php');
include_once('modele/pare_feu.php');

$id_concerne = $_POST['id_concerne'];
$id_groupe = $_POST['id_groupe'];

$reponse_potin = NULL;
$reponse_message = NULL;


$nb_potins_sur_user_ds_gp_courant = nb_potins_sur_user_ds_gpe($id_groupe, $id_concerne, $bdd);

// On cherche les potins qu'on a découverts
$potins_decouverts = potins_decouverts_sur_user_dans_gpe($id_concerne, $id_groupe, $id_user);


// Vérification de l'autorisation de voir ce potin
if(est_autorise($id_user, $id_concerne, $id_groupe))
{

	if($potins_decouverts != 0) // Si on a déjà découvert des potins
	{
		$nb_potins_a_decouvrir = $nb_potins_sur_user_ds_gp_courant - sizeof($potins_decouverts);
	}
	else
	{
		$nb_potins_a_decouvrir = $nb_potins_sur_user_ds_gp_courant;
	}

	if($nb_potins_a_decouvrir > 0)// Si il y a des potins à découvrir
	{
	 	if(calculer_points($id_user, $bdd) >= 20) // Si on a assez de points
	 	{
	 		$tous_potins = tous_potins_sur_user_ds_gpe($id_groupe, $id_concerne);

	 		if($potins_decouverts != 0) // Si on a déjà découvert des potins
	 		{
	 			// Il faut les retirer des autres
	 			$potins_cherches = array_diff($tous_potins, $potins_decouverts);
	 		}
	 		else
	 		{
	 			// Sinon on prend tous les potins
	 			$potins_cherches = $tous_potins;
	 		}

	 		// On choisit un potin au hasard dans ceux qu'on a pas encore découverts
	 		$potin_au_pif = $potins_cherches[array_rand($potins_cherches)];

	 		$potin_decouvert = infos_potin($potin_au_pif, $bdd);

	 		// On enregistre le potin comme découvert
	 		potin_externe_decouvert($potin_decouvert['id_Potin']);
	  
	      	$reponse_potin = vue_affichage_potin($potin_decouvert, $id_user, $bdd);
	      	$reponse_message = 'succes';
	 	}
	 	else // Sinon
	 	{
	 		$reponse_message = 'pas_points';
	 	}
	}
	else // Sinon
	{
	 	$reponse_message = 'pas_potins';
	}
}
else
{
	$reponse_message = 'non_autorise';
}

// Construction de la réponse JSON
$reponse['potin'] =$reponse_potin;
$reponse['message'] =$reponse_message;
echo json_encode($reponse);