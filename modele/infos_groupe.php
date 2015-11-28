<?php
include_once('modele/admin_groupe.php');
include_once('modele/rechercher_user.php');

function infos_tous_groupes($bdd)
{
	$i = 0;

    $req = $bdd->prepare('SELECT * FROM groupes ORDER BY nom ASC'); 
	$req->execute(array());
    while($donnees = $req->fetch()) 
    {
    	$groupes_cherches[$i] = $donnees['id_groupe'];
		$i++;
    }
    $req->closeCursor();

    if(isset($groupes_cherches))
	{
		return $groupes_cherches;
	}
	else
	{
		return 0;
	}
}

function infos_groupe($id_groupe, $bdd)
{
	$req = $bdd->prepare('SELECT * FROM groupes WHERE id_groupe = ?'); 
	$req->execute(array($id_groupe));
	$donnees = $req->fetch();
	$req->closeCursor();

	return $donnees;
}

function nb_potins_ds_gpe($id_groupe, $bdd) // Nombre de potins dans un groupe
{
	$req = $bdd->prepare('SELECT COUNT(*) FROM potins WHERE id_groupe = ?'); 
	$req->execute(array($id_groupe));
	$donnees = $req->fetch();
	$req->closeCursor();

	return $donnees; // A remplacer par : $donnees[COUNT(*)]
}

// Doublon : dans infos_user
function nb_potins_sur_user_ds_gpe($id_groupe, $id_user, $bdd) // Nombre de potins dans un groupe, concernant un user
{
	$req = $bdd->prepare('SELECT COUNT(*) FROM cor_potin_users
							INNER JOIN potins
							ON potins.id_potin = cor_potin_users.id_potin
							WHERE potins.id_groupe = ? AND cor_potin_users.id_concerne = ?'); 
	$req->execute(array($id_groupe, $id_user));
	$donnees = $req->fetch();
	$req->closeCursor();

	return $donnees['COUNT(*)'];
}

function nb_potins_visibles_sur_user_ds_gpe($id_groupe, $id_concerne, $id_user, $bdd) // Nombre de potins dans un groupe, concernant un membre et visibles par l'user
{
	$i = 0;

	// Selection des potins de l'user concerne dans le groupe
	$req = $bdd->prepare('SELECT * FROM cor_potin_users
							INNER JOIN potins
							ON potins.id_potin = cor_potin_users.id_potin
							WHERE potins.id_groupe = ? AND cor_potin_users.id_concerne = ?'); 
	$req->execute(array($id_groupe, $id_concerne));

	while($donnees = $req->fetch()) // Pour chacun des potins
    {
    	// On cherche si ce potin correspond à l'user et si il n'a pas été découvert par lui --> Il ne faut pas qu'il apparaisse
    	$req2 = $bdd->prepare('SELECT COUNT(*) FROM cor_potin_users
								WHERE id_concerne = ? AND id_potin = ? AND decouvert = 0');
		$req2->execute(array($id_user, $donnees['id_potin']));
		$donnees2 = $req2->fetch();
		$req2->closeCursor();

		// Prise en compte si on n'a pas trouvé de correspondance
		if($donnees2['COUNT(*)'] == 0)
		{
			$i++; // On prend en compte ce potin
		}
    }

	$req->closeCursor();

	return $i;
}

function nb_utilisateurs_actifs_groupe($id_groupe, $bdd)
{
	include_once('modele/infos_user.php');
	$i = 0;

	$req = $bdd->prepare('SELECT id_user FROM cor_user_groupe WHERE id_groupe = ?'); 
	$req->execute(array($id_groupe));

	while($donnees = $req->fetch()) // Pour chacun des potins
    {
    	$duree = nb_jours_derniere_connexion($donnees['id_user'], $bdd); // De puis combien de temps l'utilisateur courant ne s'est pas connecté

    	if(($duree != 'jamais_connecte') && ($duree <= 30)) // Si il s'est connecté dans les 30 jours
    	{
    		$i++; // On a un utilisateur actif de plus
    	}
	}
	$req->closeCursor();

	return $i;
}

function changer_nom_groupe($id_groupe, $nouv_nom, $bdd)
{
	$req = $bdd->prepare('UPDATE groupes SET nom = ? WHERE id_groupe = ?'); 
  	$req->execute(array($nouv_nom, $id_groupe));
}

function changer_description_groupe($id_groupe, $nouv_desc, $bdd)
{
	$req = $bdd->prepare('UPDATE groupes SET description = ? WHERE id_groupe = ?'); 
  	$req->execute(array($nouv_desc, $id_groupe));
}

function admin_groupe($id_groupe, $bdd) // Retourne les id des admins du groupe
{
	$i = 0;

	$id_users = rech_users_d_un_groupe($id_groupe, $bdd); //Tous les users du groupe

	foreach ($id_users as $key => $user_courant) {
		if(role_gpe($user_courant, $id_groupe, $bdd) == 2) // Si l'user courant est un admin
		{
			$admins[$i] = $user_courant;
			$i++;
		}
	}

	if(isset($admins))
	{
		return $admins;
	}
	else
	{
		return 0;
	}
}
?>