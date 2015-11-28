<?php
session_start();

// Includes
include_once('controleur/includes/config.php');
include_once('controleur/includes/fonctions.php');

$bdd = connexionbdd(); // On se connecte à la base de données
$id_user = htmlspecialchars($_SESSION['membre_id']); // Recuperation id de l'utilisateur

?>

<div id="inserer_vote">

<?php
	$potin_courant['id_Potin'] = $_GET['num_potin'];

?>

          <?php
          include_once('modele/infos_potin.php');
          include_once('modele/requetes_vote.php');

          if(deja_vote($potin_courant['id_Potin'], $id_user, $bdd)) // si on a déjà voté
          {
            echo '<p>Vous avez déjà voté !</p>';
            $afficher_resultat = true;
          }
          else if($id_user == auteur_du_potin($potin_courant['id_Potin'], $bdd)) // Si on est l'auteur du potin
          {
            echo '<p>Vous êtes l\'auteur de ce potin</p>';
            $afficher_resultat = true;
          }
          else // On peut voter
          { 
            include('vue/affichage_potin/formulaire_de_vote.php');
            $afficher_resultat = false;
          }

          ?>

        </div>
        <div class="modal-body contenu-centre">

          <?php
                  
          if($afficher_resultat)
          {
            include_once('modele/infos_potin.php');
            $concernes_dans_potin = concernes_potin($potin_courant['id_Potin'], $bdd); // On cherche tous les utilisateurs concernes
  
            foreach ($concernes_dans_potin as $key => $value) {
              if(deja_vote($potin_courant['id_Potin'], $value, $bdd) > 0) // Si le concerne courant a voté
              {
                include_once('modele/infos_user.php');
                $concerne_courant = infos_user($value, $bdd);
  
                echo 'L\'avis de '.$concerne_courant['prenom'].' : ';
  
                $vote_concerne_courant = vote_user($potin_courant['id_Potin'], $value, $bdd);
  
                if($vote_concerne_courant == -1)
                {
                  echo 'C\'est faux !';
                }
                else if($vote_concerne_courant == 0)
                {
                  echo 'Je n\' ai pas d\'avis.';
                }
                else if($vote_concerne_courant == 1)
                {
                  echo 'Je l\'admets, c\'est vrai.';
                }
                echo '<br>';
              }
            }
            echo '<br>';


            $resultat_votes = resultat_vote($potin_courant['id_Potin'], $bdd);

            include('vue/affichage_potin/barre_votes.php');

            if($resultat_votes == 'non_disponible') // Si il n'y a pas eu assez de votes
            {
              echo '<p>Trop peu de personnes ont voté pour déterminer la véracité de ce potin.</p>';
            }
            else if($resultat_votes == 'sur')
            {
              echo '<p>C\'est sûr !</p>';
            }
            else if($resultat_votes == 'possible')
            {
              echo '<p>C\'est possible</p>';
            }
            else if($resultat_votes == 'surement_faux')
            {
              echo '<p>Ce potin est sûrement faux</p>';
            }
            else if($resultat_votes == 'faux')
            {
              echo '<p>Ce n\'est pas vrai</p>';
            }
            else if($resultat_votes == 'calomnie')
            {
              echo '<p>Ce n\' est que pure calomnie !</p>';
            }

          }
?>
              