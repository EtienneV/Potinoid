<?php include('vue/header.php'); //contient le doctype, et head. ?>

<body>
  
  <header>

    <?php include('controleur/interface/barre_navigation.php'); // Inclut la barre de navigation ?>
    <?php include('controleur/interface/fenetre_nouveau_potin.php');?>

  </header>

  <div class="container">
    <div class="col-lg-2">
      <?php include('controleur/administration/menu_admin.php'); // Inclut le menu des groupes à gauche ?>
    </div>

    <div class="col-lg-10">
      <section>
        <div class="row">

          <?php

          // On regarde quelle page a été demandée
          if($nom_page == 'admin') 
          {
            include('controleur/administration/moderation.php');
          }
          else if($nom_page == 'admin_liste_attente') 
          {
            include('controleur/administration/admin_liste_attente.php');
          }
          else if($nom_page == 'admin_inscrire') 
          {
            include('controleur/administration/admin_inscrire.php');
          }
          else if($nom_page == 'admin_suggestions')
          {
            include('controleur/administration/feedback.php');
          }
          else if($nom_page == 'admin_connexions') 
          {
            include('controleur/administration/connexions.php');
          }
          else // Si la page demandée n'existe pas
          {
            include('controleur/page_demarrage.php');
          }


          ?>

        </div>
      </section>
    </div>
  </div>
</body>

<?php
  include('vue/footer.php');
?>