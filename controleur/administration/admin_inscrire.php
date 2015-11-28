<?php
if(isset($_POST['ajout_user']) && ($_POST['ajout_user'] == 'ok'))
{
  // On ajoute l'utilisateur dans la table "users"
  $req = $bdd->prepare('INSERT INTO users(nom, prenom, mail, mdp) VALUES(?, ?, ?, ?)'); 
  $req->execute(array($_POST['nom'], $_POST['prenom'], $_POST['mail'], $_POST['mdp']));
  $id_nouveau_user = $bdd->lastInsertId(); // On récupère l'id du nouvel user

  // On cherche tous les groupes
  $req = $bdd->prepare('SELECT * FROM groupes');
  $req->execute();

  while($donnees = $req->fetch()) // On regarde si l'utilisateur doit appartenir au groupe courant
  {
    if(isset($_POST[$donnees['id_groupe']]) && ($_POST[$donnees['id_groupe']] == 'on'))
    {
      echo ' '.$donnees['id_groupe'].' OK ';
      $req2 = $bdd->prepare('INSERT INTO cor_user_groupe(id_user, id_groupe) VALUES(?, ?)'); 
      $req2->execute(array($id_nouveau_user, $donnees['id_groupe']));
      $req2->closeCursor();
    }   
  }
  $req->closeCursor();
}




// Ajout de points
/*
$req = $bdd->prepare('SELECT id_user FROM users');     
$req->execute();
while($donnees = $req->fetch())
{
  
  $req2 = $bdd->prepare('INSERT INTO `points`(`id_user`, `valeur`, `date`) VALUES (?, 10, NOW())');
  $req2->execute(array($donnees['id_user'], ));
  

  //echo $donnees['id_user'].'<br>';
}
$req->closeCursor();
*/
?>

<h2>Ajout d'un utilisateur</h2>

<form action="#" method="post" name="Ajouter_user">

            <input type="hidden" name="ajout_user" value="ok" /> <!-- Sert à savoir si ce formulaire a été traité -->

            <div class="row">
              <div class ="col-lg-8">

                <div class="input-group input-group-lg">
                  <input placeholder="Prenom" class="form-control" name="prenom" id="prenom">                  
                </div><!-- /input-group -->

                <div class="input-group input-group-lg">
                  <input placeholder="Nom" class="form-control" name="nom" id="nom">                  
                </div>

                <div class="input-group input-group-lg">
                  <input placeholder="Mail" class="form-control" name="mail" id="mail">                  
                </div>

                <div class="input-group input-group-lg">
                  <input placeholder="Mot de passe" class="form-control" name="mdp" id="mdp">                  
                </div>

                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn dropdown-toggle"  data-placeholder="Groupes">Groupes  <span class="caret"></span></button>
                      <ul class="dropdown-menu">

                        <?php
                          $req = $bdd->prepare('SELECT * FROM groupes'); 
                          $req->execute();

                          while($donnees = $req->fetch()) // On rentre tous les identifiants de groupe dans un tableau
                          {
                            echo '<li><input type="checkbox" name="'.htmlspecialchars($donnees['id_groupe']).'" id="'.htmlspecialchars($donnees['id_groupe']).'"><label for="'.htmlspecialchars($donnees['id_groupe']).'">'.htmlspecialchars($donnees['nom']).'</label></li>';
                          }

                          $req->closeCursor();
                        ?>

                      </ul>
                  </div>

                <button class="pull-right btn btn-primary" type="submit"><span class="fa fa-star"></span> Créer utilisateur</button>

              </div>
            </div>
          </form>
