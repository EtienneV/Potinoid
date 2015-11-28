<?php
session_start();

// Includes
include_once('controleur/includes/config.php');
include_once('controleur/includes/fonctions.php');

$bdd = connexionbdd(); // On se connecte à la base de données
$id_user = htmlspecialchars($_SESSION['membre_id']); // Recuperation id de l'utilisateur

$potin_courant['id_Potin'] = $_GET['num_potin'];

include_once('modele/commentaires.php');
$commentaires = rechercher_commentaires($potin_courant['id_Potin'], $bdd);

?>

<div id="inserer_comment">

<?php
	
  if($commentaires != 'erreur_nocom') // Si on a des commentaires
                {

                  foreach ($commentaires as $clef => $valeur) {

                    $com_courant = infos_commentaire($valeur, $bdd);
  
                    echo $com_courant['prenom'].' '.$com_courant['nom'].' : '.$com_courant['texte'].'<br>';
                  }
                }
                else
                {
                  echo 'Aucun commentaire<br>';
                }

                ?>

                <br>
                <form method="post" action="#">
                  <input type="hidden" name="new_comment" value="ok" />
                  <input type="hidden" name="id_potin" value="<?php echo $potin_courant['id_Potin']; ?>" />
                  <textarea class="form-control" name="nouv_comment" id="nouv_comment" rows="2" class="text-com"></textarea>
                  <button type="submit" class="btn btn-link">Commenter</button>
                </form>