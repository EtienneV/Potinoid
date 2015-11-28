<?php 
include_once('controleur/includes/fonctions/rand_callout_color.php');
if(!isset($couleur['i']))
{
  $couleur['i'] = 6;
}

$couleur = rand_callout_color($couleur['i']);
?>


<div class="bs-callout bs-callout-<?php echo  $couleur['couleur']; ?> callout-brouille">
  <h4>
    <?php
    $nom_concernes = explode(',', $potin_courant['concernes']);
    $id_concernes = explode(',', $potin_courant['id_Concernes']);
    $nb_concernes = count($nom_concernes);

    echo 'Sur moi';
    echo ' dans <a href="'.INDEX.'?page=groupe&id_groupe='.$potin_courant['id_Groupe'].'">'.htmlspecialchars($potin_courant['nom_groupe']).'</a>';
    ?>
  </h4>

  <h5>
    <?php
    echo 'Quelqu\'un a écrit, le '.htmlspecialchars($potin_courant['nom_jour_potin']).' '.htmlspecialchars($potin_courant['jour_potin']).' '.htmlspecialchars($potin_courant['mois_potin']).' '.htmlspecialchars($potin_courant['annee_potin']).'<br>';    
    ?>
  </h5>

  <p>
    <?php
  // Affichage du potin brouillé
  include_once('controleur/includes/fonctions/brouiller_message.php');
  brouiller_message($potin_courant['Potin']);

  // Affichage de l'image, si il y en a une
  if($potin_courant['Image'] != '')
  {
    echo '<img class="imagePotin" src="images/brouillage.gif" alt="Photo du potin"/>';
  }

  ?>

  </p>

    <?php
  if($potin_courant['id_auteur'] == $id_user) // Si c'est l'user qui a écrit ce potin
  {
  ?>

    <!-- Bouton de suppression -->
    <form action="#" method="post" name="supprimer_potin" class="form-horizontal ">
      <input type="hidden" name="suppr_potin" value="ok" />
      <input type="hidden" name="numero_potin" value="<?php echo $potin_courant['id_Potin']; ?>" />
      <button class="pull-right btn btn-link" type="submit"><span class="fa fa-trash"></span> Se raviser</button>
    </form>

  <?php
  }
  ?>



  <!-- Bouton de révélation du potin -->
    <form action="#" method="post" name="decouvrir_potin" class="form-horizontal ">
      <input type="hidden" name="decouvrir_potin" value="ok" />
      <input type="hidden" name="numero_potin" value="<?php echo $potin_courant['id_Potin']; ?>" />
      <button class="pull-right btn btn-link" type="submit" 

  <?php
  include_once('controleur/includes/fonctions/calculs_points.php');

  if(calculer_points($id_user, $bdd) < 10)// si on a pas assez de points
  {
    
    echo 'disabled="disabled"';
    
  }
  ?>
  ><span class="glyphicon glyphicon-eye-open"></span> Découvrir !</button>
    </form>




  <?php
    include_once('modele/commentaires.php');
    $commentaires = rechercher_commentaires($potin_courant['id_Potin'], $bdd);
  ?>

  <a href="#">
   <?php
  if($commentaires != 'erreur_nocom') // Si on a des commentaires
  {
    $nb_com = count($commentaires);
    echo $nb_com." commentaire";
    if ($nb_com > 1) {
      echo 's';
    }
  }
  else
  {
    echo 'Commenter';
  }
   ?>
  </a>

  <!-- Boutons de vote -->
<a href="#">

   <?php
    include_once('modele/requetes_vote.php');
    echo nb_votes_positif($potin_courant['id_Potin'], $bdd).' ';
    ?>
    <span class="glyphicon glyphicon-thumbs-up"></span></button>

    <?php
    include_once('modele/requetes_vote.php');
    echo nb_votes_negatif($potin_courant['id_Potin'], $bdd);
    ?>
    <span class="glyphicon glyphicon-thumbs-down"></span></button>

 </a>

</div>