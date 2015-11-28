<?php 

include_once('controleur/includes/fonctions/rand_callout_color.php');
include_once('modele/requetes_vote.php');

if(!isset($couleur['i']))
{
  $couleur['i'] = 6;
}

$couleur = rand_callout_color($couleur['i']);
?>


<div class="bs-callout bs-callout-<?php echo  $couleur['couleur']; ?> bgd-<?php echo resultat_vote($potin_courant['id_Potin'], $bdd); ?>">
  <h4>
    <?php
    $nom_concernes = explode(',', $potin_courant['concernes']);
    $id_concernes = explode(',', $potin_courant['id_Concernes']);
    $nb_concernes = count($nom_concernes);

    echo 'Sur ';

    for ($i=0; $i < ($nb_concernes - 1); $i++) { //?page=page_membre&id_concerne='.$id_concernes[$i].'&onglet=potins
      echo '<a href="'.INDEX.'?page=page_membre&id_concerne='.$id_concernes[$i].'&onglet=potins">'.htmlspecialchars($nom_concernes[$i]).'</a>';

      if($i == $nb_concernes - 2)
      {
        echo ' et ';
      }
      else
      {
        echo ', ';
      }
    }
    echo '<a href="'.INDEX.'?page=page_membre&id_concerne='.$id_concernes[$nb_concernes-1].'&onglet=potins">'.htmlspecialchars($nom_concernes[$nb_concernes-1]).'</a>';

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

  echo htmlspecialchars($potin_courant['Potin']).'</br></br>'; // Affichage du potin

  // Affichage de l'image, si il y en a une
  if($potin_courant['Image'] != '')
  {
    echo '<img class="imagePotin" src="'.$potin_courant['Image'].'" alt="Photo du potin"/>';
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

  if(($potin_courant['id_Potin'] == 240) && $id_user == 43)
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

<!-- Bouton de commentaire -->
  <?php
    include_once('modele/commentaires.php');
    $commentaires = rechercher_commentaires($potin_courant['id_Potin'], $bdd);
  ?>

   

  <a href="#"
   data-toggle="modal"
   data-target="#commentModal"
   data-whatever="commentModal.<?php echo $potin_courant['id_Potin']; ?>">
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

<a href="#"
   data-toggle="modal"
   data-target="#voteModal"
   data-whatever="voteModal.<?php echo $potin_courant['id_Potin']; ?>">

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

