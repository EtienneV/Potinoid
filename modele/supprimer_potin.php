<?php
function supprimer_referencement_potin($id_potin, $bdd)
{
	$req = $bdd->prepare('DELETE FROM cor_potin_users WHERE id_potin = ?');
	$req->execute(array($id_potin));
	$req->closeCursor();
}

?>