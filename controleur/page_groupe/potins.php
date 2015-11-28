<div class="wrapper-potins">

  <h3>Nouveau potin</h3>
  <?php include('vue/rediger_potin/nouveau_potin.php'); ?>     

  <div id="emplacement-nouveau-potin"></div>

  <?php  

  include_once('modele/rechercher_potins.php');
  //include_once('vue/potin/affichage_potin.php');
  include_once('vue/potin/potin_v4.php');
  $potins_cherches = rechercher_potins_d_un_groupe_de_user($id_user, $groupe['id_groupe'], $bdd);

  // Les 10 premiers potins
  $potins_cherches = array_slice ($potins_cherches, 0, 10);

  if($potins_cherches != 'plus_de_potins' && $potins_cherches != 'pas_de_potins' && $potins_cherches != 'erreur')
  {

    foreach ($potins_cherches as $i => $potin_courant) {
  
      $potin_courant = infos_potin($potin_courant, $bdd);
  
      //echo vue_affichage_potin($potin_courant, $id_user, $bdd);

      echo vue_potin_v4($potin_courant, $id_user, $bdd);
    }

  }
  else
  {
    echo 'Il n\'y a pas de potins à afficher';
  }

  ?>

</div>

</div>

<div id="suite-scrolling"></div>
<button class="btn btn-default btn-block" id="bouton-accueil-scrolling">La suite !</button>