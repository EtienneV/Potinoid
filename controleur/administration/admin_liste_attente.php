<h2>Liste d'attente</h2>

<?php

if(isset($_POST['accepter_inscription']) && ($_POST['accepter_inscription'] == 'ok'))
{
	if(isset($_POST['id_liste']) && $_POST['id_liste'] != '')
	{

			echo $_POST['id_liste'];

			$req = $bdd->prepare('SELECT *
                                    FROM liste_attente
                                    WHERE id_liste = ?');     
      
            $req->execute(array($_POST['id_liste']));
            $donnees = $req->fetch();

            echo $donnees['prenom'].' '.$donnees['nom'];

            $req->closeCursor();

            $req = $bdd->prepare('INSERT INTO users(prenom, nom, mail, mdp) VALUES(?, ?, ?, ?)'); 
	    	$req->execute(array($donnees['prenom'], $donnees['nom'], $donnees['mail'], $donnees['mdp']));
	    	$id_nouveau_user = $bdd->lastInsertId(); // On récupère l'id du nouvel user

            // On cherche tous les groupes
			$req2 = $bdd->prepare('SELECT * FROM groupes');
			$req2->execute();
			while($donnees2 = $req2->fetch()) // On regarde si l'utilisateur doit appartenir au groupe courant
			{
			  if(isset($_POST[$donnees2['id_groupe']]) && ($_POST[$donnees2['id_groupe']] == 'on'))
			  {
			    echo ' '.$donnees2['nom'].' OK ';

			    $req3 = $bdd->prepare('INSERT INTO cor_user_groupe(id_user, id_groupe) VALUES(?, ?)'); 
      			$req3->execute(array($id_nouveau_user, $donnees2['id_groupe']));
			    
			  }   
			}
			$req2->closeCursor();

			$req3 = $bdd->prepare('UPDATE liste_attente
									SET statut = "done"
									WHERE id_liste = ?'); 
      		$req3->execute(array($_POST['id_liste']));
	}
}

?>

<?php   
              $req = $bdd->prepare('SELECT *
                                    FROM liste_attente
                                    ORDER BY id_liste DESC');     
      
              $req->execute();
      
              echo '<div class="row">';
              echo '<div class="col-lg-7">'; // 12 
      
              while($donnees = $req->fetch()) // On rentre tous les identifiants de groupe dans un tableau
              {

                  echo '<div class="panel panel-primary">';
                  echo '<div class="panel-heading">';

                  echo '<h3 class="panel-title">';
                  echo htmlspecialchars($donnees['prenom']).' '.htmlspecialchars($donnees['nom']).'</h3>';
                  echo 'E-mail : '.htmlspecialchars($donnees['mail']);

                  echo '</div><div class="panel-body">';
                  echo '<strong>Groupes : </strong><br>'.htmlspecialchars($donnees['groupes']).'</br><strong>Message : </strong><br>'.htmlspecialchars($donnees['message']).'<br>';

                  // Bouton de suppression
                  
                  if($donnees['statut'] == 'new' || $donnees['statut'] == 'view')
                  {
					echo '<form action="#" method="post" name="accepter_inscription" class="form-horizontal ">';

                          $req2 = $bdd->prepare('SELECT * FROM groupes'); 
                          $req2->execute();

                          while($donnees2 = $req2->fetch()) // On rentre tous les identifiants de groupe dans un tableau
                          {
                            echo '<li><input type="checkbox" name="'.htmlspecialchars($donnees2['id_groupe']).'" id="'.htmlspecialchars($donnees2['id_groupe']).'"><label for="'.htmlspecialchars($donnees2['id_groupe']).'">'.htmlspecialchars($donnees2['nom']).'</label></li>';
                          }

                          $req2->closeCursor();
                        
                  	
                  	echo '<input type="hidden" name="accepter_inscription" value="ok" />';
                  	echo '<input type="hidden" name="id_liste" value="'.$donnees['id_liste'].'" />';
                  	echo '<button class="pull-right btn btn-primary" type="submit"><span class="glyphicon glyphicon-plus"></span> Inscrire</button>';
                  	echo '</form>';
              	  }
                  
                  echo '</div></div>';                
              }
      
              $req->closeCursor();
            ?>