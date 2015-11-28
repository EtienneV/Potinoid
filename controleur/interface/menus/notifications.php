<?php
        include_once('modele/notifications.php');
        $notifs = rechercher_notifs($id_user, $bdd);
        ?>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-inbox"></span>
            <?php
            // Affichage du nombre de notifs
            if($notifs == 'erreur_nonotif') // Si il n'y a pas de nouvelles notifications
            {
              echo ' 0 ';
            }
            else
            {
              $nb_notifs = count($notifs);
              echo ' <span class="label label-danger">'.$nb_notifs.'</span> ';
            }
            ?>
            <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">

            <?php
            if($notifs == 'erreur_nonotif') // Si il n'y a pas de nouvelles notifs
            {
              echo '<li><a>Aucune notification</a></li>';
            }
            else
            {
              foreach ($notifs as $key => $value) {
                $notif_courante = infos_notif($value, $bdd);

                echo '<li>';

                if($notif_courante['type'] == 'nouv_potin') // Nouveau potin concernant l'user
                {
                  include_once('modele/infos_groupe.php');
                  $nom_gpe_notif = infos_groupe($notif_courante['ref_bis'], $bdd);

                  echo '<a href="'.INDEX.'?page=notif_new_potin&id_potin='.$notif_courante['ref'].'&id_notif='.$value.'">Un nouveau potin a été écrit sur vous dans '.$nom_gpe_notif['nom'].' !</a>';
                }
                else if($notif_courante['type'] == 'comment_mypotin') // Nouveau commentaire sur un potin écrit par l'user
                {
                  echo '<a href="'.INDEX.'?page=notif_new_comment&id_potin='.$notif_courante['ref'].'&id_notif='.$value.'">Un nouveau commentaire a été écrit sur l\'un de vos potins</a>';
                }
                else if($notif_courante['type'] == 'reply_comment') // Nouveau commentaire sur un potin commenté par l'user
                {
                  echo '<a href="'.INDEX.'?page=notif_new_comment&id_potin='.$notif_courante['ref'].'&id_notif='.$value.'">On a répondu à l\'un de vos commentaires</a>';
                }
                else if($notif_courante['type'] == 'message') // Si c'est un message passé en notification
                {
                  echo '<a href="'.INDEX.'?page=notif_message&id_notif='.$value.'">'.$notif_courante['description'].'</a>';
                }
                else if($notif_courante['type'] == 'new_insc_gpe') // Nouvelle postulation à un groupe
                {
                  echo '<a href="'.INDEX.'?page=notif_new_insc_gpe&id_groupe='.$notif_courante['ref'].'&id_notif='.$value.'">Nouvelle inscription à l\'un de vos groupes</a>';
                }
                else
                {
                  echo '<a>erreur de notification</a>';
                }

                echo '</li>';
              }
            }

            ?>

          </ul>
        </li>