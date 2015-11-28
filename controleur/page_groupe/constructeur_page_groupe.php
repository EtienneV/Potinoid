<?php

include_once('modele/appartient_au_groupe.php');
$est_dans_groupe = appartient_au_groupe($id_user, $groupe['id_groupe'], $bdd);

?>

<!--
<div class="page-header">
<h2><?php echo htmlspecialchars($groupe['nom']); ?></h2>
<em><?php echo htmlspecialchars($groupe['description']); ?></em>
<div id="pac-charger">Chargement ... <img src="images/ajax-loader.gif"></img></div>
</div>
-->


<!-- Header -->

<?php

if($groupe['image'] != '')
{
  $photo_profile = $groupe['image'];
}
else{
  $photo_profile = 'default';
}


?>

<div class="header-page-user-v2">

   <div class="hpu-fond-v2">

      <?php
      
      ?>

      <img src="images/groupe/<?php echo $photo_profile; ?>-bd.jpg">
   </div>

   <div class="hpu-user-v2">
    <div class="hpu-v2-roundedImage" style="background:url(images/groupe/<?php echo $photo_profile; ?>-180.jpg) no-repeat 0px 0px;">&nbsp;</div>

    <div class="hpu-nom-v2">
      <h2><?php echo htmlspecialchars(($groupe['nom'])); ?></h2>

      <?php
      echo '<em>'.$groupe['description'].'</em>';
      ?>
        </div>
    </div> 

<!-- Onglets -->
<ul class="hpu-v2-onglets">
  <?php include_once('controleur/page_groupe/onglets_page_groupe.php'); ?>
</ul>

</div>

<div id="pac-charger">Chargement ... <img src="images/ajax-loader.gif"></div>



<?php
  
// Si l'utilisateur est membre du groupe | OU QUE C'EST l'ADMIN TOUT PUISSANT
if(($est_dans_groupe == true) || ($id_user == 91))
{

  	 

	echo '<div class="well nav-whitepage">';


	if($onglet == 'potins')
  {
		include_once('controleur/page_groupe/potins.php');
	}
	else if($onglet == 'membres')
  {
    include_once('controleur/page_groupe/membres.php');
  }
  else if($onglet == 'parametres')
  {
    include_once('controleur/page_groupe/parametres.php');
  }
  else if($onglet == 'admin_gpe')
  {
    include_once('controleur/page_groupe/admin_groupe.php');
  }
  else
  {
    include_once('controleur/page_groupe/potins.php');
  }


	echo '</div>';

}
else // Si l'user n'est pas mambre du groupe
{
  echo '<div class="col-lg-12">';

  echo 'Vous ne faites pas partie de ce groupe.<br>';

  echo '<a href="'.INDEX.'?page=inscription_groupe&id_groupe='.$groupe['id_groupe'].'">S\'inscrire à ce groupe</a>';

  echo '</div>';

  
}

?>

