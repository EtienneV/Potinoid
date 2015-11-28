<?php

function est_autorise($id_user, $id_concerne, $id_groupe)
{
	global $bdd;

	// Si il est bloqué sur l'user -> Non
	if(user_est_bloque_sur_user($id_user, $id_concerne)) return false;
	// Sinon, si il est bloqué sur le groupe pour l'user -> Non
	else if(user_est_bloque_sur_gp_par_user($id_user, $id_concerne, $id_groupe)) return false;
	// Si le bloquage du groupe est fait par un admin
	else if(user_est_bloque_sur_gp_par_gp($id_user, $id_groupe)) return false;
	// Sinon, OK
	else return true;
}

function user_est_bloque_sur_user($id_user, $id_concerne)
{
	global $bdd;

	$req = $bdd->prepare('SELECT COUNT(*) 
							FROM pare_feu
							WHERE id_protege = ? AND type_protege = "user" AND id_limite = ? AND type_limite = "user"'); 
	$req->execute(array($id_concerne, $id_user));
	$donnees = $req->fetch();
	$req->closeCursor();

	if($donnees['COUNT(*)'] > 0)
	{
		return true;
	}
	else return false;
}

function user_est_bloque_sur_gp_par_user($id_user, $id_concerne, $id_groupe)
{
	global $bdd;

	$req = $bdd->prepare('SELECT COUNT(*) 
							FROM pare_feu
							WHERE id_demandeur = ? AND type_demandeur = "user" AND id_protege = ? AND type_protege = "groupe" AND id_limite = ? AND type_limite = "user"'); 
	$req->execute(array($id_concerne, $id_groupe, $id_user));
	$donnees = $req->fetch();
	$req->closeCursor();

	if($donnees['COUNT(*)'] > 0)
	{
		return true;
	}
	else return false;
}

function user_est_bloque_sur_gp_par_gp($id_user, $id_groupe)
{
	global $bdd;

	$req = $bdd->prepare('SELECT COUNT(*) 
							FROM pare_feu
							WHERE id_demandeur = ? AND type_demandeur = "groupe" AND id_protege = ? AND type_protege = "groupe" AND id_limite = ? AND type_limite = "user"'); 
	$req->execute(array($id_groupe, $id_groupe, $id_user));
	$donnees = $req->fetch();
	$req->closeCursor();

	if($donnees['COUNT(*)'] > 0)
	{
		return true;
	}
	else return false;
}