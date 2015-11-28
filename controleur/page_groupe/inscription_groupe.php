<div class="page-header">
<h2>Inscription à <?php echo htmlspecialchars($groupe['nom']); ?></h2>
<div id="pac-charger">Chargement ... <img src="images/ajax-loader.gif"></img></div>
</div>

<?php
include_once('modele/appartient_au_groupe.php');
$est_dans_groupe = appartient_au_groupe($id_user, $groupe['id_groupe'], $bdd);

if(!$est_dans_groupe) // Si l'user n'est pas dans ce groupe, il peut s'y inscrire
{
	include_once('modele/groupes/inscription_groupe.php');

	// Si la candidatue n'a pas déjà été soumise
	if(!candidature_dans_groupe_existe($id_user, $groupe['id_groupe'], $bdd))
	{
		candidature_dans_groupe($id_user, $groupe['id_groupe'], $bdd); // On enregistre la demande de candidature

		include_once('modele/notifications.php');
		include_once('modele/infos_groupe.php');

		$admins_groupe = admin_groupe($groupe['id_groupe'], $bdd); // Recherche des admins du groupe

		foreach ($admins_groupe as $key => $admin_courant) {
			nouvelle_notif($admin_courant, 'new_insc_gpe', $groupe['id_groupe'], 0, $bdd);
		}
	}

	echo '<div class="alert alert-success" role="alert">';
	echo 'Votre inscription est en attente de validation par les administrateurs du groupe !';
	echo '</div>';
}
else
{
	echo '<div class="alert alert-danger" role="alert">';
	echo 'Vous êtes déjà inscrit à ce groupe !<br>';
	echo '<a href="'.INDEX.'?page=groupe&id_groupe='.$groupe['id_groupe'].'">'.$groupe['nom'].'</a>';
	echo '</div>';
}

?>