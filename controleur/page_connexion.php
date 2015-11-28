<?php
$titre = '';
?>

<?php // Formulaire de traitement de l'inscription à la liste d'attente

if(isset($_POST['form_liste_attente']) && ($_POST['form_liste_attente'] == 'ok'))
{
	if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mail']) && isset($_POST['mdp']) && isset($_POST['groupes']) && isset($_POST['message']))
	{
		if($_POST['nom'] != '' && $_POST['prenom'] != '' && $_POST['mail'] != '' && $_POST['mdp'] != '' && $_POST['groupes'] != '')
		{
			/*if($_POST['message'] == '') // Si le message n'a pas été rempli
			{
				$_POST['message'] == ' ';
			}*/

			$mdp_crypte = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

			// On ajoute tout ça dans la liste d'attente
	    	$req = $bdd->prepare('INSERT INTO liste_attente(prenom, nom, mail, mdp, groupes, message, statut) VALUES(?, ?, ?, ?, ?, ?, "new")'); 
	    	$req->execute(array($_POST['prenom'], $_POST['nom'], $_POST['mail'], $mdp_crypte, $_POST['groupes'], $_POST['message']));
	
	    	$liste_attente_ok = true;
    	}
    	else
    	{
    		$liste_attente_chmp_vide = true;
    	}
	}
}

?>

