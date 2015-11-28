<?php

// Ceci est la page d'affichage des potins

include_once('modele/appartient_au_groupe.php');
$user_dans_groupe = appartient_au_groupe($id_user, $groupe['id_groupe'], $bdd);
$concerne_dans_groupe = appartient_au_groupe($user_concerne['id_user'], $groupe['id_groupe'], $bdd);

// Si l'utilisateur ne fait pas partie du groupe OU qu'il est la personne concernée
// OU que le concerné n'est pas dans le groupe
if(($user_dans_groupe == false) || ($_SESSION['membre_id'] == $user_concerne['id_user']) || ($concerne_dans_groupe == false))
{ 
  $ulr_page_groupe = '/page_groupe.php?id_groupe='.$groupe['id_groupe'];
  header('Location: '.ROOTPATH.$ulr_page_groupe);
  exit();
}
/**
Fonction : securite_groupe
*/


?>

<?php

  // Si on reçoit des données du formulaire de potins
  if(isset($_POST['post_potin']) && ($_POST['post_potin'] == 'ok'))
  {
    if(isset($_POST['potin']) && ($_POST['potin'] != '')) //On regarde si on a bien reçu un potin
    {
      // On récupère le potin
      $potin_recu = $_POST['potin'];


      //Si on a une image
      $ListeExtension = array('jpg' => 'image/jpeg', 'jpeg'=>'image/jpeg');
      $ListeExtensionIE = array('jpg' => 'image/pjpeg', 'jpeg'=>'image/pjpeg');
      
      if (!empty($_FILES['ImagePotin']))
      {
          if($_FILES['ImagePotin']['size'] != 0) // Si il y a une image A REVOIR !!!!!!!!
          {
              if ($_FILES['ImagePotin']['error'] <= 0)
              {
                      if ($_FILES['ImagePotin']['size'] <= 2097152)
                      {
                          $ImageNews = $_FILES['ImagePotin']['name'];
                          $ExtensionPresumee = explode('.',$ImageNews);
                          $ExtensionPresumee = strtolower($ExtensionPresumee[count($ExtensionPresumee)-1]);
                          echo $ExtensionPresumee;
                          if ($ExtensionPresumee == 'jpg' || $ExtensionPresumee == 'jpeg')
                          {
                            $ImageNews2 = getimagesize($_FILES['ImagePotin']['tmp_name']);
                            if($ImageNews2['mime'] == $ListeExtension[$ExtensionPresumee]  || $ImageNews2['mime'] == $ListeExtensionIE[$ExtensionPresumee])
                            {
 
                                              $ImageChoisie = imagecreatefromjpeg($_FILES['ImagePotin']['tmp_name']);
                                              $TailleImageChoisie = getimagesize($_FILES['ImagePotin']['tmp_name']);
                                              $NouvelleLargeur = 400; //Largeur choisie à 400 px mais modifiable
 
                                              $NouvelleHauteur = ( ($TailleImageChoisie[1] * (($NouvelleLargeur)/$TailleImageChoisie[0])) );
 
                                              $NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur) or die ("Erreur");
 
                                              imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]);
                                              imagedestroy($ImageChoisie);
                                              //$NomImageChoisie = explode('.',$ImageNews2);
                                              $NomImageExploitable = time();
                                              
                                              imagejpeg($NouvelleImage , 'images/potins/'.$NomImageExploitable.'.'.$ExtensionPresumee, 100);
                                              $LienImageNews = 'images/potins/'.$NomImageExploitable.'.'.$ExtensionPresumee;

                                              echo $LienImageNews;
 
                                        }
                                        else
                                        {
                                                echo 'Le type MIME de l\'image n\'est pas bon';
                                        }
                                }
                                else
                                {
                                        echo 'L\'extension choisie pour l\'image est incorrecte';
                                }
                        }
                        else
                        {
                                echo 'L\'image est trop lourde';
                        }
                }
                else
                {
                        echo 'Erreur lors de l\'upload image';
                }
              }
              else
              {
                $LienImageNews = '';
                echo 'Pas d\'image';
              }
        }
        else
        {
                echo 'Au moins un des champs est vide';
        }
      

  
      // On écrit le potin dans la table "potins"
      $req = $bdd->prepare('INSERT INTO potins(potin, id_auteur, id_groupe, date_potin, image) VALUES(?, ?, ?, NOW(), ?)'); 
      $req->execute(array($potin_recu, $id_user, $groupe['id_groupe'], $LienImageNews));
      $id_nouveau_potin = $bdd->lastInsertId(); // On récupère l'id du potin inséré
      
      // On associe le potin à l'user de la page
      $req = $bdd->prepare('INSERT INTO cor_potin_users(id_concerne, id_potin, decouvert) VALUES(?, ?, 0)'); 
      $req->execute(array($user_concerne['id_user'], $id_nouveau_potin));

      // Notification
      include_once('modele/notifications.php');
      nouvelle_notif($user_concerne['id_user'], 'nouv_potin', $id_nouveau_potin, $groupe['id_groupe'], $bdd);

      // On cherche tous les users du groupe (sauf celui de la page)
      $req = $bdd->prepare('SELECT users.id_user AS id_user, users.prenom AS prenom, users.nom AS nom
                            FROM cor_user_groupe
                            INNER JOIN users
                              ON users.id_user = cor_user_groupe.id_user
                            WHERE cor_user_groupe.id_groupe = ? AND users.id_user != ?');
      $req->execute(array($groupe['id_groupe'], $user_concerne['id_user']));

      while($donnees = $req->fetch()) // On regarde si ils sont aussi concernés par le potin
      {
        $id_test = 'id'.strval($donnees['id_user']);

        if(isset($_POST[$id_test]) && ($_POST[$id_test] == 'on'))
        {
          $req2 = $bdd->prepare('INSERT INTO cor_potin_users(id_concerne, id_potin, decouvert) VALUES(?, ?, 0)'); 
          $req2->execute(array($donnees['id_user'], $id_nouveau_potin));

          // Notifications pour les users supplémentaires
          include_once('modele/notifications.php');
          nouvelle_notif($donnees['id_user'], 'nouv_potin', $id_nouveau_potin, $groupe['id_groupe'], $bdd);
        }   
      }
      $req->closeCursor();

      $succes_publication = 1;
    }
    else
    {
      echo 'Il faut écrire un potin !<br>' ;
    }
  }
