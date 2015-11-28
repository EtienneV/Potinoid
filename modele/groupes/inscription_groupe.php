<?php

function candidature_dans_groupe($id_user, $id_groupe, $bdd)
{
	$req = $bdd->prepare('INSERT INTO inscription_groupe(id_user, id_groupe, type, etat, date) VALUES(?, ?, "demande", "new", NOW())'); 
  	$req->execute(array($id_user, $id_groupe));
}

function candidature_dans_groupe_existe($id_user, $id_groupe, $bdd)
{
	$req = $bdd->prepare('SELECT COUNT(*) FROM inscription_groupe 
							WHERE id_user = ? AND id_groupe = ?');
	$req->execute(array($id_user, $id_groupe));
	$donnees = $req->fetch();
	$req->closeCursor();

	if($donnees['COUNT(*)'] > 0) return true; // Si la candidature existe déjà
	else return false;
}

function id_candidatures_d_un_groupe($id_groupe, $bdd)
{
	$i = 0;

    $req = $bdd->prepare('SELECT id
                          FROM inscription_groupe 
                          WHERE id_groupe = ?
                          ORDER BY date DESC');     
    $req->execute(array($id_groupe));
    while($donnees = $req->fetch()) 
    {
    	$candidatures_cherches[$i] = $donnees['id'];
		$i++;
    }
    $req->closeCursor();

    if(isset($candidatures_cherches))
	{
		return $candidatures_cherches;
	}
	else
	{
		return 0;
	}
}

function infos_candidature($id_candidature, $bdd)
{
	$req = $bdd->prepare('SELECT inscription_groupe.id AS id_insc, 
								users.id_user AS id_user,
								users.nom AS nom,
								users.prenom AS prenom,
								groupes.nom AS nom_groupe,
								inscription_groupe.type AS type_insc,
								inscription_groupe.etat AS etat_insc,
								inscription_groupe.date AS date_insc
							FROM inscription_groupe 
							INNER JOIN users
							ON users.id_user = inscription_groupe.id_user
							INNER JOIN groupes
							ON groupes.id_groupe = inscription_groupe.id_groupe
							WHERE id = ?');
	$req->execute(array($id_candidature));
	$donnees = $req->fetch();
	$req->closeCursor();

	return $donnees;
}

function supprimer_candidature($id_candidature, $bdd)
{
	$req = $bdd->prepare('DELETE FROM inscription_groupe WHERE id = ?'); 
  	$req->execute(array($id_candidature));
}

function inscrire_user($id_user, $id_groupe, $bdd)
{
	$req = $bdd->prepare('INSERT INTO cor_user_groupe(id_user, id_groupe, role) VALUES(?, ?, 1)'); 
  	$req->execute(array($id_user, $id_groupe));
}

?>