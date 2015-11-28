<?php
include_once('modele/notifications.php');
include_once('modele/admin_groupe.php');

if(role_gpe($id_user, $_GET['id_groupe'], $bdd) == 2) // Si l'user est admin du groupe
{
	notif_vue($_GET['id_notif'], $bdd); // L'user a vu la notification
	
	echo '<h3>Candidatures</h3>';

    include_once('modele/groupes/inscription_groupe.php');
    $id_candidatures = id_candidatures_d_un_groupe($_GET['id_groupe'], $bdd); // On recherche les id de toutes les candidatures de ce groupe

    if($id_candidatures != 0) // Si on a des candidatures
    {
      foreach ($id_candidatures as $key => $candidature_courante) {
      	
        $candidature_courante = infos_candidature($candidature_courante, $bdd);

        echo $candidature_courante['prenom'].' '.$candidature_courante['nom'].', dans '.$candidature_courante['nom_groupe'];

        // Accepter
        echo '<a href="'.INDEX.'?page=groupe&onglet=admin_gpe&id_groupe='.$_GET['id_groupe'].'&inscription_user='.$candidature_courante['id_insc'].'">  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>  </a>';
        // Refuser
        echo '<a href="'.INDEX.'?page=groupe&onglet=admin_gpe&id_groupe='.$_GET['id_groupe'].'&supprimer_cand='.$candidature_courante['id_insc'].'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
        echo '<br><br>';
      }
    }
    else
    {
      echo 'Il n\'y a pas de candidatures';
    }
}

?>