<?php

/**
A faire : 
Convertir image en jpg
*/

include_once('controleur/includes/fonctions/make_clickable.php');

// Gestion de la photo
$LienImageNews = '';

// Si on reçoit un fichier
if(isset($_FILES["file"]["type"]))
{
    $validextensions = array("jpeg", "jpg", "png", "JPG"); // Extensions valides
    $temporary = explode(".", $_FILES["file"]["name"]); // Sépare le nom de l'extension
    $file_extension = end($temporary); // Sélectionne l'extension
    $file_type = $_FILES["file"]["type"];

    // si le fichier est de l'un des trois formats, que sa taille correspond et que son extension est valide
    if ((($file_type == "image/png") || ($file_type == "image/jpg") || ($file_type == "image/jpeg")) && 
        ($_FILES["file"]["size"] < 50000000) && in_array($file_extension, $validextensions)) 
    {
        if ($_FILES["file"]["error"] > 0) // gestion des erreurs de fichier
        {
            //echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
        }
        else
        {
            $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
            $targetPath = "images/potins/".time().'.'.$file_extension; // Target path where file is to be stored

            if (file_exists($targetPath)) { // Gestion de l'existence du fichier sur le serveur
                //echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
            }
            else
            {
                
                move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

                $LienImageNews = $targetPath;
            }
        }
    }
    else
    {
        //echo "<span id='invalid'>***Invalid file Size or Type***<span>";
    }
}
else
{
    //echo "erreur file type";
}


if(isset($_POST['id_groupe']) && isset($_POST['users_concernes']) && isset($_POST['potin']))
{
    $groupe = $_POST['id_groupe'];
    $concernes = json_decode($_POST['users_concernes']);
    $potin = $_POST['potin'];
    
    $potin = make_clickable($potin);
    
    // On écrit le potin dans la table "potins"
    $req = $bdd->prepare('INSERT INTO potins(potin, id_auteur, id_groupe, date_potin, image) VALUES(?, ?, ?, NOW(), ?)'); 
    $req->execute(array($potin, $id_user, $groupe, $LienImageNews));
    $id_nouveau_potin = $bdd->lastInsertId(); // On récupère l'id du potin inséré
    
    
    // On associe le potin à chaque user concerne
    foreach ($concernes as $key => $concerne_courant) {
    	$req = $bdd->prepare('INSERT INTO cor_potin_users(id_concerne, id_potin, decouvert) VALUES(?, ?, 0)'); 
        $req->execute(array($concerne_courant, $id_nouveau_potin));

        // Notifications
        include_once('modele/notifications.php');
        nouvelle_notif($concerne_courant, 'nouv_potin', $id_nouveau_potin, $groupe, $bdd);
    }
    
    
    
    
    // Renvoi de l'affichage du nouveau potin
    include_once('modele/rechercher_potins.php');
    $potin_courant = infos_potin($id_nouveau_potin, $bdd);
    
    //include_once('vue/potin/affichage_potin.php');
    include_once('vue/potin/potin_v4.php');
    //$retour['potin'] = vue_affichage_potin($potin_courant, $id_user, $bdd);
    $retour['potin'] = vue_potin_v4($potin_courant, $id_user, $bdd);
    $retour['id_potin'] = $id_nouveau_potin;
    
    echo $retour['potin'];
    //echo json_encode($retour);

}

?>