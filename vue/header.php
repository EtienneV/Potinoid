<!DOCTYPE html>
<html>
	<head>

		<meta charset="utf-8" />
		<meta name="language" content="fr" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />

		<link href="librairies/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="librairies/bootstrap/css/dropdowns-enhancement.min.css" rel="stylesheet">
		<link href="css/bs-callout.css" rel="stylesheet"> 

		<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/smoothness/jquery-ui.css">
		
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<!--<link href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/simplex/bootstrap.min.css" rel="stylesheet">-->

		<link href="css/pnotify.custom.min.css" media="all" rel="stylesheet" type="text/css" />

		<link href="css/potin-style.css" rel="stylesheet">
		<link href="css/potin-v4.css" rel="stylesheet">
		<link href="css/header-user.css" rel="stylesheet">

		<link rel="icon" type="image/png" href="images/logo2.png" />

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
