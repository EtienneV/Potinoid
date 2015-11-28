<?php

include_once('modele/infos_user.php');
include_once('modele/infos_groupe.php');
include_once('modele/appartient_au_groupe.php');
include_once('modele/rechercher_user.php');

?>

<nav>        
  <div class="p-nav">
  <div class="row" id="row_panel_menu">

  <?php

  $user = infos_user($id_user, $bdd);

  if($user['avatar'] != '')
  {
    $photo_profile = $user['avatar'];
  }
  else{
    $photo_profile = 'default';
  }
  ?>

  <div class="encart-usr-photo" style="background:url(images/profile/<?php echo $photo_profile; ?>-180.jpg) no-repeat 0px 0px;">&nbsp;</div>
  <div class="encart-usr-masque"></div>
  <a class="encart-usr-nom" href="<?php echo INDEX; ?>?page=page_perso"><?php echo htmlspecialchars($_SESSION['membre_prenom']).'<br>'; ?> </a>
  <?php echo '<span class="badge encart-usr-potins"  data-toggle="tooltip" data-placement="right" title="Dépensez vos points pour les découvrir !">' .nb_potins_sur_user($id_user). ' potins !</span>'; // On affiche le nombre de potins calculer_points($id_user, $bdd) ?>
  <?php echo '<span class="badge points-user encart-usr-points" data-toggle="tooltip" data-placement="right" title="Dès 10 points, 1 potin !">'.'...'.' points</span>'; ?>
    



    <!--<div class="col-lg-6"> <!-- photo de l'user-->
<!--
      <?php 
      if($_SESSION['membre_avatar'] != '') // si le membre a un avatar
      {
        echo '<img class="avatar" src="images/profile/'.$_SESSION['membre_avatar'].'-50.jpg" alt="Avatar"/>';
        //echo '<div class="div-avatar" style="background:url('.$_SESSION['membre_avatar'].') no-repeat 0px 0px;"></div>';
      }
      else{
        echo '<img src="images/profile/default-50.jpg" alt="Photo du potin"/>';
      }
      ?>

    </div>-->

    <!--<div class="col-lg-6"> <!-- Nom, nb potins et points -->
      <!--<div class="row">
        <a href="<?php echo INDEX; ?>?page=page_perso"><?php echo htmlspecialchars($_SESSION['membre_prenom']).'<br>'; ?> </a>
      </div>
      <div class="row">
        <?php echo '<span class="badge"  data-toggle="tooltip" data-placement="right" title="Dépensez vos points pour les découvrir !">' .nb_potins_sur_user($id_user). ' potins !</span>'; // On affiche le nombre de potins calculer_points($id_user, $bdd) ?>
        <?php echo '<span class="badge points-user" data-toggle="tooltip" data-placement="right" title="Dès 10 points, 1 potin !">'.'...'.' points</span>'; ?>
        <br><br><br>
      </div>
    </div>-->
  </div>
  </div>
    
  <div class="panel panel-default" id="panel-menu">
    <div class="panel-body" id="panel2-menu">
    
      <ul class="nav nav-pills nav-stacked">
    
        <!--<li class="<?php if(isset($nom_page) && ($nom_page == 'demarrage')) echo 'active';?>"> 
          <a href=<?php echo '"'.INDEX.'"'; ?> class="no-radius"> <span class="glyphicon glyphicon-home"></span> Accueil </a> 
        </li>-->

        <li class="nav-header"><h4><small>MES GROUPES</small></h4></li>

        <?php     

        // Tous les groupes de l'user concerne
        $groupes_user = groupes_d_un_user($id_user, $bdd);

        if($groupes_user != 'aucun_groupe') // Si l'user est dans au moins un groupe
        {
          foreach ($groupes_user as $key => $groupe_courant) {

            $groupe_courant = infos_groupe($groupe_courant, $bdd); // Récupération des infos du groupe

            // Si on est placé dans c'est le groupe courant
            if(isset($nom_page) && (($nom_page == 'groupe') || ($nom_page == 'potins_user')) && $groupe['id_groupe'] == $groupe_courant['id_groupe']) 
            {
              echo '<li class="active">';
            }
            else echo '<li>';

            // Creation de l'url vers la page du groupe
            echo '<a href="'.INDEX.'?page=groupe&id_groupe='.htmlspecialchars($groupe_courant['id_groupe']).'"  class="li-no-radius"> <span class="glyphicon glyphicon-book"></span> '.htmlspecialchars($groupe_courant['nom']).'</a></li>';
            

            if(isset($nom_page) && (($nom_page == 'groupe') || ($nom_page == 'potins_user')) && $groupe['id_groupe'] == $groupe_courant['id_groupe']) 
            {

              $users_groupe_courant = rech_users_d_un_groupe($groupe_courant['id_groupe'], $bdd);

              echo '<div class="p-nav-liste-membres">';

              foreach ($users_groupe_courant as $key => $user_courant) {
                $user_courant = infos_user($user_courant, $bdd);

                echo '<div class="row"><div class="col-lg-offset-1 col-lg-2"><span class="badge">'.nb_potins_visibles_sur_user_ds_gpe($groupe_courant['id_groupe'], $user_courant['id_user'], $id_user, $bdd).'</span></div><div class="col-lg-offset-1 col-lg-8"><a href="'.INDEX.'?page=page_membre&id_concerne='.$user_courant['id_user'].'&onglet=potins">'.htmlspecialchars($user_courant['prenom']).' '.substr(htmlspecialchars($user_courant['nom']), 0, 1).'</a></div></div>'; 
              }

              echo '</div>';
            }
          }
        }
        else
        {
          echo 'Vous n\'êtes dans aucun groupe';
        }
        ?>
    
        <br>
    
        <!--<li class="nav-header"><h4><small>EVENEMENT</small></h4></li>
    
        <li class="nav-header"> <a href="campagne.php" class="no-radius"> <span class="glyphicon glyphicon-exclamation-sign"></span> Campagne BDE </a> </li>
    
        <br> -->
    
        <li class="<?php if(isset($nom_page) && ($nom_page == 'liste_groupes')) echo 'active';?>"> 
          <a href=<?php echo '"'.INDEX.'?page=liste_groupes"'; ?> class="no-radius"> <span class="glyphicon glyphicon-th-list"></span> Tous les groupes </a> 
        </li>

      </ul>
    </div> 
  </div>

</nav>