?>

        





































          <div class="page-header page-user">
            <div class="row">
            <div class="col-lg-1">

            <?php 
            if($user_concerne['avatar'] != '')
            {
              echo '<img class="avatar" src="'.$user_concerne['avatar'].'" alt="Avatar" id="avatar_potin"/>';
              //echo '<div class="div-avatar" style="background:url('.$user_concerne['avatar'].') no-repeat 0px 0px;">&nbsp;</div>';
            }
            else{
              echo '<img src="images/profile/default.png" alt="Photo du potin" id="avatar_potin"/>';
            }
            ?>
          </div>
          <div class="col-lg-11">

            <h2><?php echo htmlspecialchars($user_concerne['prenom']).' '.htmlspecialchars($user_concerne['nom']).' dans '.htmlspecialchars($groupe['nom']); ?></h2>

            <?php

            echo '<em>'.$user_concerne['description'].'</em>';

            ?>

          </div>
          </div>
          </div>
      


          <?php
      // On informe l'utilisateur de la réussite de la publication du potin
      if(isset($succes_publication) && ($succes_publication == 1))
      {
        echo '<div class="row"><div class="alert alert-success col-md-12">';
        echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
        echo '<strong>Potin publié avec succès !</strong>';
        echo '</div></div>';
      }
    ?>
          
          <div class="row">
          <div class="col-lg-7"> <!-- 6 -->

            <?php  
            include_once('modele/rechercher_potins.php');
            $potins_cherches = rechercher_potins_d_un_user_dans_un_groupe($id_user, $groupe['id_groupe'], $user_concerne['id_user'], $bdd);
          
            if($potins_cherches != 0)
            {
              foreach ($potins_cherches as $i => $potin_courant) {
          
                $potin_courant = infos_potin($potin_courant, $bdd);
          
                include('vue/afficher_potin_anonyme.php');
              }
            }
          
            ?>
    </div>

        <div class="col-lg-5">
          <form action="#" method="post" name="Poster_potin" class="form-horizontal " enctype="multipart/form-data">
            <div class="form-group">
              <legend>Poster un nouveau potin sur <?php echo htmlspecialchars($user_concerne['prenom']); ?></legend>
            </div>
      
            <input type="hidden" name="post_potin" value="ok" />
      
            <!--<div class="row">-->
              <div class="form-group">
                <!--<div class="col-md-12">-->

                  <div class="btn-group">
                    <button data-toggle="dropdown" class="btn dropdown-toggle"  data-placeholder="Qui d'autre est concerné ?">Qui d'autre est concerné ?  <span class="caret"></span></button>
                      <ul class="dropdown-menu">

                        <?php
                          $monid = 'id'.strval($id_user);
                          echo '<li><input type="checkbox" name="'.htmlspecialchars($monid).'" id="'.htmlspecialchars($monid).'"><label for="'.htmlspecialchars($monid).'">Moi</label></li>';
                        ?>

                        <li class="divider"></li>

                        <?php
                          $req = $bdd->prepare('SELECT users.id_user AS id_user, users.prenom AS prenom, users.nom AS nom
                                                  FROM cor_user_groupe
                                                  INNER JOIN users
                                                    ON users.id_user = cor_user_groupe.id_user
                                                  WHERE cor_user_groupe.id_groupe = ? AND users.id_user != ? AND users.id_user != ?'); 
                          $req->execute(array($groupe['id_groupe'], $user_concerne['id_user'], $id_user));

                          while($donnees = $req->fetch()) // On rentre tous les identifiants de groupe dans un tableau
                          {
                            $id_choix = 'id'.strval($donnees['id_user']);

                            echo '<li><input type="checkbox" name="'.htmlspecialchars($id_choix).'" id="'.htmlspecialchars($id_choix).'"><label for="'.htmlspecialchars($id_choix).'">'.htmlspecialchars($donnees['prenom']).' '.htmlspecialchars($donnees['nom']).'</label></li>';
                          }

                          $req->closeCursor();
                        ?>

                      </ul>
                  </div>

                  <textarea class="form-control" name="potin" id="potin" placeholder="Ecrivez votre potin ici !"></textarea>

                  <!-- Test fichier -->
                  <label for="ImagePotin">Joindre une photo : </label>
                  <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                  <input type="file" name="ImagePotin" id="ImagePotin" />

                <!--</div>-->
              </div>
            
      
            <div class="form-group">
              <button class="pull-right btn btn-primary" type="submit"><span class="glyphicon glyphicon-leaf"></span> Potiner !</button>
            </div>
            <!--</div>-->
          </form>
        </div>






























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


















