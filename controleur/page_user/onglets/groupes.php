<?php
include_once('controleur/includes/fonctions/calculs_points.php');
include_once('modele/rechercher_potins.php');
include_once('vue/potin/affichage_potin.php');

echo '<h3>Groupes en commun</h3>';
echo '<ul>';

// Pour chacun des groupes en commun
foreach ($groupes_communs as $ng => $groupe_courant) {
	$groupe_courant = infos_groupe($groupe_courant, $bdd);

	echo '<li><a href="'.INDEX.'?page=groupe&id_groupe='.$groupe_courant['id_groupe'].'">'.$groupe_courant['nom'].'</a> <span class="badge">'.nb_potins_visibles_sur_user_ds_gpe($groupe_courant['id_groupe'], $user_concerne['id_user'], $id_user, $bdd).' potins ! </span></li>';
}

echo '</ul>';

// Tous les groupes de l'user concerne
$groupes_concerne = groupes_d_un_user($user_concerne['id_user'], $bdd);

// On prend ses groupes auxquels on n'appartient pas
$ses_groupes = array_diff($groupes_concerne, $groupes_communs);

if(sizeof($ses_groupes) != 0) // Si il y a d'autres groupes
{
	echo '<h3>Ses groupes</h3>';

	// Début accordéon
	echo '<div class="panel-group" id="accordion-pot-grps-user" role="tablist" aria-multiselectable="true">';


	foreach ($ses_groupes as $key => $groupe_courant) {
		$groupe_courant = infos_groupe($groupe_courant, $bdd);

		$nb_potins_sur_user_ds_gp_courant = nb_potins_sur_user_ds_gpe($groupe_courant['id_groupe'], $user_concerne['id_user'], $bdd);

		echo '<div class="panel panel-default panel-acc-gp">
	            <div class="panel-heading" role="tab" id="acc-gp-'.$groupe_courant['id_groupe'].'">
	              <h4 class="panel-title">
	                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-gp-'.$groupe_courant['id_groupe'].'" aria-expanded="false" aria-controls="collapse-gp-'.$groupe_courant['id_groupe'].'">
	                  '.$groupe_courant['nom'].'
	                </a>
	              </h4> 
	              <span class="badge pg-gp-nb-pot-gp">'.$nb_potins_sur_user_ds_gp_courant.' potins ! </span>
	            </div>
	            <div id="collapse-gp-'.$groupe_courant['id_groupe'].'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="acc-gp-'.$groupe_courant['id_groupe'].'">
	          <div class="panel-body">';

	    echo '<ul>';

	    echo '<li><a href="'.INDEX.'?page=groupe&id_groupe='.$groupe_courant['id_groupe'].'">Voir la page du groupe</a></li>';

	    // On cherche les potins qu'on a découverts
	    $potins_decouverts = potins_decouverts_sur_user_dans_gpe($user_concerne['id_user'], $groupe_courant['id_groupe'], $id_user);

	    if($potins_decouverts != 0) // Si on a déjà découvert des potins
	    {
	    	$nb_potins_a_decouvrir = $nb_potins_sur_user_ds_gp_courant - sizeof($potins_decouverts);
	    }
	    else
	    {
	    	$nb_potins_a_decouvrir = $nb_potins_sur_user_ds_gp_courant;
	    }

	    if($nb_potins_a_decouvrir > 0)// Si il y a des potins à découvrir
	    {
		    if(calculer_points($id_user, $bdd) >= 20) // Si on a assez de points
		    {
		    	echo '<li><a class="decouvrir-potin-externe" idConcerne="'.$user_concerne['id_user'].'" idGroupe="'.$groupe_courant['id_groupe'].'">Découvrir un potin sur '.$user_concerne['prenom'].'</a> (20 points)</li>';
		    }
		    else // Sinon
		    {
		    	echo '<li>A partir de 20 points, tu pourras découvrir un potin sur '.$user_concerne['prenom'].' dans '.$groupe_courant['nom'].' !</li>';
		    }
		}

	    echo '</ul>';

	    echo '<div class="potin-externe-suivant-'.$groupe_courant['id_groupe'].'"></div>';

	    if($potins_decouverts != 0)
	    {
		    foreach ($potins_decouverts as $key => $potin_courant) {
		    	$potin_courant = infos_potin($potin_courant, $bdd);
	  
	      		echo vue_affichage_potin($potin_courant, $id_user, $bdd);
		    }
		}

        echo '</div>
   		    </div>
   		  </div>';
	}

	echo '</div>'; // Fin accordéon
}