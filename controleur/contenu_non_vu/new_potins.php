<?php

$potins_non_vus = unserialize($_GET['potins']);

echo '<div class="page-header">
  <h2>Quoi de neuf ?</h2>
  <div id="pac-charger">Chargement ... <img src="images/ajax-loader.gif"></img></div>
</div>';

include_once('vue/potin/affichage_potin.php');
include_once('modele/infos_potin.php');

echo '<div class="wrapper-potins">';

foreach ($potins_non_vus as $i => $potin_courant) {
  $potin_courant = infos_potin($potin_courant, $bdd);

  echo vue_affichage_potin($potin_courant, $id_user, $bdd);
}

echo '</div>';



?>