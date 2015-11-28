<?php
$num_page = $_GET['num_page'];

$limit = $_GET['limit'];

$offset = $limit*$num_page;



include_once('modele/rechercher_potins.php');
  $potins_cherches = rechercher_potins_des_groupes_de_user_offset($id_user, $limit, $offset, $bdd);
  
  $nb_potins = count($potins_cherches);

  if($potins_cherches != 'plus_de_potins' && $potins_cherches != 'pas_de_potins' && $potins_cherches != 'erreur')
  {



  foreach ($potins_cherches as $i => $potin_courant) {

    $potin_courant = infos_potin($potin_courant, $bdd);

    include('vue/afficher_potin_anonyme.php');

  }


	}
	else
	{
		echo 'Il n\'y a plus de potins à afficher';
	}



?>

<div id="fin-colonne-g"></div>