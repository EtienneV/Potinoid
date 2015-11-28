<h2>Connexions</h2>

<div class="row">

  <div class="col-lg-7"> <!-- 6 -->

    <p>
    Nombre de visiteurs uniques BDE : <?php 
                                        include_once('campagne/modele/visites.php');
                                        echo nb_visiteurs_uniques($bdd); ?>
    </p>
    <p>
    Nombre d'affichages BDE : <?php echo nb_affichages($bdd); ?>
    </p>

    <ul class="list-group">
    <?php   
      $req = $bdd->prepare('SELECT *
                            FROM connexions
                            INNER JOIN users
                              ON users.id_user = connexions.id_user
                            ORDER BY connexions.date DESC'); 
      $req->execute();
      while($donnees = $req->fetch())
      {
        if($donnees['id_user'] != 1) {
          echo '<li class="list-group-item">';
          echo htmlspecialchars($donnees['prenom']).' '.htmlspecialchars($donnees['nom']).' connecté le '.htmlspecialchars($donnees['date']);
          echo '</li>';               
        } 
      }
      $req->closeCursor();
    ?>
    </ul>
  </div>
</div>