<!-- Menu administration :
  - Liste de tous les potins *
  - Gestion des utilisateurs
  - Consultation des suggestions
  - Statistiques de trafic
-->

<nav>        
  <ul class="nav nav-pills nav-stacked">
    <li class="nav-header"><h4><small>ADMINISTRATION</small></h4></li>
    <li class="<?php
                  if(isset($nom_page) && ($nom_page == 'liste_potins')) echo 'active';
                  else echo 'nav-header';
                ?>"> <a href="administration.php"><span class="fa fa-arrow-right"></span> Liste des potins </a> </li>

     <li class="<?php
                  if(isset($nom_page) && ($nom_page == 'gestion_utilisateurs')) echo 'active';
                  else echo 'nav-header';
                ?>"> <a href="gestion_utilisateurs.php"><span class="fa fa-arrow-right"></span> Gestion des utilisateurs </a> </li>

     <li class="<?php
                  if(isset($nom_page) && ($nom_page == 'suggestions')) echo 'active';
                  else echo 'nav-header';
                ?>"> <a href="admin_suggestions.php"><span class="fa fa-arrow-right"></span> Suggestions / Remarques </a> </li>

      <li class="<?php
                  if(isset($nom_page) && ($nom_page == 'connexions')) echo 'active';
                  else echo 'nav-header';
                ?>"> <a href="connexion_users.php"><span class="fa fa-arrow-right"></span> Connexions </a> </li>
    
  </ul>

</nav>
