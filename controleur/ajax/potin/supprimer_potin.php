<?php

/**
Vérifier que le potin est bien l'oeuvre de user !!!
*/

include_once('modele/supprimer_potin.php');
supprimer_referencement_potin($_POST['id_potin'], $bdd);

?>