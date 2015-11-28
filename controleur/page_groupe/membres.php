<div class="col col-xs-12">
  <div class="row">

<?php
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
      if($user_courant['id_user'] != $id_user)
      {
        echo '<a href="'.INDEX.'?page=page_membre&id_concerne='.$user_courant['id_user'].'&onglet=potins">';
      }
      else
      {        
        echo '<a href="'.INDEX.'?page=page_perso">';
      }
      echo $user_courant['prenom'].' '.$user_courant['nom'].'</a>';

      include_once('modele/admin_groupe.php');

      if(role_gpe($user_courant['id_user'], $groupe['id_groupe'], $bdd) == 2) echo '<br>Admin';



      echo '</div><div class="row">';
      $nb_potins = nb_potins_user_dans_groupe($user_courant['id_user'], $groupe['id_groupe'], $bdd);
      echo '<span class="badge">'.$nb_potins.' potin';
      if($nb_potins != 1) echo 's';
      echo '</span>';
      echo '</div></div>';

  }
}

?>
 </div>

 <?php echo 'Actifs : '.nb_utilisateurs_actifs_groupe($groupe['id_groupe'], $bdd); ?>

</div>