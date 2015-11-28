<?php
include_once('modele/rechercher_potins.php');
include_once('modele/infos_groupe.php');

function nb_votes_positif($id_potin, $bdd)
{
	$req = $bdd->prepare('SELECT COUNT(*)
                          FROM vote_potin
                          WHERE id_potin = ? AND valeur = 1');     
    $req->execute(array($id_potin));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['COUNT(*)'];
}

function nb_votes_negatif($id_potin, $bdd)
{
	$req = $bdd->prepare('SELECT COUNT(*)
                          FROM vote_potin
                          WHERE id_potin = ? AND valeur = -1');     
    $req->execute(array($id_potin));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['COUNT(*)'];
}

function nb_votes_ne_sait_pas($id_potin, $bdd)
{
  $req = $bdd->prepare('SELECT COUNT(*)
                          FROM vote_potin
                          WHERE id_potin = ? AND valeur = 0');     
    $req->execute(array($id_potin));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['COUNT(*)'];
}

function nb_votants($id_potin, $bdd)
{
  $req = $bdd->prepare('SELECT COUNT(*)
                          FROM vote_potin
                          WHERE id_potin = ?');     
    $req->execute(array($id_potin));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['COUNT(*)'];  
}

// Verification pas dejà voté
function deja_vote($id_potin, $id_user, $bdd)
{
    $req = $bdd->prepare('SELECT COUNT(*)
                          FROM vote_potin
                          WHERE id_potin = ? AND id_auteur = ?');     
    $req->execute(array($id_potin, $id_user));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['COUNT(*)'];
}

function vote_user($id_potin, $id_user, $bdd)
{
  $req = $bdd->prepare('SELECT valeur
                          FROM vote_potin
                          WHERE id_potin = ? && id_auteur = ?');     
    $req->execute(array($id_potin, $id_user));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['valeur'];
}

// Calcul score
function score($id_potin, $bdd)
{
    $req = $bdd->prepare('SELECT SUM(valeur)
                          FROM vote_potin
                          WHERE id_potin = ?');     
    $req->execute(array($id_potin));
    $donnees = $req->fetch();
    $req->closeCursor();

    return $donnees['SUM(valeur)'];
}

function assez_de_votes($id_potin, $bdd) // Est-ce qu'il y assez de votes pour rendre les résultat valable ?
{
  $data_potin = infos_potin($id_potin, $bdd);

  if(nb_votants($id_potin, $bdd) > nb_utilisateurs_actifs_groupe($data_potin['id_Groupe'], $bdd)/2) // Si nb_votants > actifs_groupe/2
  {
    return true;
  }
  else
  {
    return false;
  }
}

function resultat_vote($id_potin, $bdd)
{
  $pos = nb_votes_positif($id_potin, $bdd);
  $neg = nb_votes_negatif($id_potin, $bdd);

  if(assez_de_votes($id_potin, $bdd)) // Si il y a assez de votes pour avoir un résultat
  {
    if($pos > (3/2)*$neg)
    {
      return 'sur';
    }
    else if(($pos > $neg) && ($pos <= (3/2)*$neg) )
    {
      return 'possible';
    }
    else if(($pos > ($neg/2)) && ($pos <= $neg))
    {
      return 'surement_faux';
    }
    else if(($pos > 0) && ($pos <= $neg/2))
    {
      return 'faux';
    }
    else if(($pos == 0) && ($neg > 0))
    {
      return 'calomnie';
    }
    else
    {
      return 'non_disponible';
    }
  }
  else
  {
    return 'non_disponible';
  }
}
?>