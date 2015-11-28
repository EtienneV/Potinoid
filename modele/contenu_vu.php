<?php

function contenu_deja_vu($id_user, $id_contenu, $type, $bdd)
{
	$req = $bdd->prepare('SELECT COUNT(*)
							FROM contenu_vu
							WHERE id_user = ? AND id_contenu = ? AND type = ?'); 
    $req->execute(array($id_user, $id_contenu, $type));
    $donnees = $req->fetch();
    $req->closeCursor();

    if($donnees['COUNT(*)'] > 0) return true;
    else return false;
}

function new_contenu_vu($id_user, $id_contenu, $type, $bdd)
{
	// type de contenu - potin : potin ; commentaire : comment
	$req = $bdd->prepare('INSERT INTO contenu_vu(id_user, id_contenu, type) VALUES(?, ?, ?)'); 
    $req->execute(array($id_user, $id_contenu, $type));
}

function liste_potins_vus($id_user, $bdd)
{
	$i = 0;

	$req = $bdd->prepare('SELECT id_contenu
							FROM contenu_vu
							WHERE id_user = ? AND type = "potin"'); 
    $req->execute(array($id_user));
    
    while($donnees = $req->fetch()) 
    {
    	$potins_cherches[$i] = $donnees['id_contenu'];
		$i++;
    }

    $req->closeCursor();

    if(isset($potins_cherches)) // si on a trouvé des commentaires
    {
    	return $potins_cherches;
    }
    else 
    {
    	return 'erreur_nopotin';
    }
}

function liste_com_vus($id_user, $bdd)
{
	$i = 0;

	$req = $bdd->prepare('SELECT id_contenu
							FROM contenu_vu
							WHERE id_user = ? AND type = "comment"'); 
    $req->execute(array($id_user));
    
    while($donnees = $req->fetch()) 
    {
    	$potins_cherches[$i] = $donnees['id_contenu'];
		$i++;
    }

    $req->closeCursor();

    if(isset($potins_cherches)) // si on a trouvé des commentaires
    {
    	return $potins_cherches;
    }
    else 
    {
    	return 'erreur_nocom';
    }
}

function liste_potins_non_vus($id_user, $bdd)
{
	$potins_vus = liste_potins_vus($id_user, $bdd);

	include_once('modele/rechercher_potins.php');
	$tous_potins = rechercher_potins_des_groupes_de_user($id_user, $bdd);

	if($potins_vus != 'erreur_nopotin') // Si on a vu des potins
	{
		if($tous_potins != 0) // Si on a des potins
		{
			return array_diff($tous_potins, $potins_vus); // On retourne les potins qu'on a pas vus
		}
		else
		{
			return 'error_aucun_potin'; // Il n'y a pas de potins
		}
	}
	else // Si on a vu aucun potin
	{
		return $tous_potins; // On renvoie tous les potins
	}
}

?>