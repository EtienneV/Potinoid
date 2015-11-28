<?php
include_once('modele/rechercher_potins.php');
include_once('modele/requetes_vote.php');

// Calcul des points supplémentaires
function points_supplementaires($id_user, $bdd)
{
    $req = $bdd->prepare('SELECT SUM(valeur) AS total
                          FROM points
                          WHERE id_user = ?');     
    $req->execute(array($id_user));
    $donnees = $req->fetch();
    $req->closeCursor();

    return 0+$donnees['total'];
}

function points_cadeau($id_user, $bdd)
{
    $req = $bdd->prepare('SELECT SUM(valeur) AS total
                          FROM points
                          WHERE id_user = ? AND nature = "cadeau"');     
    $req->execute(array($id_user));
    $donnees = $req->fetch();
    $req->closeCursor();

    return 0+$donnees['total'];
}

function points_votes($id_user, $bdd)
{
    $req = $bdd->prepare('SELECT SUM(valeur) AS total
                          FROM points
                          WHERE id_user = ? AND nature = "vote"');     
    $req->execute(array($id_user));
    $donnees = $req->fetch();
    $req->closeCursor();

    return 0+$donnees['total'];
}

function points_connexions($id_user, $bdd)
{
    $req = $bdd->prepare('SELECT SUM(valeur) AS total
                          FROM points
                          WHERE id_user = ? AND nature = "connexion"');     
    $req->execute(array($id_user));
    $donnees = $req->fetch();
    $req->closeCursor();

    return 0+$donnees['total'];
}

function points_potins($id_user, $bdd)
{
	$points = 0;

	// Combien les potins publiés ont rapportés
	$potins_user = rechercher_potins_d_un_auteur($id_user, $bdd);

	if($potins_user != 0)
	{
		foreach ($potins_user as $key => $value) {
			switch (resultat_vote($value, $bdd)) {
				case 'sur':
					$points += 10;
					break;
				case 'possible':
					$points += 5;
					break;
				case 'faux':
					$points -= 3;
					break;
				case 'calomnie':
					$points -= 5;
					break;			
				default:
					break;
			}
		}
	}

	return $points;
}

function calculer_points($id_user, $bdd)
{
	$points = 0;

	$points += points_potins($id_user, $bdd);

	$points += points_supplementaires($id_user, $bdd); // On ajoute les points offerts ou autre

	// Combien les potins découverts nous ont coûté
	$points -= nb_potins_decouverts($id_user, $bdd)*10;

  $points -= nb_potins_externes_decouverts($id_user, $bdd)*20;

	return $points;
}

function nb_potins_decouverts($id_user, $bdd)
{
	$req = $bdd->prepare('SELECT COUNT(*)
                          FROM cor_potin_users
                          WHERE id_concerne = ? AND decouvert = 1');     
    $req->execute(array($id_user));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['COUNT(*)'];
}

function nb_potins_externes_decouverts($id_user, $bdd)
{
  $req = $bdd->prepare('SELECT COUNT(*)
                          FROM potins_externes
                          WHERE id_user = ?');     
    $req->execute(array($id_user));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['COUNT(*)'];
}

?>