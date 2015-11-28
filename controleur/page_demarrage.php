<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Haut page -->
<div style="text-align:center;">
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9919756477198596"
     data-ad-slot="3479048869"></ins></div>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

<!--<div class="page-header">
  <h2>Derniers potins dans tes groupes</h2>
  <div id="pac-charger">Chargement ... <img src="images/ajax-loader.gif"></img></div>
</div>-->

<?php

include_once('modele/contenu_vu.php');
$ptns_nn_vus = liste_potins_non_vus($id_user, $bdd);

if(($ptns_nn_vus != 'error_aucun_potin') && (sizeof($ptns_nn_vus) != 0))
{
  echo '<div class="alert alert-success" role="alert">';
  echo '<h3>Quoi de neuf ?</h3>';
  echo '<ul>';
  echo '<li><a href="'.INDEX.'?page=new_contenu&type=potins&potins='.htmlspecialchars(serialize($ptns_nn_vus)).'"> '.sizeof($ptns_nn_vus).' nouveaux potins !</a><br></li>';
  echo '</ul>';
  echo '</div>';
}

?>


<div class="wrapper-potins">

  
  <!--<h3>Nouveau potin</h3>-->
  <?php include('vue/rediger_potin/nouveau_potin.php'); ?>     

  <div id="emplacement-nouveau-potin"></div>

  <?php  

  include_once('modele/rechercher_potins.php');
  $potins_cherches = rechercher_potins_des_groupes_de_user_offset($id_user, 10, 0, $bdd);

  include_once('vue/potin/affichage_potin.php');

  include_once('vue/potin/potin_v4.php');

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

  <div id="fin-colonne"></div>

</div>

</div>

<div id="suite-scrolling"></div>
<button class="btn btn-default btn-block" id="bouton-accueil-scrolling">La suite !</button>