<?php include_once('vue/header.php'); //contient le doctype, et head. ?>

<body>
    
  <header>

    <?php include_once('controleur/includes/fonctions/calculs_points.php'); ?>

    <?php include_once('controleur/interface/barre_navigation.php'); // Inclut la barre de navigation ?>


    <!-- Traitement des formulaires -->
    <?php include_once('modele/traitement_formulaire_commentaire.php');?>
    <?php include_once('modele/traitement_formulaire_vote.php');?>
    <?php include_once('modele/traitement_formulaire_suppression_potin.php');?>
    <?php include_once('modele/traitement_formulaire_revelation_potin.php');?>

  </header>

  <div class="container">
    <div class="col-lg-2">
      <?php include_once('controleur/interface/menu_groupes.php'); // Inclut le menu des groupes à gauche ?>
    </div>    

    <section>
      <div class="col-lg-10">
        <div class="row">

        <?php

          // On regarde quelle page a été demandée
          if($nom_page == 'groupe') // Si on demande la page d'un groupe
          {
            //include('controleur/groupe.php');
            if(isset($_GET['onglet']) && ($_GET['onglet'] != ''))
            {
              if($_GET['onglet'] == 'potins')
              {
                $onglet = 'potins';
                include('controleur/page_groupe/constructeur_page_groupe.php');
              }
              else if($_GET['onglet'] == 'membres')
              {
                $onglet = 'membres';
                include('controleur/page_groupe/constructeur_page_groupe.php');
              }
              else if($_GET['onglet'] == 'parametres')
              {
                $onglet = 'parametres';
                include('controleur/page_groupe/constructeur_page_groupe.php');
              }
              else if($_GET['onglet'] == 'admin_gpe')
              {
                $onglet = 'admin_gpe';
                include('controleur/page_groupe/constructeur_page_groupe.php');
              }
            }
            else
            {
              $onglet = 'potins';
              include('controleur/page_groupe/constructeur_page_groupe.php');
            }
          }
          else if($nom_page == 'liste_groupes') 
          {
            include('controleur/page_groupe/liste_groupes.php');
          }
          else if($nom_page == 'creer_groupe') 
          {
            include('controleur/page_groupe/creer_groupe.php');
          }
          else if($nom_page == 'inscription_groupe') 
          {
            include('controleur/page_groupe/inscription_groupe.php');
          }
          
          else if($nom_page == 'potins_user') // Si on demande la page de potins d'un utilisateur
          {
            include('controleur/potins_user.php');
          }
          else if($nom_page == 'page_membre') // Si on demande la page de potins d'un utilisateur
          {
              if(isset($_GET['onglet']))
              {
                $onglet = $_GET['onglet'];
              }
              else
              {
                $onglet = 'potins';
              }

              include('controleur/page_user/constructeur_page_user.php');
          }

          // Notifications
          else if($nom_page == 'notif_new_potin')
          {
            include('controleur/affichage_notification/affichage_nouveau_potin.php');
          }
          else if($nom_page == 'notif_new_comment')
          {
            include('controleur/affichage_notification/affichage_potin_commentaires.php');
          }
          else if($nom_page == 'notif_message')
          {
            include('controleur/affichage_notification/affichage_message.php');
          }
          else if($nom_page == 'notif_new_insc_gpe')
          {
            include('controleur/affichage_notification/affichage_new_candidature_gpe.php');
          }

          else if($nom_page == 'new_contenu') // Si on demande la page d'un groupe
          {
            if(isset($_GET['type']) && ($_GET['type'] != ''))
            {
              if($_GET['type'] == 'potins')
              {
                include('controleur/contenu_non_vu/new_potins.php');
              }
              else if($_GET['type'] == 'comments')
              {
                include('controleur/contenu_non_vu/nex_comments.php');
              }
            }
            else
            {
             
            }
          }
          


          /*else if($nom_page == 'modif_infos_user') // Si on demande la page de modification des infos personnelles
          {
            include('controleur/session/modif_infos_user.php');
          }*/
          else if($nom_page == 'page_perso') // Si on demande le profil perso
          {
            if(isset($_GET['onglet']) && ($_GET['onglet'] != ''))
            {
              if($_GET['onglet'] == 'mes_potins')
              {
                $onglet = 'mes_potins';
                include('controleur/espace_perso/constructeur_page_perso.php');
              }
              else if($_GET['onglet'] == 'potins_sur_moi')
              {
                $onglet = 'potins_sur_moi';
                include('controleur/espace_perso/constructeur_page_perso.php');
              }
              else if($_GET['onglet'] == 'recap_points')
              {
                $onglet = 'recap_points';
                include('controleur/espace_perso/constructeur_page_perso.php');
              }
              else if($_GET['onglet'] == 'parametres')
              {
                $onglet = 'parametres';
                include('controleur/espace_perso/constructeur_page_perso.php');
              }
            }
            else
            {
              $onglet = 'mes_potins';
              include('controleur/espace_perso/constructeur_page_perso.php');
            }
            
          }
          else if($nom_page == 'feedback') // Si on demande la page de feedback 
          {
            include('controleur/session/feedback.php');
          }



          else // Si la page demandée n'existe pas
          {
            include('controleur/page_demarrage.php');
          }


        ?>

        </div>
      </div>
    </section>
  </div>
</body>

<?php
  include('vue/footer.php');
?>