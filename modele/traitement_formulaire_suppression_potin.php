<?php
/**
Vérifier que le potin est bien l'oeuvre de user !!!
*/


if(isset($_POST['suppr_potin']) && ($_POST['suppr_potin'] == 'ok')) // Si l'user supprime un de ses potin
{    
  include_once('modele/supprimer_potin.php');
  supprimer_referencement_potin($_POST['numero_potin'], $bdd);
}
?>