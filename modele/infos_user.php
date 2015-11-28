<?php

function infos_user($id_user, $bdd)
{
	$req = $bdd->prepare('SELECT id_user, nom, prenom, description, avatar FROM users WHERE id_user = ?'); 
	$req->execute(array($id_user));
	$donnees = $req->fetch();
	$req->closeCursor();

	return $donnees;
}

// Doublon : dans infos_groupe
function nb_potins_user_dans_groupe($id_user, $id_groupe, $bdd)
{
	$req = $bdd->prepare('SELECT COUNT(*)
							FROM cor_potin_users
							INNER JOIN potins
							ON potins.id_potin = cor_potin_users.id_potin
							WHERE id_concerne = ? AND id_groupe = ?'); 
	$req->execute(array($id_user, $id_groupe));
	$donnees = $req->fetch();
	$req->closeCursor();

	return $donnees['COUNT(*)'];
}

function nb_potins_sur_user($id_user)
{
	global $bdd;

	// On cherche le nombre de potins concernant "user"
    $req = $bdd->prepare('SELECT COUNT(*) FROM cor_potin_users WHERE id_concerne = ?'); 
    $req->execute(array($id_user));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['COUNT(*)'];
}

function nb_jours_derniere_connexion($id_user, $bdd) // Depuis combien de temps l'user ne s'est pas connecté ?
{
	// On compte le nb de fois qu'il s'est connecté
	$req = $bdd->prepare('SELECT COUNT(*)
    							FROM connexions 
    							WHERE id_user = ?'); 
	$req->execute(array($id_user));
	$donnees = $req->fetch();
	$req->closeCursor();

	if($donnees['COUNT(*)'] != 0) // Si l'utilisateur s'est déjà connecté
	{

		$req = $bdd->prepare('SELECT DATEDIFF(NOW(), MAX(connexions.date)) AS difference
	    							FROM connexions 
	    							WHERE id_user = ?'); 
		$req->execute(array($id_user));
		$donnees = $req->fetch();
		$req->closeCursor();

		return $donnees['difference'];
	}
	else
	{
		return 'jamais_connecte';
	}
}

?>