<?php
// Si on reçoit des infos du formulaire de changement de mail et qu'on a bien reçu un mail
if(isset($_POST['mail_change']) && ($_POST['mail_change'] == 'ok') && isset($_POST['nouveau_mail']))
{
  $nouveau_mail = trim($_POST['nouveau_mail']); // On récupère le nouveau mail, en enlevant les caractères indésirables
  $resultat_test_mail = checkmail($nouveau_mail, $bdd); // On teste le mail envoyé

  if($resultat_test_mail == 'isnt') $erreur_pas_un_mail = 1; // Si ce n'est pas un mail
  else if($resultat_test_mail == 'exists') $erreur_deja_utilise = 1; // Si le mail existe déjà
  else if($resultat_test_mail == 'empty') $erreur_pas_de_mail = 1; // Si il n'y a pas de mail
  else if($resultat_test_mail == 'ok') // Si tout est bon
  {
    // On enregistre le nouveau mail
    $req = $bdd->prepare('UPDATE users 
              SET mail=?
              WHERE id_user = ?');     
        $req->execute(array($nouveau_mail, $id_user));

        $mail_modifie = 1;
  } 
}

// Si on reçoit des infos du formulaire de changement de MOT DE PASSE et qu'on a bien reçu un mdp
if(isset($_POST['mdp_change']) && ($_POST['mdp_change'] == 'ok') && isset($_POST['nouveau_mdp']) && isset($_POST['verif_mdp']))
{
  $nouveau_mdp = trim($_POST['nouveau_mdp']); // Récupération nouveau mdp
  $verif_mdp = trim($_POST['verif_mdp']); // Récupération vérif mdp

  $resultat_test_mdp = checkmdpS($nouveau_mdp, $verif_mdp); // Vérification de la conformité du nouveau mdp

  if($resultat_test_mdp == 'different') $erreur_mdp_differents = 1;
  else if($resultat_test_mdp == 'empty') $erreur_mdp_vide = 1;
  else if($resultat_test_mdp == 'tooshort') $erreur_mdp_court = 1;
  else if($resultat_test_mdp == 'toolong') $erreur_mdp_long = 1;
  else if($resultat_test_mdp == 'ok')
  {
    $mdp_crypte = password_hash($nouveau_mdp, PASSWORD_DEFAULT);

    // On enregistre le nouveau mdp
    $req = $bdd->prepare('UPDATE users 
              SET mdp=?
              WHERE id_user = ?');     
        $req->execute(array($mdp_crypte, $id_user));

        $_SESSION['membre_mdp'] = $mdp_crypte; // On actualise le mdp dans la session, pour rester authentifié

        $mdp_modifie = 1;
  }
}


// Si on reçoit des infos du formulaire de changement de description
if(isset($_POST['desc_change']) && ($_POST['desc_change'] == 'ok') && isset($_POST['description']))
{
  $nouv_description = trim($_POST['description']); 

    // On enregistre la nouvelle description
    $req = $bdd->prepare('UPDATE users 
              SET description = ?
              WHERE id_user = ?');     
    $req->execute(array($nouv_description, $id_user));

}



//Si on a une image
if(isset($_POST['verif']) && ($_POST['verif'] == 'ok'))
  {
      $ListeExtension = array('jpg' => 'image/jpeg', 'jpeg'=>'image/jpeg');
      $ListeExtensionIE = array('jpg' => 'image/pjpeg', 'jpeg'=>'image/pjpeg');
      
      if (!empty($_FILES['changer_avatar']))
      {
          if($_FILES['changer_avatar']['size'] != 0) // Si il y a une image A REVOIR !!!!!!!!
          {
              if ($_FILES['changer_avatar']['error'] <= 0)
              {
                      if ($_FILES['changer_avatar']['size'] <= 2097152)
                      {
                          $ImageNews = $_FILES['changer_avatar']['name'];
                          $ExtensionPresumee = explode('.',$ImageNews);
                          $ExtensionPresumee = strtolower($ExtensionPresumee[count($ExtensionPresumee)-1]);
                          echo $ExtensionPresumee;
                          if ($ExtensionPresumee == 'jpg' || $ExtensionPresumee == 'jpeg')
                          {
                            $ImageNews2 = getimagesize($_FILES['changer_avatar']['tmp_name']);
                            if($ImageNews2['mime'] == $ListeExtension[$ExtensionPresumee]  || $ImageNews2['mime'] == $ListeExtensionIE[$ExtensionPresumee])
                            {
 
                                              $ImageChoisie = imagecreatefromjpeg($_FILES['changer_avatar']['tmp_name']);
                                              include('modele/image/modifications_image.php');
                                              //$NouvelleImage = make_profile_square($ImageChoisie, 50);
                                              $LienImageNews = make_tous_profiles($ImageChoisie);

                                               // On écrit le potin dans la table "potins"
                                              $req = $bdd->prepare('UPDATE users SET avatar = ? WHERE id_user = ?'); 
                                              $req->execute(array($LienImageNews, $id_user));
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

<h3>Modifier mes informations personnelles</h3>


<form action="#" method="post" name="Changer_mail">

              <input type="hidden" name="mail_change" value="ok" /> <!-- Sert à savoir si ce formulaire a été traité -->
  
              <div class="row">
                <div class ="col-lg-12">
                  <div class="input-group input-group-lg">
                    <span class="input-group-addon">
                      <?php
                      // On cherche l'adresse actuelle de l'utilisateur
                        $req = $bdd->prepare('SELECT * FROM users WHERE id_user = ?'); 
                        $req->execute(array($id_user));
                        $mail_user = $req->fetch();
                        $req->closeCursor();

                        echo htmlspecialchars($mail_user['mail']); // On affiche le mail actuel
                        ?>
                </span>
                  <input type="email" placeholder="Entrez votre nouvel email ici" class="form-control" name="nouveau_mail" id="nouveau_mail">

                  <span class="input-group-btn">
                      <button class="btn btn-default" type="submit">Changer d'email</button>
                  </span>
                </div><!-- /input-group -->

                <?php
                  // On gère les erreurs ou les succès
                  if(isset($erreur_pas_de_mail) && $erreur_pas_de_mail = 1) echo '<em>Vous n\'avez pas rentré de mail</em>';
                  if(isset($erreur_pas_un_mail) && $erreur_pas_un_mail = 1) echo '<em>Ce mail n\'est pas valide</em>';
                  if(isset($erreur_deja_utilise) && $erreur_deja_utilise = 1) echo '<em>Ce mail est déjà utilisé</em>';
                  if(isset($mail_modifie) && $mail_modifie = 1) echo '<em>Votre mail a bien été changé !</em>';
                  else echo '</br>';
                ?>

              </div>
            </div>
          </form>

          <br>

          <form action="#" method="post" name="Changer_mdp">

              <input type="hidden" name="mdp_change" value="ok" /> <!-- Sert à savoir si ce formulaire a été traité -->

            <div class="row">
              <div class ="col-lg-12">
                <div class="input-group input-group-lg">
                  <input type="password" placeholder="Nouveau mot de passe" class="form-control" name="nouveau_mdp" id="nouveau_mdp">
                  <!--<span class="input-group-addon"><img src="images/glyphicons_keys.png"/></span>-->
                  <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                  
                </div><!-- /input-group -->

                <div class="input-group input-group-lg">
                  <input type="password" placeholder="Confirmez le mot de passe" class="form-control" name="verif_mdp" id="verif_mdp">
                  <span class="input-group-btn">
                      <button class="btn btn-default" type="submit">Changer le mot de passe</button>
                  </span>
                </div><!-- /input-group -->

                <?php

                  // On gère les erreurs ou les succès
                  if(isset($erreur_mdp_differents) && $erreur_mdp_differents = 1) echo '<em>Les mots de passe sont différents</em>';
                  if(isset($erreur_mdp_vide) && $erreur_mdp_vide = 1) echo '<em>Vous n\'avez pas rentré de mot de passe</em>';
                  if(isset($erreur_mdp_court) && $erreur_mdp_court = 1) echo '<em>Le mot de passe est trop court</em>';
                  if(isset($erreur_mdp_long) && $erreur_mdp_long = 1) echo '<em>Le mot de passe est trop long</em>';
                  if(isset($mdp_modifie) && $mdp_modifie = 1) echo '<em>Mot de passe modifié avec succès !</em>';
                  else echo '</br>';
                ?>

              </div>
            </div>
          </form>

          <br>

          <div class="row">
            <div class ="col-lg-12">

              <form action="#" method="post" name="Changer_description">
    
                <input type="hidden" name="desc_change" value="ok" /> <!-- Sert à savoir si ce formulaire a été traité -->

                <div class="form-group">
                  <label for="description">Votre description</label>
                  <textarea  class="form-control" rows="3" id="description" name="description" placeholder="<?php

                  include_once('modele/infos_user.php');
                  $infos_user = infos_user($id_user, $bdd);

                  if($infos_user['description'] != '')
                  {
                    echo $infos_user['description'];
                  }
                  else
                  {
                    echo 'Ecrivez ici quelques mots sur vous';
                  }

                  ?>
                  "></textarea>
                </div> 

                <button type="submit" class="btn btn-primary pull-right">Changer la description</button>
    
              </form>
            </div>
          </div>

            <br>

<div class ="col-lg-12">
<div class="panel panel-default">
  <div class="panel-body">
            <form action="#" method="post" name="Changer_avatar" enctype="multipart/form-data">
              <div class="form-group">
                <label for="changer_avatar">Changer votre avatar : </label>

                <input type="hidden" name="verif" value="ok" />
                        <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                        <input type="file" name="changer_avatar" id="changer_avatar" />
                      </div>

                      <br>
                      <div class="form-group">
                        <button class="btn btn-primary" type="submit">Changer la photo</button>
                      </div>
            </form>
          </div></div></div>




<button class="btn btn-link btn-desinscription">Se désinscrire</button>

<div class="desinscription-wrapper">
  <img class="chat-triste" src="images/chat_triste.jpg">
  <div class="des-text">
    <p>Es-tu sûr de vouloir quitter Potinoïd ?<br>Tu n'auras plus accès aux contenus de tes groupes, ni à tes potins, ni aux potins de tes amis ...<br><br>
      <span class="chat-des-text">De plus, ce petit chat sera très triste :(</span><br><br>
    </p><span class="suite-des-text">Mais si ta décision est prise, ... ainsi soit-il :'(</span><br>
    <div class="form-group">
    <input type="password" class="form-control" id="des-mdp" placeholder="Mot de passe">
  </div>
    <button class="btn btn-danger des-quit-groupes">Quitter tous mes groupes</button>
    <button class="btn btn-link des-quit-tout">Désinscription</button>
    </div>
  
</div>