<div class="page-header">
  <h2>Groupes sur Potinoïd</h2>
  <div id="pac-charger">Chargement ... <img src="images/ajax-loader.gif"></img></div>
</div>

<?php

echo '<a href="'.INDEX.'?page=creer_groupe"><span class="glyphicon glyphicon-asterisk"></span> Créer un groupe</a><br><br>';

include_once('modele/infos_groupe.php');
$groupes = infos_tous_groupes($bdd);

foreach ($groupes as $i => $groupe_courant) {
	$groupe_courant = infos_groupe($groupe_courant, $bdd);

	echo '<b><a href="'.INDEX.'?page=groupe&id_groupe='.htmlspecialchars($groupe_courant['id_groupe']).'">'.$groupe_courant['nom'].'</a></b><br>';
	echo $groupe_courant['description'].'<br><br>';
}

?>