<?php
include_once(ROOTPATH.'/modele/requetes_vote.php');

$nb_votes_pos = nb_votes_positif($potin_courant['id_Potin'], $bdd);
$nb_votes_neut = nb_votes_ne_sait_pas($potin_courant['id_Potin'], $bdd);
$nb_votes_neg = nb_votes_negatif($potin_courant['id_Potin'], $bdd);

$nb_de_votants = nb_votants($potin_courant['id_Potin'], $bdd);

if($nb_de_votants != 0)
{
	$barre_positif = $nb_votes_pos/$nb_de_votants*100;
	$barre_negatif = $nb_votes_neg/$nb_de_votants*100;
	$barre_neutre = $nb_votes_neut/$nb_de_votants*100;
}
else
{
	$barre_positif = 0;
	$barre_negatif = 0;
	$barre_neutre = 0;
}

?>


<div class="progress">
  <div class="progress-bar progress-bar-success" style="width: <?php echo $barre_positif; ?>%">
    <span class=""><?php echo $nb_votes_pos; ?></span>
  </div>
  <div class="progress-bar progress-bar-danger" style="width: <?php echo $barre_negatif; ?>%">
    <span class=""><?php echo $nb_votes_neg; ?></span>
  </div>
  <div class="progress-bar progress-bar-info" style="width: <?php echo $barre_neutre; ?>%">
    <span class=""><?php echo $nb_votes_neut; ?></span>
  </div>
</div>