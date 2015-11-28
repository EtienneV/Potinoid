<?php

include_once('controleur/includes/fonctions/parse_smiley.php');
include_once('modele/infos_potin.php');
include_once('modele/infos_user.php');
include_once('modele/infos_groupe.php');
include_once('modele/requetes_vote.php');
include_once('modele/commentaires.php');
include_once('modele/contenu_vu.php');

/**
Refaire, en ne prenant que l'id du potin comme paramètre
**/

function vue_potin_v4_brouille($potin_courant, $id_user, $bdd)
{
	$user = infos_user($id_user, $bdd);

	$groupe_courant = infos_groupe($potin_courant['id_Groupe'], $bdd);

	// Si on n'avait pas vu ce potin
	if(!contenu_deja_vu($id_user, $potin_courant['id_Potin'], 'potin', $bdd))
	{
		// On l'enregistre comme vu 
		new_contenu_vu($id_user, $potin_courant['id_Potin'], 'potin', $bdd);
	}

	$resultat = '';

	$resultat .= '<div class="row-potin-v4 item corps-brouille-v4" id="potin-'.$potin_courant['id_Potin'].'" typePotin="v4">';

	// Header
	$resultat .= '<div class="header-potin-v4">';

	if($groupe_courant['image'] != '') $bandeau_groupe = $groupe_courant['image'];
	else $bandeau_groupe = 'default';

	$resultat .= '<div class="hp4-bandeau">
			        <img src="images/groupe/'.$bandeau_groupe.'-bd.jpg">
			      </div>';

	// Affichage des concernes
	$resultat .= '<div class="hp4-wrapper-profile">';

	/*$nom_concernes = explode(',', $potin_courant['concernes']);
	$id_concernes = explode(',', $potin_courant['id_Concernes']);
	$nb_concernes = count($nom_concernes);*/

	/*foreach ($id_concernes as $key => $concerne_courant) {
		$concerne_courant = infos_user($concerne_courant, $bdd);*/

		if($user['avatar'] != '')
		{
		  $photo_profile = $user['avatar'];
		  $initiales = '';
		}
		else {
		  $photo_profile = 'default';
		  $initiales = substr($user['prenom'], 0, 1).' '.substr($user['nom'], 0, 1);
		}

		$resultat .=  '<a href="'.INDEX.'?page=page_membre&id_concerne='.$user['id_user'].'&onglet=potins">
						<div class="hp4-wrp"><div class="hp4-rounded-profile" style="background:url(images/profile/'.$photo_profile.'-50.jpg) no-repeat 0px 0px;"  data-toggle="tooltip" data-placement="top" title="'.$user['prenom'].' '.$user['nom'].'">'.
						$initiales.'</div></div></a>';
	//}

	$resultat .= '</div>';

	$padding_nom = 10+55+10; // Calcul du décalage du nom du groupe 

	$resultat .= '<div class="hp4-nom-gp">'.'<a href="'.INDEX.'?page=groupe&id_groupe='.$potin_courant['id_Groupe'].'" style="padding-left:'.$padding_nom.'px">'.htmlspecialchars($potin_courant['nom_groupe']).'</a>'.'</div>';



	$resultat .= '</div>';

	// Corps du potin
	$resultat .= '<div class="corps-potin-v4">';

	$resultat .= '<div class="date-potin-v4">'.$potin_courant['nom_jour_potin'].' '.$potin_courant['jour_potin'].' '.$potin_courant['mois_potin'].' '.$potin_courant['annee_potin'];

	$resultat .= '</div>';

	// Le texte du potin
	$resultat .= '<div class="texte-potin-v4">'.'Ce potin peut être débloqué pour 10 points.'.'</div>';

	$resultat .= '</div>'; // Fin corps


	// Image
	$resultat .= '<div class="image-potin-v4">';

	if($potin_courant['Image'] != '')
	{
		$resultat .=  '<img class="imagePotin" src="images/brouillage.gif" alt="Photo du potin">';
	}

	$resultat .= '</div>'; // Fin image




	$resultat .= '<!-- Bouton de révélation du potin -->
    <form action="#" method="post" name="decouvrir_potin" class="form-horizontal ">
      <input type="hidden" name="decouvrir_potin" value="ok" />
      <input type="hidden" name="numero_potin" value="'.$potin_courant['id_Potin'].'" />
      <button class="pull-right btn btn-link" type="submit"';

  
  include_once('controleur/includes/fonctions/calculs_points.php');

  if(calculer_points($id_user, $bdd) < 10)// si on a pas assez de points
  {
    
    $resultat .= 'disabled="disabled"';
    
  }
  
  $resultat .= '><span class="glyphicon glyphicon-eye-open"></span> Découvrir !</button>
    </form>';
	


	// On détermine la véracité du potin
	switch (resultat_vote($potin_courant['id_Potin'], $bdd)) {
		case 'sur':
			$resv_css = 'sur';
			$resv_text = 'C\'est sûr !';
			//$resultat .= '<br>'; // On ajoute un espacement
			break;

		case 'possible':
			$resv_css = 'possible';
			$resv_text = 'C\'est possible.';
			//$resultat .= '<br>';
			break;

		case 'surement_faux':
			$resv_css = 'surement_faux';
			$resv_text = 'C\'est sûrement faux ...';
			//$resultat .= '<br>';
			break;

		case 'faux':
			$resv_css = 'faux';
			$resv_text = 'C\'est faux !';
			//$resultat .= '<br>';
			break;

		case 'calomnie':
			$resv_css = 'calomnie';
			$resv_text = 'Ce n\'est que pure calomnie !';
			//$resultat .= '<br>';
			break;
		
		default:
			$resv_css = 'none';
			$resv_text = '';
			break;
	}

	$resultat .= '<div class="potin-vote-wrapper-v4">';

	if($resv_text != '')
	{
		// Affichage de la véracité du potin
		$resultat .= '<div class="potin-resultat-vote resv-'.$resv_css.'" data-toggle="tooltip" data-placement="top" title="Cet avis est donné par un algorithme secret">
			'.$resv_text.'
		</div>';
	}

    $resultat_votes = resultat_vote($potin_courant['id_Potin'], $bdd);

	$nb_votes_pos = nb_votes_positif($potin_courant['id_Potin'], $bdd);
	$nb_votes_neut = nb_votes_ne_sait_pas($potin_courant['id_Potin'], $bdd);
	$nb_votes_neg = nb_votes_negatif($potin_courant['id_Potin'], $bdd);
	
	$nb_de_votants = nb_votants($potin_courant['id_Potin'], $bdd);
	
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

   	if($nb_de_votants != 0)
   	{
     	$resultat .=  '<div class="vote-progressbar-wraper">';
		if($barre_positif != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #5cb85c; width: '.$barre_positif.'%;"></span>';
		if($barre_neutre != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #5bc0de; width: '.$barre_neutre.'%;"></span>';
		if($barre_negatif != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #d9534f; width: '.$barre_negatif.'%;"></span>';
		$resultat .=  '</div>';
	}

	

	


	

$resultat .=  '</div>';
$resultat .= '</div>';



return $resultat;

}
