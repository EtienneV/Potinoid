<?php

function nouvelle_notif($id_user, $type, $ref1, $ref2, $bdd)
{
    $req = $bdd->prepare('INSERT INTO notifications(id_user, type, date, vue, ref, ref_bis) VALUES(?, ?, NOW(), 0, ?, ?)'); 
    $req->execute(array($id_user, $type, $ref1, $ref2));
}

function rechercher_notifs($id_user, $bdd) // Les notifications non visitées
{
	$i = 0;

	$req = $bdd->prepare('SELECT id_notif
							FROM notifications
							WHERE id_user = ? AND vue = 0
							ORDER BY date DESC'); 
    $req->execute(array($id_user));
    
    while($donnees = $req->fetch()) 
    {
    	$notifs_cherches[$i] = $donnees['id_notif'];
		$i++;
    }

    $req->closeCursor();

    if(isset($notifs_cherches)) // si on a trouvé des commentaires
    {
    	return $notifs_cherches;
    }
    else 
    {
    	return 'erreur_nonotif';
    }
}

function infos_notif($id_notif, $bdd)
{
	$req = $bdd->prepare('SELECT *
							FROM notifications
							WHERE id_notif = ?'); 
    $req->execute(array($id_notif));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees;
}

function notif_vue($id_notif, $bdd)
{
	$req = $bdd->prepare('UPDATE notifications
							SET vue = 1
							WHERE id_notif = ?');     
    $req->execute(array($id_notif));
}

function notif_existe_deja($id_user, $type, $ref1, $ref2, $bdd)
{
    // On regarde si la notif non lue existe déjà
    $req = $bdd->prepare('SELECT COUNT(*) AS nb
                            FROM notifications
                            WHERE id_user = ? AND type = ? AND vue = 0 AND ref = ? AND ref_bis = ?'); 
    $req->execute(array($id_user, $type, $ref1, $ref2));
    $donnees = $req->fetch();
    $req->closeCursor();

    if($donnees['nb'] != 0) // Si elle existe déjà
    {
        return true;
    }
    else return false;
}

?>