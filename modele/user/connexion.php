<?php

function nb_connexions_day($id_user)
{
	global $bdd;

	$req = $bdd->prepare('SELECT COUNT(*)
							FROM connexions
							WHERE id_user = ? AND DATE(date) = CURDATE()'); 
	$req->execute(array($id_user));
	$donnees = $req->fetch();
	$req->closeCursor();

	return $donnees['COUNT(*)'];
}

function enregistrement_connexion($id_user)
{
	global $bdd;

	$req = $bdd->prepare('INSERT INTO connexions(id_user, date) VALUES(?,NOW())'); 
    $req->execute(array($id_user));
}
