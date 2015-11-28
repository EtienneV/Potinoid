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

function vue_potin_v4($potin_courant, $id_user, $bdd)
{

	$groupe_courant = infos_groupe($potin_courant['id_Groupe'], $bdd);

	// Si on n'avait pas vu ce potin
	if(!contenu_deja_vu($id_user, $potin_courant['id_Potin'], 'potin', $bdd))
	{
		// On l'enregistre comme vu 
		new_contenu_vu($id_user, $potin_courant['id_Potin'], 'potin', $bdd);
	}

	$resultat = '';

	$resultat .= '<div class="row-potin-v4 item" id="potin-'.$potin_courant['id_Potin'].'" typePotin="v4">';

	// Header
	$resultat .= '<div class="header-potin-v4">';

	if($groupe_courant['image'] != '') $bandeau_groupe = $groupe_courant['image'];
	else $bandeau_groupe = 'default';

	$resultat .= '<div class="hp4-bandeau">
			        <img src="images/groupe/'.$bandeau_groupe.'-bd.jpg">
			      </div>';

	// Affichage des concernes
	$resultat .= '<div class="hp4-wrapper-profile">';

	$nom_concernes = explode(',', $potin_courant['concernes']);
	$id_concernes = explode(',', $potin_courant['id_Concernes']);
	$nb_concernes = count($nom_concernes);

	foreach ($id_concernes as $key => $concerne_courant) {
		$concerne_courant = infos_user($concerne_courant, $bdd);

		if($concerne_courant['avatar'] != '')
		{
		  $photo_profile = $concerne_courant['avatar'];
		  $initiales = '';
		}
		else {
		  $photo_profile = 'default';
		  $initiales = substr($concerne_courant['prenom'], 0, 1).' '.substr($concerne_courant['nom'], 0, 1);
		}

		$resultat .=  '<a href="'.INDEX.'?page=page_membre&id_concerne='.$concerne_courant['id_user'].'&onglet=potins">
						<div class="hp4-wrp"><div class="hp4-rounded-profile" style="background:url(images/profile/'.$photo_profile.'-50.jpg) no-repeat 0px 0px;"  data-toggle="tooltip" data-placement="top" title="'.$concerne_courant['prenom'].' '.$concerne_courant['nom'].'">'.
						$initiales.'</div></div></a>';
	}

	$resultat .= '</div>';

	$padding_nom = 10+$nb_concernes*55+10; // Calcul du décalage du nom du groupe 

	$resultat .= '<div class="hp4-nom-gp">'.'<a href="'.INDEX.'?page=groupe&id_groupe='.$potin_courant['id_Groupe'].'" style="padding-left:'.$padding_nom.'px">'.htmlspecialchars($potin_courant['nom_groupe']).'</a>'.'</div>';



	$resultat .= '</div>';

	// Corps du potin
	$resultat .= '<div class="corps-potin-v4">';

	$resultat .= '<div class="date-potin-v4">'.$potin_courant['nom_jour_potin'].' '.$potin_courant['jour_potin'].' '.$potin_courant['mois_potin'].' '.$potin_courant['annee_potin'];

	if($potin_courant['id_auteur'] == $id_user) // Pour supprimer le potin
	{
		$resultat .=  '<span id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    	<span class="caret"></span>
		    </span>
		  <ul class="dropdown-menu dropdown-potin-menu" role="menu" aria-labelledby="dLabel">
		    <li><a class="drop-supprimer-potin" idPotin="'.$potin_courant['id_Potin'].'" href="#">Supprimer</a></li>
		  </ul>';
	}

	$resultat .= '</div>';

	// Le texte du potin
	$resultat .= '<div class="texte-potin-v4">'.parse_smileys(str_replace("\n","<br/>", $potin_courant['Potin'])).'</div>';

	$resultat .= '</div>'; // Fin corps


	// Image
	$resultat .= '<div class="image-potin-v4">';

	if($potin_courant['Image'] != '')
	{
		$resultat .=  '<img class="imagePotin" src="'.$potin_courant['Image'].'" alt="Photo du potin">';
	}

	$resultat .= '</div>'; // Fin image


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

	if($id_user == auteur_du_potin($potin_courant['id_Potin'], $bdd)) // Si on est l'auteur du potin
    {
    	if($nb_de_votants != 0)
    	{
      		$resultat .=  '<div class="vote-progressbar-wraper">';
			if($barre_positif != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #5cb85c; width: '.$barre_positif.'%;"></span>';
			if($barre_neutre != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #5bc0de; width: '.$barre_neutre.'%;"></span>';
			if($barre_negatif != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #d9534f; width: '.$barre_negatif.'%;"></span>';
			$resultat .=  '</div>';
		}
    }
	else if(deja_vote($potin_courant['id_Potin'], $id_user, $bdd)) // si on a déjà voté
    {
		$resultat .=  '<div class="vote-progressbar-wraper">';
		if($barre_positif != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #5cb85c; width: '.$barre_positif.'%;"></span>';
		if($barre_neutre != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #5bc0de; width: '.$barre_neutre.'%;"></span>';
		if($barre_negatif != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #d9534f; width: '.$barre_negatif.'%;"></span>';
		$resultat .=  '</div>';

		$vote_user = vote_user($potin_courant['id_Potin'], $id_user, $bdd);

		$resultat .=  '<div class="boutons-vote-wrapper">';

		$resultat .=  '<span class="potin-bouton Bvrai';
		if ($vote_user == 1) $resultat .=  '-active';
		$resultat .=  '" typeBouton="vrai" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v4">C\'est vrai</span>';

		$resultat .=  '<span class="potin-bouton Bnesaitpas';
		if ($vote_user == 0) $resultat .=  '-active';
		$resultat .=  '" typeBouton="nesaitpas" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v4">Je ne sais pas</span>';

		$resultat .=  '<span class="potin-bouton Bfaux';
		if ($vote_user == -1) $resultat .=  '-active';
		$resultat .=  '" typeBouton="faux" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v4">C\'est faux</span>';

		$resultat .=  '</div>';
    } 
    else // On peut voter
    { 
		$resultat .=  '<div class="boutons-vote-wrapper">';
		$resultat .=  '<span class="potin-bouton Bvrai" typeBouton="vrai" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v4">C\'est vrai</span>';
		$resultat .=  '<span class="potin-bouton Bnesaitpas" typeBouton="nesaitpas" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v4">Je ne sais pas</span>';
		$resultat .=  '<span class="potin-bouton Bfaux" typeBouton="faux" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v4">C\'est faux</span>';
		$resultat .=  '</div>';
    }

	$resultat .=  '</div>';



	// Commentaires
	$resultat .= '<div class="com-potin-v4">';

    $commentaires = rechercher_commentaires($potin_courant['id_Potin'], $bdd);

	if($commentaires != 'erreur_nocom') // Si on a des commentaires
	{
		$resultat .=  '<div class="cp4-wrapper">';
	
	  	foreach ($commentaires as $clef => $valeur) {
	
	    	$com_courant = infos_commentaire($valeur, $bdd);

	    	// Si on n'avait pas vu ce commentaire
			if(!contenu_deja_vu($id_user, $com_courant['id_com'], 'comment', $bdd))
			{
				// On l'enregistre comme vu 
				new_contenu_vu($id_user, $com_courant['id_com'], 'comment', $bdd);
			}
	
	    	//$resultat .=  '<h5>'.$com_courant['prenom'].' '.$com_courant['nom'].'</h5>';
	    	$resultat .=  '<p><span class="fa fa-comment-o" style="color:grey;" aria-hidden="true"></span> '.parse_smileys(str_replace("\n","<br/>", $com_courant['texte'])).'</p>';
	  	}

	  	$resultat .= '<div class="prochain-com"></div>
				     	</div>';

		$texte_textarea = 'Commentez !';
	}
	else
	{
	  	$texte_textarea = 'Soyez le premier à commenter !';
	}

	$resultat .=  '<div class="potin-form-com-v4">
				        <textarea class="potin-textarea-com" rows="1" id="potin-com" placeholder="'.$texte_textarea.'!" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v4"></textarea>
				      
				        <div class="potin-envoyer-com" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v4"><span class="glyphicon glyphicon-send" aria-hidden="true"></span></div>
				      </div>
				    </div>';


$resultat .= '</div>';



return $resultat;

}
