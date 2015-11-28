<?php

$num_page = $_GET['num_page'];
$limit = $_GET['limit'];
$id_groupe = $_GET['id_groupe'];

$offset = $limit*$num_page;

include_once('modele/rechercher_potins.php');
include_once('vue/potin/affichage_potin.php');
include_once('vue/potin/potin_v4.php');

$reponse = NULL;
$j = 0;

$potins_cherches = rechercher_potins_d_un_groupe_de_user($id_user, $id_groupe, $bdd);

if($potins_cherches != 0) // Si on a des potins
{
  $potins_cherches = array_slice ($potins_cherches, $offset, $limit);

  foreach ($potins_cherches as $i => $potin_courant) {

    $potin_courant = infos_potin($potin_courant, $bdd);

    $reponse['potin'.$j] = vue_potin_v4($potin_courant, $id_user, $bdd);
    $j++;
  }

  $reponse['nb_potins'] = $j;
}
else
{
  $reponse['nb_potins'] = 0;
}

echo json_encode($reponse);