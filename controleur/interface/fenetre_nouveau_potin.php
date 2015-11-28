<div class="modal fade" id="potinExpress" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
        <h4 class="modal-title">Poster un nouveau potin</h4>
      </div>
      
      <form action="#" method="post" name="Poster_potin" enctype="multipart/form-data">

        <input type="hidden" name="post_potin" value="ok" />

        <div class="modal-body">
          <div class="form-group">
  
            <div class="btn-group">
              <button data-toggle="dropdown" class="btn dropdown-toggle"  data-placeholder="Qui d'autre est concerné ?">Qui est concerné ? <span class="caret"></span></button>
              <ul class="dropdown-menu">
                <?php
                  $monid = 'id_nav'.strval($id_user);
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
                    $id_choix = 'id_nav'.strval($donnees['id_user']);
                    echo '<li><input type="checkbox" name="'.htmlspecialchars($id_choix).'" id="'.htmlspecialchars($id_choix).'"><label for="'.htmlspecialchars($id_choix).'">'.htmlspecialchars($donnees['prenom']).' '.htmlspecialchars($donnees['nom']).'</label></li>';
                  }
                  $req->closeCursor();
                ?>
              </ul>
            </div>
  
            <textarea class="form-control" name="potin" id="potin" placeholder="Ecrivez votre potin ici !"></textarea>
  
          </div>
          <div class="form-group">
  
            <label for="ImagePotin">Joindre une photo : </label>
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
            <input type="file" name="ImagePotin" id="ImagePotin" />
  
          </div>
        </div>
        <div class="modal-footer">
  
          <div class="form-group">
           <button class="pull-right btn btn-primary" type="submit"><span class="glyphicon glyphicon-leaf"></span> Potiner !</button>
          </div>
        </div>

      </form>

    </div>
  </div>
</div>