<?php

include_once('vue/header.php');

?>

<body>
    
  <header>


<!-- Navbar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">

      <a class="navbar-brand" href=<?php echo '"'.INDEX.'"'; ?>><h1>Potinoïd</h1></a>

    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">          
      <ul class="nav navbar-nav navbar-right">
        <li>
          <div class="voir-sur-moi"><a href=<?php echo '"'.INDEX.'"'; ?>>Voir les potins sur moi</a></div>
        </li>
      	<li>
      		<a href=<?php echo '"'.INDEX.'"'; ?>>Connexion</a>
      	</li>
      </ul>
    </div>
  </div>
</nav>

</header>

  <div class="container">

  	<section>
      <div class="col-lg-12">
        <div class="row">

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

    <a href=<?php echo '"'.INDEX.'"'; ?> class="pull-right btn btn-success pp-gp-btn-hdr" type="submit">Se connecter pour rejoindre ce groupe !</a>

</div>

<?php
echo '<div class="well nav-whitepage">';

include_once('modele/infos_groupe.php');
$nb_potins_gp = nb_potins_ds_gpe($groupe['id_groupe'], $bdd);
$nb_potins_gp = $nb_potins_gp['COUNT(*)'];
echo '<div class="pp-gp-nb-p">'.$nb_potins_gp.' potins sur les membres de '.$groupe['nom'].'</div>';

echo '<h3>'.'Membres'.'</h3>';

include_once('modele/rechercher_user.php');
$membres = rech_users_d_un_groupe($groupe['id_groupe'], $bdd);

if($membres != 0)
{
  foreach ($membres as $i => $user_courant) {
      include_once('modele/infos_user.php'); 
      $user_courant = infos_user($user_courant, $bdd);

      echo '<div class="col col-sm-2 vignette-user">';
      echo '<div class="row row-avatar">';
      if($user_courant['avatar'] != '')
      {
        echo '<p class="p-avatar"> <img class="vignette-avatar" src="images/profile/'.$user_courant['avatar'].'-50.jpg" alt="Avatar"/> </p>';
      }
      else{
        echo '<p class="p-avatar"> <img class="vignette-avatar" src="images/profile/default.png" alt="Avatar"/> </p>';
      }
      echo '</div><div class="row">';
      echo '<a href="'.INDEX.'?page=page_membre&id_concerne='.$user_courant['id_user'].'&onglet=potins">';
      
      echo $user_courant['prenom'].' '.$user_courant['nom'].'</a>';

      echo '</div><div class="row">';
      $nb_potins = nb_potins_user_dans_groupe($user_courant['id_user'], $groupe['id_groupe'], $bdd);
      echo '<span class="badge">'.$nb_potins.' potin';
      if($nb_potins != 1) echo 's';
      echo '</span>';
      echo '</div></div>';

  }
}






echo '</div>';
?>
		</div>
      </div>
    </section>
  </div>
</body>

<?php

include('vue/footer.php');

?>