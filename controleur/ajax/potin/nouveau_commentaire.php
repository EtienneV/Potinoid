<?php
// Inscription du nouveau commentaire dans la bdd

include_once('controleur/includes/fonctions/parse_smiley.php');

// Si on reçoit des infos du formulaire
if(isset($_POST['comment']) && ($_POST['comment'] != ''))
{
	$commentaire = trim($_POST['comment']); // On récupère le message 

  // On cherche l'auteur du potin
  include_once('modele/infos_potin.php');
  $auteur_potin = auteur_du_potin($_POST['id_Potin'], $bdd);

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
  $auteurs_com = qui_a_commente_potin($_POST['id_Potin'], $bdd);

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
  $req->execute(array($id_user, $_POST['id_Potin'], $_POST['comment']));

}
?>


<div class="potin-d-test">
	<div class="potin-commentaires">
	
	  	<h4>Commentaires</h4>

	  	<?php
	  	include_once('modele/commentaires.php');
		$commentaires = rechercher_commentaires($_POST['id_Potin'], $bdd);

		if($commentaires != 'erreur_nocom') // Si on a des commentaires
        {
          	foreach ($commentaires as $clef => $valeur) {
            	$com_courant = infos_commentaire($valeur, $bdd);
            	echo '<p><span class="fa fa-comment-o" style="color:grey;" aria-hidden="true"></span> '.parse_smileys(str_replace("\n","<br/>", $com_courant['texte'])).'</p>';
          	}
        }
        else
        {
          	echo '<h5>Soyez le premier à commenter !</h5>';
        }
		?>
		
	</div>
	<div class="potin-form-com">
		<textarea class="potin-textarea-com" rows="1" idUser="<?php echo $_POST['auteur']; ?>" idPotin="<?php echo $_POST['id_Potin']; ?>" id="potin-com" placeholder="Commentez !"></textarea>
	
		<div class="potin-envoyer-com" idUser="<?php echo $_POST['auteur']; ?>" idPotin="<?php echo $_POST['id_Potin']; ?>"><span class="glyphicon glyphicon-send" aria-hidden="true"></span></div>
	</div>
</div>

