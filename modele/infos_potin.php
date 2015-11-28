<?php

function auteur_du_potin($id_potin, $bdd)
{
	$req = $bdd->prepare('SELECT id_auteur
                          FROM potins
                          WHERE id_potin = ?');     
    $req->execute(array($id_potin));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['id_auteur'];
}

function groupe_du_potin($id_potin, $bdd)
{
  $req = $bdd->prepare('SELECT id_groupe
                          FROM potins
                          WHERE id_potin = ?');     
    $req->execute(array($id_potin));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['id_groupe'];
}

function concernes_potin($id_potin, $bdd)
{
	$i = 0;

	$req = $bdd->prepare('SELECT id_concerne
                          FROM cor_potin_users
                          WHERE id_potin = ?');     
    $req->execute(array($id_potin));
    while($donnees = $req->fetch()) 
    {	
    	$users_cherches[$i] = $donnees['id_concerne'];
		$i++;
    }
    $req->closeCursor();

    if(isset($users_cherches))
	{
		return $users_cherches;
	}
	else
	{
		return 0;
	}
}

?>