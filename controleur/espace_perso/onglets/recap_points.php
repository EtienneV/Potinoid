<?php
include_once('controleur/includes/fonctions/calculs_points.php');

echo '<h3>Tu as '.calculer_points($id_user, $bdd).' points !</h3>';

echo '<p>';
echo '<ul>';

echo '<li>Cadeaux / Bonus : <strong>+'.points_cadeau($id_user, $bdd).' points</strong><br></li>';

echo '<li>Votes : <strong>+'.points_votes($id_user, $bdd).' points</strong><br></li>';

echo '<li>Potins publiés : <strong>';
$points_potins = points_potins($id_user, $bdd);
if($points_potins >= 0) echo '+';
echo $points_potins.' points</strong><br></li>';

echo '<li>Connexions : <strong>+'.points_connexions($id_user, $bdd).' points</strong><br></li>';

echo '<br>';

echo '<li>Potins découverts : <strong>-'.(nb_potins_decouverts($id_user, $bdd)*10).' points</strong><br></li>';

echo '<li>Potins découverts dans d\'autres groupes: <strong>-'.(nb_potins_externes_decouverts($id_user, $bdd)*20).' points</strong><br></li>';

echo '</ul>';
echo '</p>';

echo '<hr>';

echo '<h3>Comment gagner des points ?</h3>';

echo '<p><ul>';

echo '<li>En votant sur un potin : <strong>+1 point !</strong></li>';

echo '<li>En publiant un potin : en fonction des votes de tes amis, <strong>+10 à -5 points </strong></li>';

echo '<li>En te connectant : <strong>+1 point</strong> par jour où tu te connectes</li>';

echo '</ul></p>';

echo '<hr>';

echo '<h3>Que faire de mes points ?</h3>';

echo '<p><ul>';

echo '<li><strong>Avec 10 points</strong> : Découvrir un potin te concernant, sur <a href="'.INDEX.'?page=page_perso&onglet=potins_sur_moi"><strong>cette page</strong></a></li>';

echo '<li><strong>Avec 20 points</strong> : Découvrir un potin concernant un ami, dans un groupe dont tu ne fais pas partie.</li>';

echo '</ul></p>';

?>
