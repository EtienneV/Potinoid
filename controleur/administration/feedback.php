<h2>Suggestions</h2>

<div class="row">
          <div class="col-lg-12"> <!-- 6 -->

            <?php   
              $req = $bdd->prepare('SELECT *
                                    FROM probleme_suggestion
                                    INNER JOIN users
                                      ON probleme_suggestion.id_auteur = users.id_user
                                    ORDER BY probleme_suggestion.date DESC');     
      
              $req->execute();
      
              echo '<div class="row">';
              echo '<div class="col-lg-7">'; // 12 
      
              while($donnees = $req->fetch()) // On rentre tous les identifiants de groupe dans un tableau
              {
                  echo '<div class="panel panel-primary">';
                  echo '<div class="panel-heading">';
                  echo '<h3 class="panel-title">';
                  echo 'Suggestion de : '.htmlspecialchars($donnees['prenom']).' '.htmlspecialchars($donnees['nom']).'</h3>';
                  echo '</div><div class="panel-body">';
                  echo htmlspecialchars($donnees['message']).'</br></br>';
                  echo '</div></div>';                
              }
      
              echo '</div></div>';
      
              $req->closeCursor();
            ?>