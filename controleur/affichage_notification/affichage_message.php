<?php
include_once('modele/notifications.php');
$message = infos_notif($_GET['id_notif'], $bdd);
notif_vue($_GET['id_notif'], $bdd);
?>

<h2>Nouveau message</h2>

<div class="bs-callout bs-callout-default">
	<p>
		<?php echo $message['description']; ?>
	</p>
</div>