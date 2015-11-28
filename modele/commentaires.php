<?php
function rechercher_commentaires($id_potin, $bdd)
{
	$i = 0;

	$req_com = $bdd->prepare('SELECT id_com FROM commentaires WHERE id_potin = ? ORDER BY date_com ASC'); 
    $req_com->execute(array($id_potin));
    
    while($donnees_com = $req_com->fetch()) 
    {
    	$commentaires_cherches[$i] = $donnees_com['id_com'];
		$i++;
    }

    $req_com->closeCursor();

    if(isset($commentaires_cherches)) // si on a trouvé des commentaires
    {
    	return $commentaires_cherches;
    }
    else 
    {
    	return 'erreur_nocom';
    }
}


function infos_commentaire($id_com, $bdd)
{
	$req_com = $bdd->prepare('SELECT *
								FROM commentaires 
								INNER JOIN users
								ON commentaires.id_auteur = users.id_user
								WHERE id_com = ?'); 
    $req_com->execute(array($id_com));
    $donnees_com = $req_com->fetch();
    $req_com->closeCursor();

    return $donnees_com;
}

function qui_a_commente_potin($id_potin, $bdd)
{
    $i = 0;

    $req = $bdd->prepare('SELECT id_auteur FROM commentaires WHERE id_potin = ?'); 
    $req->execute(array($id_potin));
    
    while($donnees = $req->fetch()) 
    {
        $auteurs_cherches[$i] = $donnees['id_auteur'];
        $i++;
    }

    $req->closeCursor();

    if(isset($auteurs_cherches)) // si on a trouvé des commentaires
    {
        return $auteurs_cherches;
    }
    else 
    {
        return 'erreur_nocom';
    }
}

function nb_com_de_user($id_user)
{
    global $bdd;

    $req_com = $bdd->prepare('SELECT COUNT(*) FROM commentaires WHERE id_auteur = ?'); 
    $req_com->execute(array($id_user));
    $donnees_com = $req_com->fetch();
    $req_com->closeCursor();

    return $donnees_com['COUNT(*)'];
}
?>