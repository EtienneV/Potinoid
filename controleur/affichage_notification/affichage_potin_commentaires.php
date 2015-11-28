<?php
include_once('modele/notifications.php');
include_once('modele/infos_potin.php');
include_once('modele/appartient_au_groupe.php');
include_once('vue/potin/affichage_potin.php');

$infos_notif = infos_notif($_GET['id_notif'], $bdd);

if($id_user == $infos_notif['id_user']) // On accède à la page que si la notif appartient à l'user
{
	notif_vue($_GET['id_notif'], $bdd); // L'user a vu la notification
	
	if(appartient_au_groupe($id_user, groupe_du_potin($_GET['id_potin'], $bdd), $bdd)) // Si l'utilisateur appartient au groupe du potin
	{		
		
		$potin_courant = infos_potin($_GET['id_potin'], $bdd);
		
		//include('vue/afficher_potin_anonyme.php');
    echo '<div class="wrapper-potins">';
    echo vue_affichage_potin($potin_courant, $id_user, $bdd);
    echo '</div>';
	}
	else
	{
		echo 'Vous ne pouvez accéder à ce potin';
	}
}

?>

<!-- Fenêtre de vote -->
  <div class="modal fade" id="voteModal" tabindex="-1" role="dialog" aria-labelledby="voteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
          <h4 class="modal-title" id="voteModalLabel">Donnez votre avis</h4>

        </div>
        <div class="modal-body contenu-centre">

          <div id="inserer_vote">Chargement</div>
          

        </div>
      </div>
    </div>
  </div>

<!-- Fenêtre de commentaires -->
  <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
            <h4 class="modal-title" id="myModalLabel">Commentaires</h4>
            </div>
            <div class="modal-body">
                
              <div id="inserer_comment">Chargement</div>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>