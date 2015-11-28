<?php

$num_page = $_GET['num_page'];
$limit = $_GET['limit'];

$offset = $limit*$num_page;

include_once('modele/rechercher_potins.php');
include_once('vue/potin/affichage_potin.php');
include_once('vue/potin/potin_v4.php');
include_once('vue/potin/potin_v4_brouille.php');
include_once('modele/infos_potin.php');

$reponse = NULL;
$j = 0;

$potins_cherches = rechercher_potins_sur_user($id_user, $bdd);

if($potins_cherches != 0) // Si on a des potins
{
  $potins_cherches = array_slice ($potins_cherches, $offset, $limit);

  foreach ($potins_cherches as $i => $potin_courant) {

    $potin_courant = infos_potin($potin_courant, $bdd);

    //$reponse['potin'.$j] = vue_potin_v4($potin_courant, $id_user, $bdd);

    $reqa = $bdd->prepare('SELECT decouvert FROM cor_potin_users WHERE id_concerne = ? AND id_potin = ?');     
   	$reqa->execute(array($id_user, $potin_courant['id_Potin']));
   	$donneesa = $reqa->fetch();
   	$reqa->closeCursor();   	

	if (($donneesa['decouvert'] == 1) || (auteur_du_potin($potin_courant['id_Potin'], $bdd) == $id_user)) { // Si on l'a écouvert ou qu'on en est l'auteur
		$reponse['potin'.$j] =  vue_potin_v4($potin_courant, $id_user, $bdd);
	}
	else
	{
 		$reponse['potin'.$j] =  vue_potin_v4_brouille($potin_courant, $id_user, $bdd);
 	}

    $j++;
  }

  $reponse['nb_potins'] = $j;
}
else
{
  $reponse['nb_potins'] = 0;
}

echo json_encode($reponse);