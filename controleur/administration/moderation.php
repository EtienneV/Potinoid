<?php
if(isset($_POST['suppr_potin']) && ($_POST['suppr_potin'] == 'ok'))
{
  $req = $bdd->prepare('SELECT potin FROM potins WHERE id_potin = ?'); 
  $req->execute(array($_POST['numero_potin']));
  $donnees = $req->fetch();

  $req2 = $bdd->prepare('DELETE FROM potins WHERE id_potin = ?'); 
  $req2->execute(array($_POST['numero_potin']));
  $req2->closeCursor();

  $req2 = $bdd->prepare('DELETE FROM cor_potin_users WHERE id_potin = ?'); 
  $req2->execute(array($_POST['numero_potin']));
  $req2->closeCursor();

  $req->closeCursor();
}
?>

<em>356376931</em>

<h2>Potins publiés</h2>

<?php   
              $req = $bdd->prepare('SELECT id_potin
                                    FROM potins
                                    ORDER BY date_potin DESC');     
      
              $req->execute(array($id_user));
      
              echo '<div class="row">';
              echo '<div class="col-lg-7">'; // 12 
      
              while($donnees = $req->fetch()) // On rentre tous les identifiants de groupe dans un tableau
              {

                  // Cherche les infos sur le potin
                  $req2 = $bdd->prepare('SELECT info_potin.Potin AS Potin,
                                                groupes.nom AS nom_groupe,
                                                 info_potin.id_Potin AS id_Potin,
                                                 info_potin.prenom_auteur AS prenom_auteur,
                                                 info_potin.nom_auteur AS nom_auteur,
                                                 info_potin.Image AS Image,
                                                  info_potin.Date AS Date,
                                                 nom_jour_potin,
                                                 jour_potin,
                                                 mois_potin,
                                                 annee_potin, 
                                                 GROUP_CONCAT(users.prenom SEPARATOR ", ") AS concernes
                                          FROM(
                                          SELECT potins.potin AS Potin,
                                                 potins.id_potin AS id_Potin,
                                                 potins.id_groupe AS id_Groupe,
                                                 potins.image AS Image,
                                                 users.prenom AS prenom_auteur,
                                                 users.nom AS nom_auteur,
                                                   potins.date_potin AS Date,
                                                 DAYNAME(potins.date_potin) AS nom_jour_potin,
                                                 DAY(potins.date_potin) AS jour_potin,
                                                 MONTHNAME(potins.date_potin) AS mois_potin,
                                                 YEAR(potins.date_potin) AS annee_potin
                                                 FROM potins 
                                                 INNER JOIN users
                                                   ON users.id_user = potins.id_auteur
                                                 WHERE id_potin = ?) AS info_potin
                                          INNER JOIN cor_potin_users
                                            ON cor_potin_users.id_potin = info_potin.id_Potin
                                          INNER JOIN users
                                            ON users.id_user = cor_potin_users.id_concerne
                                          INNER JOIN groupes
                                            ON groupes.id_groupe = info_potin.id_Groupe'); 

                  $req2->execute(array($donnees['id_potin']));
                  $donnees2 = $req2->fetch();

                  echo '<div class="panel panel-primary">';
                  echo '<div class="panel-heading">';
                  echo '<h3 class="panel-title">';
                  echo htmlspecialchars($donnees2['prenom_auteur']).' '.htmlspecialchars($donnees2['nom_auteur']).' a écrit, le '.htmlspecialchars($donnees2['nom_jour_potin']).' '.htmlspecialchars($donnees2['jour_potin']).' '.htmlspecialchars($donnees2['mois_potin']).' '.htmlspecialchars($donnees2['annee_potin']).'</h3>';
                  echo 'Sur '.htmlspecialchars($donnees2['concernes']).' dans '.htmlspecialchars($donnees2['nom_groupe']);
                  echo '</div><div class="panel-body">';
                  echo htmlspecialchars($donnees2['Potin']).'</br></br>';

                  if($donnees2['Image'] != '')
                  {
                    echo '<img class="imagePotin" src="'.$donnees2['Image'].'" alt="Photo du potin"/>';
                  }

                  // Bouton de suppression
                  echo '<form action="#" method="post" name="supprimer_potin" class="form-horizontal ">';
                  echo '<input type="hidden" name="suppr_potin" value="ok" />';
                  echo '<input type="hidden" name="numero_potin" value="'.$donnees['id_potin'].'" />';
                  echo '<button class="pull-right btn btn-primary" type="submit"><span class="fa fa-trash"></span> Supprimer</button>';
                  echo '</form>';
                  
                  echo '</div></div>';

                  $req2->closeCursor();
                
              }
      
              $req->closeCursor();
            ?>