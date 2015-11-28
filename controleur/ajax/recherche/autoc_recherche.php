<?php

include_once('modele/appartient_au_groupe.php');

$term = $_GET['term'];

// On recherche les noms de groupes dans lesquels l'user est inscrit, et qui correspondent à sa recherche
$requete = $bdd->prepare('SELECT users.id_user AS id_user,
									users.prenom AS prenom,
									users.nom AS nom
							FROM users 
							WHERE (users.nom COLLATE UTF8_GENERAL_CI LIKE ? || users.prenom COLLATE UTF8_GENERAL_CI LIKE ?)'); // j'effectue ma requête SQL grâce au mot-clé LIKE (collate permet de rendre la requête insensible à la casse)
$requete->execute(array('%'.$term.'%', '%'.$term.'%'));

$array = array(); // on créé le tableau

while($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données
{
    array_push($array, array('id_user' => $donnee['id_user'], 'prenom' => $donnee['prenom'], 'nom' => $donnee['nom'])); // et on ajoute celles-ci à notre tableau
}

echo json_encode($array); // il n'y a plus qu'à convertir en JSON

?>