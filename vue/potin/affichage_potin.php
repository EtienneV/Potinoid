<?php

function vue_affichage_potin($potin_courant, $id_user, $bdd)
{

include_once('controleur/includes/fonctions/parse_smiley.php');
include_once('modele/infos_potin.php');
include_once('modele/requetes_vote.php');
include_once('modele/commentaires.php');
include_once('modele/contenu_vu.php');

// Si on n'avait pas vu ce potin
if(!contenu_deja_vu($id_user, $potin_courant['id_Potin'], 'potin', $bdd))
{
	// On l'enregistre comme vu 
	new_contenu_vu($id_user, $potin_courant['id_Potin'], 'potin', $bdd);
}

$resultat = '';

$resultat .=  '<div class="col-xs-12 col-potin-test" id="potin-'.$potin_courant['id_Potin'].'"  typePotin="v3">

	<div class="row row-potin-test">
					
				<div class="potin-g-test">';

				if($potin_courant['id_auteur'] == $id_user)
				{

					$resultat .=  '<div class="dropdown dropdown-potin">
					    <span id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    	<span class="caret"></span>
					    </span>
					    
					  
					  <ul class="dropdown-menu dropdown-potin-menu" role="menu" aria-labelledby="dLabel">
					    <li><a class="drop-supprimer-potin" idPotin="'.$potin_courant['id_Potin'].'" href="#">Supprimer</a></li>
					  </ul>
					</div>';
				}
					$resultat .=  '<div class="potin-potin">
						<h4>';
							
						    $nom_concernes = explode(',', $potin_courant['concernes']);
						    $id_concernes = explode(',', $potin_courant['id_Concernes']);
						    $nb_concernes = count($nom_concernes);
						
						    $resultat .=  'Sur ';
						
						    for ($i=0; $i < ($nb_concernes - 1); $i++) { //index.php?page=page_membre&id_concerne=3&onglet=potins
						      $resultat .=  '<a href="'.INDEX.'?page=page_membre&id_concerne='.$id_concernes[$i].'&onglet=potins">'.htmlspecialchars($nom_concernes[$i]).'</a>';
						
						      if($i == $nb_concernes - 2)
						      {
						        $resultat .=  ' et ';
						      }
						      else
						      {
						        $resultat .=  ', ';
						      }
						    }
						    $resultat .=  '<a href="'.INDEX.'?page=page_membre&id_concerne='.$id_concernes[$i].'&onglet=potins">'.htmlspecialchars($nom_concernes[$nb_concernes-1]).'</a>';
						
						    $resultat .=  ' dans <a href="'.INDEX.'?page=groupe&id_groupe='.$potin_courant['id_Groupe'].'">'.htmlspecialchars($potin_courant['nom_groupe']).'</a>';
						    
						$resultat .=  '</h4>
					
						<h5>';
							
    						$resultat .=  'Quelqu\'un a écrit, le '.htmlspecialchars($potin_courant['nom_jour_potin']).' '.htmlspecialchars($potin_courant['jour_potin']).' '.htmlspecialchars($potin_courant['mois_potin']).' '.htmlspecialchars($potin_courant['annee_potin']).'<br>';    
    						
						$resultat .=  '</h5>
					
						<p>'.parse_smileys(str_replace("\n","<br/>", $potin_courant['Potin'])).'</p>

						<p>';
						
						// Affichage de l'image, si il y en a une
						if($potin_courant['Image'] != '')
						{
							$resultat .=  '<img class="image-potin-test" src="'.$potin_courant['Image'].'" alt="Photo du potin"/>';
						}
						else
						{
							$resultat .=  '<br>';
						}
						
						$resultat .=  '</p>

					</div>';

					// On détermine la véracité du potin
					switch (resultat_vote($potin_courant['id_Potin'], $bdd)) {
						case 'sur':
							$resv_css = 'sur';
							$resv_text = 'C\'est sûr !';
							$resultat .= '<br>'; // On ajoute un espacement
							break;

						case 'possible':
							$resv_css = 'possible';
							$resv_text = 'C\'est possible.';
							$resultat .= '<br>';
							break;

						case 'surement_faux':
							$resv_css = 'surement_faux';
							$resv_text = 'C\'est sûrement faux ...';
							$resultat .= '<br>';
							break;

						case 'faux':
							$resv_css = 'faux';
							$resv_text = 'C\'est faux !';
							$resultat .= '<br>';
							break;

						case 'calomnie':
							$resv_css = 'calomnie';
							$resv_text = 'Ce n\'est que pure calomnie !';
							$resultat .= '<br>';
							break;
						
						default:
							$resv_css = 'none';
							$resv_text = '';
							break;
					}

					$resultat .= '<div class="potin-vote-wrapper">';

					
					// Affichage de la véracité du potin
					$resultat .= '<div class="potin-resultat-vote resv-'.$resv_css.'" data-toggle="tooltip" data-placement="top" title="Cet avis est donné par un algorithme secret">
						'.$resv_text.'
					</div>';

						

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
				          	$resultat .=  '<div class="vote-progressbar-wraper">';
							if($barre_positif != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #5cb85c; width: '.$barre_positif.'%;"></span>';
							if($barre_neutre != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #5bc0de; width: '.$barre_neutre.'%;"></span>';
							if($barre_negatif != 0) $resultat .=  '<span class="vote-progressbar" style="border: 1px solid #d9534f; width: '.$barre_negatif.'%;"></span>';
							$resultat .=  '</div>';
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
							$resultat .=  '" typeBouton="vrai" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v3">C\'est vrai</span>';

							$resultat .=  '<span class="potin-bouton Bnesaitpas';
							if ($vote_user == 0) $resultat .=  '-active';
							$resultat .=  '" typeBouton="nesaitpas" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v3">Je ne sais pas</span>';

							$resultat .=  '<span class="potin-bouton Bfaux';
							if ($vote_user == -1) $resultat .=  '-active';
							$resultat .=  '" typeBouton="faux" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v3">C\'est faux</span>';

							$resultat .=  '</div>';
				        } 
				        else // On peut voter
				        { 
							$resultat .=  '<div class="boutons-vote-wrapper">';
							$resultat .=  '<span class="potin-bouton Bvrai" typeBouton="vrai" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v3">C\'est vrai</span>';
							$resultat .=  '<span class="potin-bouton Bnesaitpas" typeBouton="nesaitpas" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v3">Je ne sais pas</span>';
							$resultat .=  '<span class="potin-bouton Bfaux" typeBouton="faux" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v3">C\'est faux</span>';
							$resultat .=  '</div>';
				        }

						


						
					$resultat .=  '</div>
				</div>
		
				<div class="potin-d-test">
					<div class="potin-commentaires">
					
					  	<h4>Commentaires</h4>';

					  	
					  	
						$commentaires = rechercher_commentaires($potin_courant['id_Potin'], $bdd);

						if($commentaires != 'erreur_nocom') // Si on a des commentaires
		                {
		
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
		                }
		                else
		                {
		                  	$resultat .=  '<h5>Soyez le premier à commenter !</h5>';
		                }
						
					
					  	
					
					$resultat .=  '</div>

					<div class="potin-form-com">
						<textarea class="potin-textarea-com" rows="1" id="potin-com" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v3" placeholder="Commentez !"></textarea>
					

						<div class="potin-envoyer-com" idUser="'.$id_user.'" idPotin="'.$potin_courant['id_Potin'].'" typePotin="v3"><span class="glyphicon glyphicon-send" aria-hidden="true"></span></div>
					</div>
				</div>
				
	</div>

</div>';

return $resultat;

}
