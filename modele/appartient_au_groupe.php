<?php

function appartient_au_groupe($id_user, $id_groupe, $bdd)
{
	$req = $bdd->prepare('SELECT COUNT(*) AS nbr FROM cor_user_groupe WHERE id_user = ? AND id_groupe = ?'); 
	$req->execute(array($id_user, $id_groupe));
	$donnees = $req->fetch();
	$req->closeCursor();

	if($donnees['nbr'] > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}


function groupes_d_un_user($id_user, $bdd)
{
	$i = 0;

	// récupère les id de tous les groupes
	$req = $bdd->prepare('SELECT id_groupe FROM cor_user_groupe WHERE id_user = ?'); 
	$req->execute(array($id_user));

	// Pour chaque groupe, vérifie si les deux appartiennent au groupe
	while($groupe = $req->fetch()) 
	{
		$groupes_cherches[$i] = $groupe['id_groupe'];
		$i++;
	}

	$req->closeCursor();

	if(isset($groupes_cherches))
	{
		return $groupes_cherches;
	}
	else
	{
		return 'aucun_groupe';
	}
}

// Revoie les groupes en commun de deux users
function groupes_en_commun($id_user_1, $id_user_2, $bdd)
{
	$i = 0;

	// récupère les id de tous les groupes
	$req = $bdd->prepare('SELECT id_groupe FROM groupes'); 
	$req->execute();

	// Pour chaque groupe, vérifie si les deux appartiennent au groupe
	while($groupe = $req->fetch()) 
	{
		if((appartient_au_groupe($id_user_1, $groupe['id_groupe'], $bdd)) && (appartient_au_groupe($id_user_2, $groupe['id_groupe'], $bdd)))
		{
			$groupes_cherches[$i] = $groupe['id_groupe'];
			$i++;
		}
	}

	$req->closeCursor();

	if(isset($groupes_cherches))
	{
		return $groupes_cherches;
	}
	else
	{
		return 'rien_en_commun';
	}
}

?>