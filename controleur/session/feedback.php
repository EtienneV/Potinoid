<?php
// Récupération des variables pour la page
$id_user = $_SESSION['membre_id']; // Recuperation id de l'utilisateur


// Si on reçoit des infos du formulaire
if(isset($_POST['post_retour']) && ($_POST['post_retour'] == 'ok') && isset($_POST['message']))
{
	$message = trim($_POST['message']); // On récupère le message

	// On écrit le message dan la table "probleme_suggestion"
    $req = $bdd->prepare('INSERT INTO probleme_suggestion(id_auteur, date, message) VALUES(?, NOW(), ?)'); 
    $req->execute(array($id_user, $message));
    $lastId = $bdd->lastInsertId(); // On récupère l'id du potin inséré

    $succes_message = 1;
}

// On informe l'utilisateur de la réussite de la publication du potin
if(isset($succes_message) && ($succes_message == 1))
{
  echo '<div class="row"><div class="alert alert-success col-md-12">';
  echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
  echo '<strong>Merci ! :)</strong>';
  echo '</div></div>';
}
?>


<h2>Un problème ? Une suggestion ?</h2>
<a href=<?php echo '"'.INDEX.'"'; ?>><span class="glyphicon glyphicon-circle-arrow-left"></span> Accueil</a>

</br>

<div class="col-lg-6">
	<form action="#" method="post" name="poster_message" class="form-horizontal ">
   		<div class="form-group">
     		<legend>Votre message :</legend>
   		</div>
    	<input type="hidden" name="post_retour" value="ok" />
    <div class="form-group">
        <textarea class="form-control" name="message" id="message" placeholder="Ecrivez votre message ici"></textarea>
    </div>
    
   		<div class="form-group">
			<button class="pull-right btn btn-primary" type="submit"><span class="fa fa-send"></span> Envoyer</button>
    	</div>
  	</form>
</div>
   		

