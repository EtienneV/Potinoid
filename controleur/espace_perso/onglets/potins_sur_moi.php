	<?php
	include_once('modele/rechercher_potins.php');
	$potins_cherches = rechercher_potins_sur_user($id_user, $bdd);
	// Les 10 premiers potins
  	$potins_cherches = array_slice ($potins_cherches, 0, 10);
	  
	$nb_potins = count($potins_cherches);


	echo '<div class="wrapper-potins">';

	include_once('vue/potin/potin_v4.php');
	include_once('vue/potin/potin_v4_brouille.php');
	
	foreach ($potins_cherches as $i => $potin_courant) {
	
	  $potin_courant = infos_potin($potin_courant, $bdd);
	
		$reqa = $bdd->prepare('SELECT decouvert FROM cor_potin_users WHERE id_concerne = ? AND id_potin = ?');     
    	$reqa->execute(array($id_user, $potin_courant['id_Potin']));
    	$donneesa = $reqa->fetch();
    	$reqa->closeCursor();

    	include_once('modele/infos_potin.php');

		if (($donneesa['decouvert'] == 1) || (auteur_du_potin($potin_courant['id_Potin'], $bdd) == $id_user)) { // Si on l'a découvert ou qu'on en est l'auteur
			
			echo vue_potin_v4($potin_courant, $id_user, $bdd);
		}
		else
		{
	 		
	 		echo vue_potin_v4_brouille($potin_courant, $id_user, $bdd);
	 	}
	}
	echo '</div>'; 

	echo '<div id="suite-scrolling"></div>
<button class="btn btn-default btn-block" id="bouton-accueil-scrolling">La suite !</button>';
	?>
