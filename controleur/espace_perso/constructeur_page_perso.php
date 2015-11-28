<!-- Header -->

<?php

if($_SESSION['membre_avatar'] != '')
{
  $photo_profile = $_SESSION['membre_avatar'];
}
else{
  $photo_profile = 'default';
}


?>

<div class="header-page-user-v2">

   <div class="hpu-fond-v2">

   		<?php
   		
	    ?>

      <img src="images/profile/<?php echo $photo_profile; ?>-bd.jpg">
   </div>

   <div class="hpu-user-v2">
		<div class="hpu-v2-roundedImage" style="background:url(images/profile/<?php echo $photo_profile; ?>-180.jpg) no-repeat 0px 0px;">&nbsp;</div>

		<div class="hpu-nom-v2">
			<h2><?php echo htmlspecialchars($_SESSION['membre_prenom']).' '.htmlspecialchars($_SESSION['membre_nom']); ?></h2>

			<?php
			include_once('modele/infos_user.php');
			$infos_user = infos_user($id_user, $bdd);
			echo '<em>'.$infos_user['description'].'</em>';
			?>
        </div>
    </div> 

<!-- Onglets -->
<ul class="hpu-v2-onglets">
  <?php include_once('controleur/espace_perso/onglets_page_perso.php'); ?>
</ul>

</div>

<div id="pac-charger">Chargement ... <img src="images/ajax-loader.gif"></img></div>





<!--

	<div class="page-header page-user">
	  <div class="row">
	    <div class="col-lg-1">
		    <?php 
		    if($_SESSION['membre_avatar'] != '')
		    {
		      echo '<img class="avatar" src="images/profile/'.$_SESSION['membre_avatar'].'-180.jpg" alt="Avatar" id="avatar_potin"/>';
		    }
		    else{
		      echo '<img src="images/profile/default.png" alt="Photo du potin" id="avatar_potin"/>';
		    }
		    ?>
	    </div>

	    <div class="col-lg-11">
	    	<h2><?php echo htmlspecialchars($_SESSION['membre_prenom']).' '.htmlspecialchars($_SESSION['membre_nom']); ?></h2>

	    	<?php
	    	include_once('modele/infos_user.php');
	    	$infos_user = infos_user($id_user, $bdd);

            echo '<em>'.$infos_user['description'].'</em>';

            ?>

	    </div>
	    <div id="pac-charger">Chargement ... <img src="images/ajax-loader.gif"></img></div>
	  </div>
	</div>
-->


<?php include_once('controleur/espace_perso/onglets_page_perso.php'); ?>

<div class="well nav-whitepage">

	<?php
	if($onglet == 'mes_potins')
    {
		include_once('controleur/espace_perso/onglets/mes_potins.php');
	}
	else if($onglet == 'potins_sur_moi')
    {
    	include_once('controleur/espace_perso/onglets/potins_sur_moi.php');
    }
    else if($onglet == 'recap_points')
    {
    	include_once('controleur/espace_perso/onglets/recap_points.php');
    }
    else if($onglet == 'parametres')
    {
    	include_once('controleur/espace_perso/onglets/parametres.php');
    }
    else
    {
    	include_once('controleur/espace_perso/onglets/mes_potins.php');
    }
	?>

</div>