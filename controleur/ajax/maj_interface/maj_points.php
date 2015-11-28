<?php

include_once('controleur/includes/fonctions/calculs_points.php');

echo '<span class="badge points-user encart-usr-points" data-toggle="tooltip" data-placement="right" title="Dès 10 points, 1 potin !">'.calculer_points($id_user, $bdd).' points</span>';

?>