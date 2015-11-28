<?php

/**
Et si l'utilisateur a dejà débloqué le potin ?
*/

include_once('modele/notifications.php');
include_once('modele/infos_potin.php');
include_once('modele/appartient_au_groupe.php');

$infos_notif = infos_notif($_GET['id_notif'], $bdd);

if($id_user == $infos_notif['id_user']) // On accède à la page que si la notif appartient à l'user
{
	notif_vue($_GET['id_notif'], $bdd); // L'user a vu la notification
	
	if(appartient_au_groupe($id_user, groupe_du_potin($_GET['id_potin'], $bdd), $bdd)) // Si l'utilisateur appartient au groupe du potin
	{		
		
		$potin_courant = infos_potin($_GET['id_potin'], $bdd);
		
		include('vue/afficher_potin_brouille.php');
	}
	else
	{
		echo 'Vous ne pouvez accéder à ce potin';
	}
}

?>