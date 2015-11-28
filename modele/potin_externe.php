<?php

function potin_externe_decouvert($id_potin)
{
	global $bdd;
	global $id_user;

	$req = $bdd->prepare('INSERT INTO potins_externes(id_user, id_potin, date) VALUES(?, ?, NOW())'); 
  	$req->execute(array($id_user, $id_potin));
}