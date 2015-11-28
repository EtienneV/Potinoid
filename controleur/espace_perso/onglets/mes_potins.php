	<?php
	include_once('modele/rechercher_potins.php');
	$potins_cherches = rechercher_potins_d_un_auteur($id_user, $bdd);

  // Les 10 premiers potins
  $potins_cherches = array_slice ($potins_cherches, 0, 10);
	  
	///$nb_potins = count($potins_cherches);
	
	//$k = 0;
  
	echo '<div class="wrapper-potins">';
	//echo '<div class="col-lg-6 enfant-white">';

  include_once('vue/potin/potin_v4.php');
	
	foreach ($potins_cherches as $i => $potin_courant) {
	
	  $potin_courant = infos_potin($potin_courant, $bdd);
	
	  /*if ($k == floor($nb_potins / 2)) {
	    //echo '</div>';
	    //echo '<div class="col-lg-6 enfant-white">';
	  }*/

    echo vue_potin_v4($potin_courant, $id_user, $bdd);
	
	  //$k++;
	}
  //echo '</div>'; 
	echo '</div>'; 

  echo '<div id="suite-scrolling"></div>
        <button class="btn btn-default btn-block" id="bouton-accueil-scrolling">La suite !</button>';
	?>