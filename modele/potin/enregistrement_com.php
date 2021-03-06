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
    nouvelle_notif($auteur_potin, 'comment_mypotin', $_POST['id_potin'], '', $bdd);
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
        nouvelle_notif($auteur_courant, 'reply_comment', $_POST['id_potin'], '', $bdd);
      }
    }
  }

  // On écrit le commentaire dans la bdd
  $req = $bdd->prepare('INSERT INTO commentaires(id_auteur, id_potin, date_com, texte) VALUES(?, ?, NOW(), ?)'); 
  $req->execute(array($id_user, $_POST['id_potin'], $_POST['nouv_comment']));
}
?>