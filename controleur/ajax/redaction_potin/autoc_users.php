<?php

include_once('modele/appartient_au_groupe.php');

$term = $_GET['term'];
$id_groupe = $_GET['id_groupe'];

// On recherche les noms de groupes dans lesquels l'user est inscrit, et qui correspondent à sa recherche
$requete = $bdd->prepare('SELECT users.id_user AS id_user,
									users.prenom AS prenom,
									users.nom AS nom
							FROM users 
							INNER JOIN cor_user_groupe
							ON cor_user_groupe.id_user = users.id_user
							WHERE cor_user_groupe.id_groupe = ? AND (users.nom COLLATE UTF8_GENERAL_CI LIKE ? || users.prenom COLLATE UTF8_GENERAL_CI LIKE ?)'); // j'effectue ma requête SQL grâce au mot-clé LIKE (collate permet de rendre la requête insensible à la casse)
$requete->execute(array($id_groupe, '%'.$term.'%', '%'.$term.'%'));

$array = array(); // on créé le tableau

while($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données
{
    array_push($array, array('id_user' => $donnee['id_user'], 'prenom' => $donnee['prenom'], 'nom' => $donnee['nom'])); // et on ajoute celles-ci à notre tableau
}

echo json_encode($array); // il n'y a plus qu'à convertir en JSON

?>