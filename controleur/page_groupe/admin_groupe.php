<?php

// Si on demande une inscription
if(isset($_GET['inscription_user']) && $_GET['inscription_user'] != '')
{
  // On est l'admin du groupe et l'user n'est pas déjà dans le groupe, et qu'il a fait une demande
  include_once('modele/groupes/inscription_groupe.php');
  include_once('modele/admin_groupe.php');
  include_once('modele/appartient_au_groupe.php');

  $candidature = infos_candidature($_GET['inscription_user'], $bdd);

  if(role_gpe($id_user, $groupe['id_groupe'], $bdd) == 2) // Si on est l'admin du groupe
  {
    if(candidature_dans_groupe_existe($candidature['id_user'], $groupe['id_groupe'], $bdd)) // Si la personne a fait une demande d'inscription au groupe
    {
      if(!appartient_au_groupe($candidature['id_user'], $groupe['id_groupe'], $bdd)) // Si la personne n'appartient pas au groupe
      {
        inscrire_user($candidature['id_user'], $groupe['id_groupe'], $bdd);
        supprimer_candidature($candidature['id_insc'], $bdd);

      }
    }
  }
}

// Si on supprime une inscription
if(isset($_GET['supprimer_cand']) && $_GET['supprimer_cand'] != '')
{
  include_once('modele/groupes/inscription_groupe.php');

  supprimer_candidature($_GET['supprimer_cand'], $bdd);
}

// Si on change les infos du groupe
if(isset($_POST['infos_change']) && $_POST['infos_change'] == 'ok')
{
  include_once('modele/infos_groupe.php');
  include_once('modele/admin_groupe.php');

  // Il faut être admin dans ce groupe, et que le nom ne soit pas nul (sinon on ne le change pas)

  if(role_gpe($id_user, $groupe['id_groupe'], $bdd) == 2) // Si on est l'admin du groupe
  {
    if(isset($_POST['nom']) && ($_POST['nom'] != ''))
    {
      changer_nom_groupe($groupe['id_groupe'], $_POST['nom'], $bdd);
    }
    if(isset($_POST['description']) && ($_POST['description'] != ''))
    {
      changer_description_groupe($groupe['id_groupe'], $_POST['description'], $bdd);
    }
  }
}

//Si on a une image
if(isset($_POST['verif']) && ($_POST['verif'] == 'ok'))
  {
      $ListeExtension = array('jpg' => 'image/jpeg', 'jpeg'=>'image/jpeg');
      $ListeExtensionIE = array('jpg' => 'image/pjpeg', 'jpeg'=>'image/pjpeg');
      
      if (!empty($_FILES['changer_avatar_groupe']))
      {
          if($_FILES['changer_avatar_groupe']['size'] != 0) // Si il y a une image A REVOIR !!!!!!!!
          {
              if ($_FILES['changer_avatar_groupe']['error'] <= 0)
              {
                      if ($_FILES['changer_avatar_groupe']['size'] <= 2097152)
                      {
                          $ImageNews = $_FILES['changer_avatar_groupe']['name'];
                          $ExtensionPresumee = explode('.',$ImageNews);
                          $ExtensionPresumee = strtolower($ExtensionPresumee[count($ExtensionPresumee)-1]);
                          echo $ExtensionPresumee;
                          if ($ExtensionPresumee == 'jpg' || $ExtensionPresumee == 'jpeg')
                          {
                            $ImageNews2 = getimagesize($_FILES['changer_avatar_groupe']['tmp_name']);
                            if($ImageNews2['mime'] == $ListeExtension[$ExtensionPresumee]  || $ImageNews2['mime'] == $ListeExtensionIE[$ExtensionPresumee])
                            {
 
                                              $ImageChoisie = imagecreatefromjpeg($_FILES['changer_avatar_groupe']['tmp_name']);

                                              include('modele/image/modifications_image.php');
                                              $LienImageNews = make_tous_gp_profiles($ImageChoisie);

                                              
                                              $req = $bdd->prepare('UPDATE groupes SET image = ? WHERE id_groupe = ?'); 
                                              $req->execute(array($LienImageNews, $groupe['id_groupe']));
                                              $req->closeCursor();
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
    }

?>

<div class="col col-xs-12">
  <div class="row">

    <?php

    include_once('modele/admin_groupe.php');

    if(role_gpe($id_user, $groupe['id_groupe'], $bdd) == 2) // Si on est l'admin du groupe
    {      
      echo '<h3>Candidatures</h3>';

      include_once('modele/groupes/inscription_groupe.php');

      $id_candidatures = id_candidatures_d_un_groupe($groupe['id_groupe'], $bdd); // On recherche les id de toutes les candidatures de ce groupe

      if($id_candidatures != 0) // Si on a des candidatures
      {
        foreach ($id_candidatures as $key => $candidature_courante) {
          $candidature_courante = infos_candidature($candidature_courante, $bdd);
  
          echo $candidature_courante['prenom'].' '.$candidature_courante['nom'];
          echo '<a href="'.INDEX.'?page=groupe&onglet=admin_gpe&id_groupe='.$groupe['id_groupe'].'&inscription_user='.$candidature_courante['id_insc'].'">  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>  </a>';
          echo '<a href="'.INDEX.'?page=groupe&onglet=admin_gpe&id_groupe='.$groupe['id_groupe'].'&supprimer_cand='.$candidature_courante['id_insc'].'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
          echo '<br><br>';
        }
      }
      else
      {
        echo 'Il n\'y a pas de candidatures';
      }


      echo '<h3>Modifier les infos du groupe</h3>';

?>

      <form action="#" method="post" name="Changer_infos">

        <input type="hidden" name="infos_change" value="ok" /> <!-- Sert à savoir si ce formulaire a été traité -->

        <div class="form-group">

          <label for="nom">Nom du groupe</label><br>
          <input type="text" name="nom" id="nom" value="<?php echo $groupe['nom']; ?>"><br><br>

          <label for="description">Description du groupe</label>
          <textarea  class="form-control" rows="2" id="description" name="description"><?php echo $groupe['description'] ?></textarea>

        </div> 
        <button type="submit" class="btn btn-primary pull-right">Changer les infos du groupe</button>
      </form>

      <br>

      <form action="#" method="post" name="Changer_avatar_groupe" enctype="multipart/form-data">
        <div class="form-group">
          <label for="changer_avatar">Changer la photo du groupe : </label>
          <input type="hidden" name="verif" value="ok" />
          <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
          <input type="file" name="changer_avatar_groupe" id="changer_avatar_groupe" />
        </div>

        <br>

        <div class="form-group">
          <button class="btn btn-primary" type="submit">Changer la photo</button>
        </div>
      </form>

<?php      

    }
    else
    {
      echo 'Vous n\'avez pas accès à cette page';
    }

    ?>

 </div>
</div>