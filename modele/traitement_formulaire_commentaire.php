<?php
// Si on reçoit des infos du formulaire
if(isset($_POST['new_comment']) && ($_POST['new_comment'] == 'ok'))
{
	$commentaire = trim($_POST['nouv_comment']); // On récupère le message 

  // On cherche l'auteur du potin
  include_once('modele/infos_potin.php');
  $auteur_potin = auteur_du_potin($_POST['id_potin'], $bdd);

  if($auteur_potin != $id_user) // Si le commentaire n'est pas sur un potin de l'user
  {
    // Notification à l'auteur du potin
    include_once('modele/notifications.php');
    if(!notif_existe_deja($auteur_potin, 'comment_mypotin', $_POST['id_Potin'], 0, $bdd)) // Si la notif n'existe pas dejà
    {
      nouvelle_notif($auteur_potin, 'comment_mypotin', $_POST['id_Potin'], 0, $bdd);
    }
  }

  // On cherche tous les commentateurs du potin
  include_once('modele/commentaires.php');
  $auteurs_com = qui_a_commente_potin($_POST['id_potin'], $bdd);

  if($auteurs_com != 'erreur_nocom')
  {
    // Notification à tous ceux qui ont commenté, sauf l'auteur du potin
    foreach ($auteurs_com as $key => $auteur_courant) {
      if(($auteur_courant != $id_user) && ($auteur_courant) != $auteur_potin)
      {
        include_once('modele/notifications.php');
        if(!notif_existe_deja($auteur_courant, 'reply_comment', $_POST['id_Potin'], 0, $bdd)) // Si la notif n'existe pas dejà
        {
          nouvelle_notif($auteur_courant, 'reply_comment', $_POST['id_Potin'], 0, $bdd);
        }
      }
    }
  }

  // On écrit le commentaire dans la bdd
  $req = $bdd->prepare('INSERT INTO commentaires(id_auteur, id_potin, date_com, texte) VALUES(?, ?, NOW(), ?)'); 
  $req->execute(array($id_user, $_POST['id_potin'], $_POST['nouv_comment']));

  

  $succes_message = 1;
}

// On informe l'utilisateur de la réussite de la publication du potin
if(isset($succes_message) && ($succes_message == 1))
{
  echo '<div class="row"><div class="alert alert-success col-md-12">';
  echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>';
  echo '<strong>Merci ! :)</strong>';
  echo '</div></div>';
}
?>