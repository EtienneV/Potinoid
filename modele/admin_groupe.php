<?php

function role_gpe($id_user, $id_groupe, $bdd)
{
	$req = $bdd->prepare('SELECT * FROM cor_user_groupe WHERE id_groupe = ? AND id_user = ?'); 
    $req->execute(array($id_groupe, $id_user));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['role'];
}

?>