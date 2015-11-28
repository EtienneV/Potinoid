<?php

include_once('modele/infos_potin.php');

function rechercher_potins_des_groupes_de_user_offset($id_user, $limit, $offset, $bdd)
{
	$potins = rechercher_potins_des_groupes_de_user($id_user, $bdd);

	$i = 0;

	if($potins != 0) // Si il y des potins
	{
		if($offset <= count($potins)) // Si il reste des potins
		{
			foreach ($potins as $key => $potin_courant) {
				if($i == ($offset + $limit))
				{
					break;
				}
				else if($i >= $offset)
				{
					$potins_cherches[$i] = $potin_courant;
				}
				$i++;
			}
		}
		else
		{
			return 'plus_de_potins';
		}
	}
	else
	{
		return 'pas_de_potins';
	}

	if(isset($potins_cherches))
	{
		return $potins_cherches;
	}
	else
	{
		return 'erreur';
	}
}

function rechercher_potins_des_groupes_de_user($id_user, $bdd)
{
	

	$i = 0;

	// Tous les id_potins de tous les groupes auxquel appartient l'user
    $req = $bdd->prepare('SELECT id_potin
                          FROM potins
                          WHERE id_groupe IN(
                              SELECT id_groupe
                              FROM cor_user_groupe
                              WHERE id_user = ?)
                          ORDER BY date_potin DESC');        
    $req->execute(array($id_user));

    while($donnees = $req->fetch()) // Pour chacun des potins
    {

	    // On cherche si l'utilisateur est concerné par le potin courant(et s'il l'a pas découvert)
	    $req2 = $bdd->prepare('SELECT COUNT(*) FROM cor_potin_users WHERE id_concerne = ? AND id_potin = ? AND decouvert = 0'); 
	    $req2->execute(array($id_user, $donnees['id_potin']));
	    $donnees2 = $req2->fetch();
	    $req2->closeCursor();

	    // Si le potin est référencé
	    $req3 = $bdd->prepare('SELECT COUNT(*) FROM cor_potin_users WHERE id_potin = ?'); 
	    $req3->execute(array($donnees['id_potin']));
	    $donnees3 = $req3->fetch();
	    $req3->closeCursor();

	    if((!$donnees2['COUNT(*)'] || ($donnees2['COUNT(*)'] && (auteur_du_potin($donnees['id_potin'], $bdd) == $id_user))) && $donnees3['COUNT(*)']) // Si id_potin && id_user ne sont jamais ensemble dans la table de correspondance (sauf si on est l'auteur) ET que le potin est référencé
		{
			$potins_cherches[$i] = $donnees['id_potin'];
			$i++;
	    }
  	}
  	$req->closeCursor();

  	if(isset($potins_cherches))
	{
		return $potins_cherches;
	}
	else
	{
		return 0;
	}
}

function rechercher_potins_d_un_groupe_de_user($id_user, $id_groupe, $bdd)
{
	$i = 0;
	
	// Recherche les potins du groupe
    $req = $bdd->prepare('SELECT id_potin
                          FROM potins
                          WHERE id_groupe = ?
                          ORDER BY date_potin DESC');     
    $req->execute(array($id_groupe));
    
    while($donnees = $req->fetch()) 
    {
    	// On cherche si l'utilisateur est concerné par le potin courant(et s'il l'a pas découvert)
	    $req2 = $bdd->prepare('SELECT COUNT(*) FROM cor_potin_users WHERE id_concerne = ? AND id_potin = ? AND decouvert = 0'); 
	    $req2->execute(array($id_user, $donnees['id_potin']));
	    $donnees2 = $req2->fetch();
	    $req2->closeCursor();
	
		// Si le potin est référencé
	    $req3 = $bdd->prepare('SELECT COUNT(*) FROM cor_potin_users WHERE id_potin = ?'); 
	    $req3->execute(array($donnees['id_potin']));
	    $donnees3 = $req3->fetch();
	    $req3->closeCursor();
	
	    if((!$donnees2['COUNT(*)'] || ($donnees2['COUNT(*)'] && (auteur_du_potin($donnees['id_potin'], $bdd) == $id_user))) && $donnees3['COUNT(*)']) // Si id_potin && id_user ne sont jamais ensemble dans la table de correspondance ET que le potin apparaît au moins une fois dans cette table
	    {	
	        $potins_cherches[$i] = $donnees['id_potin'];
			$i++;
	    }
    }
    $req->closeCursor();

    if(isset($potins_cherches))
	{
		return $potins_cherches;
	}
	else
	{
		return 0;
	}
}

function rechercher_potins_d_un_user_dans_un_groupe($id_user, $id_groupe, $id_concerne, $bdd)
{
	$i = 0;

	// Infos sur tous les potins de l'user concerne, dans le groupe
	$req = $bdd->prepare('SELECT potins.id_potin AS id_Potin,
               						potins.date_potin AS Date
                                      	FROM potins 
                                      	INNER JOIN cor_potin_users
                                      		ON potins.id_potin = cor_potin_users.id_potin
                                      	INNER JOIN users
                                      		ON users.id_user = potins.id_auteur
                                      	WHERE cor_potin_users.id_concerne = ? AND potins.id_groupe = ?
                                      ORDER BY Date DESC');     
      
    $req->execute(array($id_concerne, $id_groupe));

    while($donnees = $req->fetch()) // Pour chacun des potins
    {
    			// On cherche si il concerne l'user et n'est pas découvert par lui
                $req2 = $bdd->prepare('SELECT COUNT(*) FROM cor_potin_users WHERE id_concerne = ? AND id_potin = ? AND decouvert = 0'); 
                $req2->execute(array($id_user, $donnees['id_Potin']));
                $donnees2 = $req2->fetch();
                $req2->closeCursor();

                if(!$donnees2['COUNT(*)']) // Si on peut voir le potin
                {
                	$potins_cherches[$i] = $donnees['id_Potin'];
					$i++;
				}
	}
	$req->closeCursor();

	if(isset($potins_cherches))
	{
		return $potins_cherches;
	}
	else
	{
		return 0;
	}
}

function rechercher_potins_d_un_auteur($id_auteur, $bdd)
{
	$i = 0;

    $req = $bdd->prepare('SELECT id_potin
                          FROM potins
                          WHERE id_auteur = ?
                          ORDER BY date_potin DESC');     
    $req->execute(array($id_auteur));
    while($donnees = $req->fetch()) 
    {
    	$req2 = $bdd->prepare('SELECT COUNT(*) FROM cor_potin_users WHERE id_potin = ?'); 
	    $req2->execute(array($donnees['id_potin']));
	    $donnees2 = $req2->fetch();
	    $req2->closeCursor();

    	if($donnees2['COUNT(*)']) // On n'affiche que les potins référencés
    	{
    		$potins_cherches[$i] = $donnees['id_potin'];
			$i++;
		}
    }
    $req->closeCursor();

    if(isset($potins_cherches))
	{
		return $potins_cherches;
	}
	else
	{
		return 0;
	}
}

function rechercher_potins_sur_user($id_concerne, $bdd)
{
	$i = 0;

    $req = $bdd->prepare('SELECT potins.id_potin
                          FROM cor_potin_users
                          INNER JOIN potins
                          ON potins.id_potin = cor_potin_users.id_potin
                          WHERE id_concerne = ?
                          ORDER BY date_potin DESC');     
    $req->execute(array($id_concerne));
    while($donnees = $req->fetch()) 
    {
    	$potins_cherches[$i] = $donnees['id_potin'];
		$i++;
    }
    $req->closeCursor();

    if(isset($potins_cherches))
	{
		return $potins_cherches;
	}
	else
	{
		return 0;
	}
}

function potins_decouverts_sur_user_dans_gpe($id_concerne, $id_groupe, $id_user)
{
	global $bdd;

	$i = 0;

    $req = $bdd->prepare('SELECT potins_externes.id_potin AS id_potin
                          FROM potins_externes
                          INNER JOIN potins
                          ON potins.id_potin = potins_externes.id_potin
                          INNER JOIN cor_potin_users
                          ON cor_potin_users.id_potin = potins_externes.id_potin
                          WHERE  potins_externes.id_user = ? AND cor_potin_users.id_concerne = ? AND potins.id_groupe = ?');     
    $req->execute(array($id_user, $id_concerne, $id_groupe));
    while($donnees = $req->fetch()) 
    {
    	$potins_cherches[$i] = $donnees['id_potin'];
		$i++;
    }
    $req->closeCursor();

    if(isset($potins_cherches))
	{
		return $potins_cherches;
	}
	else
	{
		return 0;
	}
}

function tous_potins_sur_user_ds_gpe($id_groupe, $id_user)
{
	global $bdd;

	$i = 0;

    $req = $bdd->prepare('SELECT potins.id_potin AS id_potin 
							FROM cor_potin_users
							INNER JOIN potins
							ON potins.id_potin = cor_potin_users.id_potin
							WHERE potins.id_groupe = ? AND cor_potin_users.id_concerne = ?'); 
	$req->execute(array($id_groupe, $id_user));
    while($donnees = $req->fetch()) 
    {
    	$potins_cherches[$i] = $donnees['id_potin'];
		$i++;
    }
    $req->closeCursor();

    if(isset($potins_cherches))
	{
		return $potins_cherches;
	}
	else
	{
		return 'pas_de_potin';
	}
}

function infos_potin($id_potin, $bdd)
{
	// Cherche les infos sur le potin
    $req = $bdd->prepare('SELECT info_potin.Potin AS Potin,
    								groupes.id_groupe AS id_Groupe,
                    				groupes.nom AS nom_groupe,
							       info_potin.id_Potin AS id_Potin,
							       info_potin.prenom_auteur AS prenom_auteur,
							       info_potin.nom_auteur AS nom_auteur,
							       info_potin.id_auteur AS id_auteur,
							        info_potin.Date AS Date,
							        info_potin.Image AS Image,
							        cor_potin_users.decouvert AS Decouvert,
							       nom_jour_potin,
							       jour_potin,
							       mois_potin,
							       annee_potin, 
							       GROUP_CONCAT(users.prenom SEPARATOR ",") AS concernes,
							       GROUP_CONCAT(users.id_user SEPARATOR ",") AS id_Concernes
							FROM(
							SELECT potins.potin AS Potin,
							       potins.id_potin AS id_Potin,
							       potins.id_groupe AS id_Groupe,
							       users.prenom AS prenom_auteur,
							       users.nom AS nom_auteur,
							       potins.id_auteur AS id_auteur,
							         potins.date_potin AS Date,
							       DAYNAME(potins.date_potin) AS nom_jour_potin,
							       DAY(potins.date_potin) AS jour_potin,
							       MONTHNAME(potins.date_potin) AS mois_potin,
							       YEAR(potins.date_potin) AS annee_potin,
							       potins.image AS Image
							       FROM potins 
							       INNER JOIN users
							         ON users.id_user = potins.id_auteur
							       WHERE id_potin = ?) AS info_potin
							INNER JOIN cor_potin_users
							  ON cor_potin_users.id_potin = info_potin.id_Potin
							INNER JOIN users
							  ON users.id_user = cor_potin_users.id_concerne
							INNER JOIN groupes
							  ON groupes.id_groupe = info_potin.id_Groupe'); 
    $req->execute(array($id_potin));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees;
}

?>