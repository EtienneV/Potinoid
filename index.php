<?php
/*
	A FAIRE : un include d'initialisation des variables
*/
session_start();

//ob_start(); /* On initialise le tampon. */

header('Content-type: text/html; charset=utf-8');

// Includes
include_once('controleur/includes/config.php');
include_once('controleur/includes/fonctions.php');

$bdd = connexionbdd(); // On se connecte à la base de données
actualiser_session($bdd); // Et on vérifie la session 

if(isset($_SESSION['membre_id'])) // Si on est connecté
{
    $id_user = htmlspecialchars($_SESSION['membre_id']); // Recuperation id de l'utilisateur

	// On regarde quelle page a été demandée
    if(isset($_GET['page']) && ($_GET['page'] != ''))
    {
        if($_GET['page'] == 'groupe') // Si on demande la page d'un groupe
        {
            include_once('modele/infos_groupe.php');
        	$groupe = infos_groupe($_GET['id_groupe'], $bdd); // Recherche des infos sur le groupe

          	$nom_page = 'groupe';
			$titre = $groupe['nom'];

          	include('controleur/interface/interface.php');
        }
        else if($_GET['page'] == 'liste_groupes') // Si on demande la liste des groupes
        {
            $nom_page = 'liste_groupes';
            $titre = 'Groupes sur Potinoïd';

            include('controleur/interface/interface.php');
        }
        else if($_GET['page'] == 'creer_groupe') // Si on crée un groupe
        {
            $nom_page = 'creer_groupe';
            $titre = 'Nouveau groupe';

            include('controleur/interface/interface.php');
        }
        else if($_GET['page'] == 'inscription_groupe') // Si on veut s'inscrire à un groupe
        {
            include_once('modele/infos_groupe.php');
            $groupe = infos_groupe($_GET['id_groupe'], $bdd); // Recherche des infos sur le groupe

            $nom_page = 'inscription_groupe';
            $titre = 'Inscription à '.$groupe['nom'];

            include('controleur/interface/interface.php');
        }

        else if($_GET['page'] == 'potins_user')
        {
            include_once('modele/infos_groupe.php');
            $groupe = infos_groupe($_GET['id_groupe'], $bdd); // Recherche des infos sur le groupe
            
            include_once('modele/infos_user.php');
            $user_concerne = infos_user($_GET['id_concerne'], $bdd); // Recuperation des infos sur l'utilisateur concerné

            $nom_page = 'potins_user';
            $titre = htmlspecialchars($user_concerne['prenom']).' '.htmlspecialchars($user_concerne['nom']);

            include('controleur/interface/interface.php');
        }
        else if($_GET['page'] == 'page_membre')
        {            
            include_once('modele/infos_user.php');
            $user_concerne = infos_user($_GET['id_concerne'], $bdd); // Recuperation des infos sur l'utilisateur concerné

            $nom_page = 'page_membre';
            $titre = htmlspecialchars($user_concerne['prenom']).' '.htmlspecialchars($user_concerne['nom']);

            include('controleur/interface/interface.php');
        }

        
        else if($_GET['page'] == 'page_perso')
        {
            $nom_page = 'page_perso';
            $titre = 'Mon profil';

            include('controleur/interface/interface.php');
        }
        else if($_GET['page'] == 'potins_sur_moi')
        {
            $nom_page = 'potins_sur_moi';
            $titre = 'Mon profil';

            include('controleur/interface/interface.php');
        }


        // Notifications
        else if($_GET['page'] == 'notif_new_potin')
        {
            $nom_page = 'notif_new_potin';
            $titre = 'Nouveau potin';

            include('controleur/interface/interface.php');
        }
        else if($_GET['page'] == 'notif_new_comment')
        {
            $nom_page = 'notif_new_comment';
            $titre = 'Nouveau commentaire';

            include('controleur/interface/interface.php');
        }
        else if($_GET['page'] == 'notif_message')
        {
            $nom_page = 'notif_message';
            $titre = 'Nouveau message';

            include('controleur/interface/interface.php');
        }
        else if($_GET['page'] == 'notif_new_insc_gpe')
        {
            $nom_page = 'notif_new_insc_gpe';
            $titre = 'Nouvelle candidature';

            include('controleur/interface/interface.php');
        }

        // Nouveau contenu
        else if($_GET['page'] == 'new_contenu')
        {
            $nom_page = 'new_contenu';
            $titre = 'Nouveauté';

            include('controleur/interface/interface.php');
        }

        // Pages gestion espace perso
        else if($_GET['page'] == 'modif_infos_user')
        {
            $nom_page = 'modif_infos_user';
            $titre = 'Modifier mes infos personnelles';

            include('controleur/interface/interface.php');
        }
        else if($_GET['page'] == 'feedback')
        {
            $nom_page = 'feedback';
            $titre = 'Page de feedback';

            include('controleur/interface/interface.php');
        }
        else if($_GET['page'] == 'deconnexion')
        {
        	include('controleur/session/deconnexion.php');
        	include(INDEX);
        }

        // Pages administration
        else if($_GET['page'] == 'admin')
        {
            $nom_page = 'admin';
            $titre = 'Administration';

            include('controleur/administration/interface_admin.php');
        }
        else if($_GET['page'] == 'admin_liste_attente')
        {
            $nom_page = 'admin_liste_attente';
            $titre = 'Administration';

            include('controleur/administration/interface_admin.php');
        }
        else if($_GET['page'] == 'admin_inscrire')
        {
            $nom_page = 'admin_inscrire';
            $titre = 'Administration';

            include('controleur/administration/interface_admin.php');
        }
        else if($_GET['page'] == 'admin_suggestions')
        {
            $nom_page = 'admin_suggestions';
            $titre = 'Administration';

            include('controleur/administration/interface_admin.php');
        }
        else if($_GET['page'] == 'admin_connexions')
        {
            $nom_page = 'admin_connexions';
            $titre = 'Administration';

            include('controleur/administration/interface_admin.php');
        }

        // Tests
        else if($_GET['page'] == 'test')
        {
            $nom_page = 'test';
            $titre = 'Administration';

            include('mail_relance.php');
        }

        // AJAX
        else if($_GET['page'] == 'ajax')
        {
            include('controleur/ajax/index_ajax.php');
        }
        
        else // Si la page demandée n'existe pas
        {
          	$nom_page = 'demarrage';
			$titre = htmlspecialchars($_SESSION['membre_prenom']).' '.htmlspecialchars($_SESSION['membre_nom']);

			include('controleur/interface/interface.php');
        }
    }
    else // Si on ne demande pas de page particulière
    {
        $nom_page = 'demarrage';
		$titre = htmlspecialchars($_SESSION['membre_prenom']).' '.htmlspecialchars($_SESSION['membre_nom']);

		include('controleur/interface/interface.php');
    }	
}
else // Si on n'est pas connecté
{
    if(isset($_GET['page']) && ($_GET['page'] != ''))
    {
        if($_GET['page'] == 'connexion') // Si on tente de se connecter
        {
            include('controleur/session/connexion.php');
        }
        else if($_GET['page'] == 'oubli_mdp') // Si on tente de se connecter
        {
            include('controleur/ajax/mdp/mdp_oublie.php');
        }
        else if($_GET['page'] == 'groupe') // Si on demande la page d'un groupe
        {
            include_once('modele/infos_groupe.php');
            $groupe = infos_groupe($_GET['id_groupe'], $bdd); // Recherche des infos sur le groupe

            $nom_page = 'groupe';
            $titre = $groupe['nom'];

            include('controleur/page_groupe/page_publique.php');
        }
        else
        {
            include('controleur/page_connexion.php'); // Page de connexion
        }
    }
    else
    {
    	include('controleur/page_connexion.php'); // Page de connexion
    }
}



//ob_end_flush(); /* On vide le tampon et on retourne le contenu au client. */

?>