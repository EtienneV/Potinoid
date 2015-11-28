<?php

include_once('modele/infos_potin.php');
include_once('modele/requetes_vote.php');


if(isset($_POST['vote'])) // Si on a des données de vote
{
	// On récupère la valeur du vote
	switch ($_POST['vote']) {
		case 'vrai':
			$vote = 1;
			break;

		case 'nesaitpas':
			$vote = 0;
			break;

		case 'faux':
			$vote = -1;
			break;
		
		default:
			# code...
			break;
	}

	include_once('modele/infos_potin.php');
	if($id_user != auteur_du_potin($_POST['id_Potin'], $bdd)) // Si on est pas l'auteur du potin
	{

		if(!deja_vote($_POST['id_Potin'], $_POST['auteur'], $bdd)) // Si on a pas déjà voté
		{
			// On ajoute le vote
			$req = $bdd->prepare('INSERT INTO vote_potin(id_auteur, id_potin, valeur) VALUES(?, ?, ?)'); 
    		$req->execute(array($_POST['auteur'], $_POST['id_Potin'], $vote));	

    		// On ajoute un point
    		$req = $bdd->prepare('INSERT INTO points(id_user, valeur, date, nature) VALUES(?, ?, NOW(), "vote")'); 
    		$req->execute(array($id_user, 1));	
		}
		else
		{
			$req = $bdd->prepare('UPDATE vote_potin
									SET valeur = ?
									WHERE id_auteur = ? AND id_potin = ?'); 
    		$req->execute(array($vote, $_POST['auteur'], $_POST['id_Potin']));	
		}

	}
	else
	{
		echo 'Vous ne pouvez pas voter sur ce potin car vous en êtes l\'auteur';
	}
}


$resultat_votes = resultat_vote($_POST['id_Potin'], $bdd);
$nb_votes_pos = nb_votes_positif($_POST['id_Potin'], $bdd);
$nb_votes_neut = nb_votes_ne_sait_pas($_POST['id_Potin'], $bdd);
$nb_votes_neg = nb_votes_negatif($_POST['id_Potin'], $bdd);

$nb_de_votants = nb_votants($_POST['id_Potin'], $bdd);

if($nb_de_votants != 0)
{
	$barre_positif = $nb_votes_pos/$nb_de_votants*100;
	$barre_negatif = $nb_votes_neg/$nb_de_votants*100;
	$barre_neutre = $nb_votes_neut/$nb_de_votants*100;
}
else
{
	$barre_positif = 0;
	$barre_negatif = 0;
	$barre_neutre = 0;
}

echo '<div class="potin-vote-wrapper">';

// On détermine la véracité du potin
switch ($resultat_votes) {
	case 'sur':
		$resv_css = 'sur';
		$resv_text = 'C\'est sûr !';
		break;
	case 'possible':
		$resv_css = 'possible';
		$resv_text = 'C\'est possible.';
		break;
	case 'surement_faux':
		$resv_css = 'surement_faux';
		$resv_text = 'C\'est sûrement faux ...';
		break;
	case 'faux':
		$resv_css = 'faux';
		$resv_text = 'C\'est faux !';
		break;
	case 'calomnie':
		$resv_css = 'calomnie';
		$resv_text = 'Ce n\'est que pure calomnie !';
		break;
	
	default:
		$resv_css = 'none';
		$resv_text = '';
		break;
}

// Affichage de la véracité du potin
echo '<div class="potin-resultat-vote resv-'.$resv_css.'">
	'.$resv_text.'
</div>';

if(deja_vote($_POST['id_Potin'], $_POST['auteur'], $bdd)) // si on a déjà voté
{
	echo '<div class="vote-progressbar-wraper">';
	if($barre_positif != 0) echo '<span class="vote-progressbar" style="border: 1px solid #5cb85c; width: '.$barre_positif.'%;"></span>';
	if($barre_neutre != 0) echo '<span class="vote-progressbar" style="border: 1px solid #5bc0de; width: '.$barre_neutre.'%;"></span>';
	if($barre_negatif != 0) echo '<span class="vote-progressbar" style="border: 1px solid #d9534f; width: '.$barre_negatif.'%;"></span>';
	echo '</div>';

	$vote_user = vote_user($_POST['id_Potin'], $_POST['auteur'], $bdd);

	echo '<div class="boutons-vote-wrapper">';

	echo '<span class="potin-bouton Bvrai';
	if ($vote_user == 1) echo '-active';
	echo '" typeBouton="vrai" idUser="'.$_POST['auteur'].'" idPotin="'.$_POST['id_Potin'].'">C\'est vrai</span>';

	echo '<span class="potin-bouton Bnesaitpas';
	if ($vote_user == 0) echo '-active';
	echo '" typeBouton="nesaitpas" idUser="'.$_POST['auteur'].'" idPotin="'.$_POST['id_Potin'].'">Je ne sais pas</span>';

	echo '<span class="potin-bouton Bfaux';
	if ($vote_user == -1) echo '-active';
	echo '" typeBouton="faux" idUser="'.$_POST['auteur'].'" idPotin="'.$_POST['id_Potin'].'">C\'est faux</span>';

	echo '</div>';
}
else if($_POST['auteur'] == auteur_du_potin($_POST['id_Potin'], $bdd)) // Si on est l'auteur du potin
{
    echo '<div class="vote-progressbar-wraper">';
	if($barre_positif != 0) echo '<span class="vote-progressbar" style="border: 1px solid #5cb85c; width: '.$barre_positif.'%;"></span>';
	if($barre_neutre != 0) echo '<span class="vote-progressbar" style="border: 1px solid #5bc0de; width: '.$barre_neutre.'%;"></span>';
	if($barre_negatif != 0) echo '<span class="vote-progressbar" style="border: 1px solid #d9534f; width: '.$barre_negatif.'%;"></span>';
	echo '</div>';
}
else // On peut voter
{ 
	echo '<div class="boutons-vote-wrapper">';
	echo '<span class="potin-bouton Bvrai" typeBouton="vrai" idUser="'.$_POST['auteur'].'" idPotin="'.$_POST['id_Potin'].'">C\'est vrai</span>';
	echo '<span class="potin-bouton Bnesaitpas" typeBouton="nesaitpas" idUser="'.$_POST['auteur'].'" idPotin="'.$_POST['id_Potin'].'">Je ne sais pas</span>';
	echo '<span class="potin-bouton Bfaux" typeBouton="faux" idUser="'.$_POST['auteur'].'" idPotin="'.$_POST['id_Potin'].'">C\'est faux</span>';
	echo '</div>';
}

echo '</div>';
?>