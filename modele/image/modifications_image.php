<?php

function make_tous_profiles($source)
{
	$sq180 = make_profile_square($source, 180);
	$sq100 = make_profile_square($source, 100);
	$sq50 = make_profile_square($source, 50);
	$bd = make_bandeau_blur($source);

	$nomImage = time();
	
	// Engregistrement des images                                              
	imagejpeg($sq180 , 'images/profile/'.$nomImage.'-180.jpg', 100);
	imagejpeg($sq100 , 'images/profile/'.$nomImage.'-100.jpg', 100);
	imagejpeg($sq50 , 'images/profile/'.$nomImage.'-50.jpg', 100);
	imagejpeg($bd , 'images/profile/'.$nomImage.'-bd.jpg', 100);
	// Image d'origine
	imagejpeg($source , 'images/profile/'.$nomImage.'-o.jpg', 100);

	return $nomImage;
}

function make_tous_gp_profiles($source)
{
	$sq180 = make_profile_square($source, 180);
	$sq100 = make_profile_square($source, 100);
	$sq50 = make_profile_square($source, 50);
	$bd = make_bandeau_blur($source);

	$nomImage = time();
	
	// Engregistrement des images                                              
	imagejpeg($sq180 , 'images/groupe/'.$nomImage.'-180.jpg', 100);
	imagejpeg($sq100 , 'images/groupe/'.$nomImage.'-100.jpg', 100);
	imagejpeg($sq50 , 'images/groupe/'.$nomImage.'-50.jpg', 100);
	imagejpeg($bd , 'images/groupe/'.$nomImage.'-bd.jpg', 100);
	// Image d'origine
	imagejpeg($source , 'images/groupe/'.$nomImage.'-o.jpg', 100);

	return $nomImage;
}

function make_profile_square($source, $taille)
{
	$taille_profile = $taille;

	$destination = imagecreatetruecolor($taille_profile, $taille_profile); // On crée la miniature vide

	// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
	$largeur_source = imagesx($source);
	$hauteur_source = imagesy($source);

	$largeur_destination = $taille_profile;
	$hauteur_destination = $taille_profile;

	if($hauteur_source > $largeur_source) // Si elle est en portrait
	{
	  // Taille de la portion à prendre dans l'image
	  $src_w = $largeur_source;
	  $src_h = $largeur_source;
	  
	  // Coordonnées de la portion à prendre
	  $src_x = 0;
	  $src_y = ($hauteur_source/2)-($src_h/2);
	}
	else if($hauteur_source < $largeur_source) // Si elle est en paysage
	{
	  // Taille de la portion à prendre dans l'image
	  $src_w = $hauteur_source;
	  $src_h = $hauteur_source;
	  
	  // Coordonnées de la portion à prendre
	  $src_x = ($largeur_source/2)-($src_w/2);
	  $src_y = 0;
	}
	else // Si elle est carrée
	{
	  // Taille de la portion à prendre dans l'image
	  $src_w = $largeur_source;
	  $src_h = $hauteur_source;
	  
	  // Coordonnées de la portion à prendre
	  $src_x = 0;
	  $src_y = 0;
	}

	// On crée la miniature
	imagecopyresampled($destination, $source, 0, 0, $src_x, $src_y, $largeur_destination, $hauteur_destination, $src_w, $src_h);

	return $destination;
}

function make_bandeau_blur($source)
{
	$largeur_destination = 950;
	$hauteur_destination = 240;

	$destination = imagecreatetruecolor($largeur_destination, $hauteur_destination); // On crée la miniature vide

	// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
	$largeur_source = imagesx($source);
	$hauteur_source = imagesy($source);

	// Taille de la portion à prendre dans l'image
	$src_w = $largeur_source;
	$src_h = ($hauteur_destination / $largeur_destination) * $largeur_source;

	// Coordonnées de la portion à prendre
	$src_x = 0;
	$src_y = ($hauteur_source/2)-($src_h/2);

	// On crée la miniature
	imagecopyresampled($destination, $source, 0, 0, $src_x, $src_y, $largeur_destination, $hauteur_destination, $src_w, $src_h);

	$j = 0;

	// For normal use the third argument should be the sum of all the values in the matrix.
	for ($i=0; $i < 50; $i++) { 
		imagefilter($destination, IMG_FILTER_SMOOTH, -4);
	  	imagefilter($destination, IMG_FILTER_GAUSSIAN_BLUR);
	  	imagefilter($destination, IMG_FILTER_BRIGHTNESS, $j);
	  	$j = !$j;
	}

	imagefilter($destination, IMG_FILTER_CONTRAST, -5);
	imagefilter($destination, IMG_FILTER_BRIGHTNESS, -20);

	return $destination;
}