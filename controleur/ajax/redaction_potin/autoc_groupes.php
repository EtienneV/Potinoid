<?php

include_once('modele/appartient_au_groupe.php');

$term = $_GET['term'];
$id_concerne = $_GET['id_concerne'];

// On recherche les noms de groupes dans lesquels l'user est inscrit, et qui correspondent à sa recherche
$requete = $bdd->prepare('SELECT * FROM groupes 
							INNER JOIN cor_user_groupe
							ON cor_user_groupe.id_groupe = groupes.id_groupe
							WHERE cor_user_groupe.id_user = ? AND nom COLLATE UTF8_GENERAL_CI LIKE ?'); // j'effectue ma requête SQL grâce au mot-clé LIKE (collate permet de rendre la requête insensible à la casse)
$requete->execute(array($id_user, '%'.$term.'%'));

$array = array(); // on créé le tableau

while($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données
{
	if(($id_concerne == -1) || (($id_concerne != -1) && appartient_au_groupe($id_concerne, $donnee['id_groupe'], $bdd))) // Si il y a un user sélectionné par défaut et qu'il appartient au groupe courant
	{
    	array_push($array, array('id_groupe' => $donnee['id_groupe'], 'nom_groupe' => $donnee['nom'])); // et on ajoute celles-ci à notre tableau
	}
}

echo json_encode($array); // il n'y a plus qu'à convertir en JSON

?>