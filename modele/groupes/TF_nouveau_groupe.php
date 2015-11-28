<?php
if(isset($_POST['form_nouv_groupe']) && ($_POST['form_nouv_groupe'] == 'ok'))
{
	if(isset($_POST['nom_groupe']) && ($_POST['nom_groupe'] != ''))
	{
		if(isset($_POST['description_groupe']) && ($_POST['description_groupe'] != ''))
		{
			// On crée le groupe
			$req = $bdd->prepare('INSERT INTO groupes(nom, description) VALUES(?, ?)'); 
  			$req->execute(array($_POST['nom_groupe'], $_POST['description_groupe']));
  			$id_nouveau_groupe = $bdd->lastInsertId(); // On récupère l'id du nouveau groupe

  			// On met l'user en tant qu'admin du nouveau groupe
  			$req = $bdd->prepare('INSERT INTO cor_user_groupe(id_user, id_groupe, role) VALUES(?, ?, 2)'); 
  			$req->execute(array($id_user, $id_nouveau_groupe));
		}
	}
}
?>