<!DOCTYPE html>
<html>
	<head>

		<meta charset="utf-8" />
		<meta name="language" content="fr" />

		<link rel="icon" type="image/png" href="images/logo2.png" />

		<link href="librairies/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="librairies/bootstrap/css/dropdowns-enhancement.min.css" rel="stylesheet">
		
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<!--<link href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/simplex/bootstrap.min.css" rel="stylesheet">-->

		<link href="css/pnotify.custom.min.css" media="all" rel="stylesheet" type="text/css" />

		<link href="css/connexion.css" rel="stylesheet">

		<?php
		/**********Vérification du titre...*************/
		
		if(isset($titre) && trim($titre) != '')
		$titre = TITRESITE.' - '.$titre;
		
		else
		$titre = TITRESITE;
		
		/***********Fin vérification titre...************/
		?>
		<title><?php echo $titre; ?></title>

	</head>

	<body>

		<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=177634445647963&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


		<div class="container">
			<div class="row">
				<div class="jumbotron-connexion">
					<h1>Les secrets les mieux gardés sont sur<br><b>Potinoïd.fr</b></h1>
				</div>
			</div>
		<div class="row">

			<div class="col-xs-6">
				<img src="images/gossip.png">
			</div>

			<div class="col-xs-6">
		
			<form action="<?php echo INDEX; ?>?page=connexion" method="post" name="Connexion" class="form-horizontal col-lg-12">
				<div class="form-group">
					<legend>Connexion au flux de potins</legend>
				</div>
		
				<input type="hidden" name="val_connexion" id="val_connexion" value="ok" />
		
				
			    	<div class="form-group">
			      		<label for="mail">Adresse mail : </label>
			    		
		 	       			<input type="email" class="form-control" name="mail" id="mail"
		 	       			<?php
								if(isset($_POST['mail']))
								{
									echo ' value="'.htmlspecialchars($_POST['mail']).'" ';
								}
							?>>
							<?php
								if(isset($erreur_pas_mail) && ($erreur_pas_mail == 1))
								{
									echo '<em>Vous n\'avez pas renseigné de mail</em>';
								}
								else if(isset($erreur_mail_existe) && ($erreur_mail_existe == 1))
								{
									echo '<em>Ce mail n\'existe pas</em>';
								}
							?>
		 	     		
		 	   		</div>
			  	
			    	<div class="form-group">
			      		<label for="mdp">Mot de passe : </label>
			    		
		 	       			<input type="password" placeholder="Surtout, ne vous trompez pas !" class="form-control" name="mdp" id="mdp">
		 	       			<?php
								if(isset($erreur_pas_mdp) && ($erreur_pas_mdp == 1))
								{
									echo '<em>Vous n\'avez pas renseigné de mot de passe</em>';
								}
								else if(isset($erreur_mauvais_mdp) && ($erreur_mauvais_mdp == 1))
								{
									echo '<em>Mauvais mot de passe !</em>';
								}
							?>
		 	     		
		 	   		</div>

		 	   		<a id="lien_oubli_mdp" href="#">J'ai oublié mon mot de passe :-(</a>
		 	   		<div class="oubli_mdp_w">
		 	   			Envoyer un nouveau mot de passe à cette adresse :
		 	   			<input type="email" class="form-control" name="mail_nv_mdp" id="mail_nv_mdp">
		 	   			<br>
		 	   			<a class="btn btn-primary" id="btn_nv_mdp">OK</a>
		 	   		</div>

			  	
		
			  	<div class="form-group">
					<input type="checkbox" name="cookie" id="cookie"/> 
					<label for="cookie"> Je veux rester connecté </label>
				</div>
		
				<div class="form-group">
					<button class="pull-right btn btn-block btn-primary" type="submit"><span class="glyphicon glyphicon-eye-open"></span> Potiner !</button>
					<br>
					<button type="button" class="pull-right btn btn-block btn-primary btn-potato" data-toggle="modal" data-target="#potatoModal">Je ne suis pas encore inscrit</button>
				</div>
		
			</form>

			
		</div>

		<!--<div class="annonce-campagne"><a href="campagne.php"><img src="campagne/vs_tr.png"></a></div>-->

		<div class="col-xs-6 col-xs-offset-3">

		<?php if(isset($liste_attente_ok) && $liste_attente_ok == true) { ?>

		<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
			<strong>Bravo !</strong> Vous avez été inscrit sur la liste d'attente !
		</div>

		<?php 
		}
		else if(isset($liste_attente_chmp_vide) && $liste_attente_chmp_vide == true) { ?>

		<div class="alert alert-danger" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
			Au moins un des champs requis est vide :(
		</div>

		<?php
		}
		?>

		</div>

		</div>

	</div>



<!-- Fenêtre d'inscription -->
  <div class="modal fade" id="potatoModal" tabindex="-1" role="dialog" aria-labelledby="voteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
          <h4 class="modal-title" id="voteModalLabel">Inscription à la liste d'attente</h4>

        </div>
        <div class="modal-body contenu-centre">

          	<form action="#" method="post" name="Inscription_LA">
			  
          	  <input type="hidden" name="form_liste_attente" value="ok">

			  <div class="form-group">
			    <label for="prenom">Prénom *</label>
			    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Les pseudos ne sont pas acceptés">
			  </div>

			  <div class="form-group">
			    <label for="nom">Nom *</label>
			    <input type="text" class="form-control" id="nom" name="nom">
			  </div>

			  <div class="form-group">
			    <label for="mail">Mail *</label>
			    <input type="email" class="form-control" id="mail" name="mail">
			  </div>

			  <div class="form-group">
			    <label for="mdp">Mot de passe *</label>
			    <input type="password" class="form-control" id="mdp" name="mdp">
			  </div>

			  <div class="form-group">
			    <label for="groupes">Groupes *</label>
			    <textarea class="form-control" rows="3" id="groupes" name="groupes" placeholder="Décrivez ici les groupes dont vous voulez faire partie (votre famille, école, groupe d'amis ...)"></textarea>
			  </div>

			  <div class="form-group">
			    <label for="message">Message</label>
			    <textarea class="form-control" rows="3" id="message" name="message" placeholder="Ecrivez ici ce que vous voulez"></textarea>
			  </div>

			  <div class="checkbox">
			    <label>
			      <input type="checkbox"> J'ai bien compris que je n'ai aucune condition générale d'utilisation à approuver.
			    </label>
			  </div>

			  <small>
			  	L'inscription à la bêta-test de Potinoïd.fr est soumise à modération. <br>
			  	Vous receverez un e-mail à l'adresse indiquée lorsque votre inscription sera effective.
			  </small>

		</div>
		<div class="modal-footer">

				<!-- <img src="images/Potato_gif.gif" id="potato"></img> -->

				<button type="submit" class="btn btn-primary pull-right">Soumettre</button>

			</form>
          
        </div>
      </div>
    </div>
  </div>

	</body>

	<footer>
		<div class="container">
			<div class="col-xs-6">
				<p>(C) 2014 - Site dactylographié par ChipsOndulée</p>
			</div>

			<div class="col-xs-6 fb-style">
				<div class="fb-like pull-right" data-href="https://www.facebook.com/Potinoid" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
			</div>
		</div>
	</footer>

	<script src="librairies/bootstrap/js/jquery.js"></script> <!-- A ajouter à bootstrap ! -->
	<script src="librairies/bootstrap/js/bootstrap.js "></script>
	<script src="librairies/bootstrap/js/dropdowns-enhancement.js "></script>

	<script type="text/javascript" src="javascript/pnotify.custom.min.js"></script>

	<script src="javascript/oubli_mdp.js"></script>

</html>

