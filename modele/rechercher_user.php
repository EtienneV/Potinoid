<?php
function rech_users_d_un_groupe($id_groupe, $bdd) // Tri par ordre alphabétique
{
	$i = 0;

    $req = $bdd->prepare('SELECT users.id_user
                          FROM cor_user_groupe
                          INNER JOIN users
                          ON users.id_user = cor_user_groupe.id_user
                          WHERE id_groupe = ?
                          ORDER BY users.prenom, users.nom ASC');     
    $req->execute(array($id_groupe));
    while($donnees = $req->fetch()) 
    {
    	$users_cherches[$i] = $donnees['id_user'];
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