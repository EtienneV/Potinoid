<?php

// Recherche de tous les potins du membre dans les groupes de l'user
// Un groupe après l'autre

?>

<div class="wrapper-potins">

  <?php  
  include_once('modele/rechercher_potins.php');
  include_once('modele/infos_groupe.php');
  include_once('vue/potin/affichage_potin.php');

  echo '<h3>Nouveau potin</h3>';
  include('vue/rediger_potin/nouveau_potin_sur_membre.php'); // Il faut le spécail page membre, avec concerne déjà choisi


  // Début accordéon
  echo '<div class="panel-group" id="accordion-pot-grps-user" role="tablist" aria-multiselectable="true">';



  // pour chacun des groupes en commun
  foreach ($groupes_communs as $ng => $groupe_courant) {
  	
  	$groupe_courant = infos_groupe($groupe_courant, $bdd);

  	$potins_cherches = rechercher_potins_d_un_user_dans_un_groupe($id_user, $groupe_courant['id_groupe'], $user_concerne['id_user'], $bdd);

    //echo '<h3>'.$groupe_courant['nom'].'</h3>';

    echo '<div class="panel panel-default panel-acc-gp">
            <div class="panel-heading" role="tab" id="acc-gp-'.$groupe_courant['id_groupe'].'">
              <h4 class="panel-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-gp-'.$groupe_courant['id_groupe'].'" aria-expanded="false" aria-controls="collapse-gp-'.$groupe_courant['id_groupe'].'">
                  '.$groupe_courant['nom'].'
                </a>
              </h4> 
              <span class="badge pg-gp-nb-pot-gp">'.nb_potins_visibles_sur_user_ds_gpe($groupe_courant['id_groupe'], $user_concerne['id_user'], $id_user, $bdd).' potins ! </span>
            </div>
            <div id="collapse-gp-'.$groupe_courant['id_groupe'].'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="acc-gp-'.$groupe_courant['id_groupe'].'">
          <div class="panel-body">';
          
  	if($potins_cherches != 0) // Si il y a des potins dans ce groupe
  	{
  		  // Pour chacun des potins du groupe
  	  	foreach ($potins_cherches as $i => $potin_courant) {
  	    	$potin_courant = infos_potin($potin_courant, $bdd);
  	    	echo vue_affichage_potin($potin_courant, $id_user, $bdd);
  	  	}
  	}

    echo '</div>
        </div>
      </div>';
  }

  echo '</div>'; // Fin accordéon

echo '<a href="'.INDEX.'?page=page_membre&id_concerne='.$user_concerne['id_user'].'&onglet=groupes">Découvrir des potins dans ses autres groupes !</a>';

echo '</div>';