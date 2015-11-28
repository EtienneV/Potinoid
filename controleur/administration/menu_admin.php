<nav>        
  <div class="panel panel-default" id="panel-menu">
    <div class="panel-body" id="panel2-menu">

      <ul class="nav nav-pills nav-stacked">
    
        <li class="nav-header"><h4><small> ADMINISTRATION</small></h4></li>
    
        <li class="<?php
                      if(isset($nom_page) && ($nom_page == 'admin')) echo 'active';
                      else echo 'nav-header';
                    ?>"> <a href=<?php echo '"'.INDEX.'?page=admin"'; ?>> <span class="fa fa-arrow-right"></span> Modération </a> </li>

        <li class="<?php
                      if(isset($nom_page) && ($nom_page == 'admin_liste_attente')) echo 'active';
                      else echo 'nav-header';
                    ?>"> <a href=<?php echo '"'.INDEX.'?page=admin_liste_attente"'; ?>> <span class="fa fa-arrow-right"></span> Liste d'attente </a> </li>
    
        <li class="<?php
                      if(isset($nom_page) && ($nom_page == 'admin_inscrire')) echo 'active';
                      else echo 'nav-header';
                    ?>"> <a href=<?php echo '"'.INDEX.'?page=admin_inscrire"'; ?>> <span class="fa fa-arrow-right"></span> Inscrire </a> </li>
    
        <li class="<?php
                      if(isset($nom_page) && ($nom_page == 'admin_suggestions')) echo 'active';
                      else echo 'nav-header';
                    ?>"> <a href=<?php echo '"'.INDEX.'?page=admin_suggestions"'; ?>> <span class="fa fa-arrow-right"></span> Suggestions </a> </li>  
    
        <li class="<?php
                      if(isset($nom_page) && ($nom_page == 'admin_connexions')) echo 'active';
                      else echo 'nav-header';
                    ?>"> <a href=<?php echo '"'.INDEX.'?page=admin_connexions"'; ?>> <span class="fa fa-arrow-right"></span> Connexions </a> </li>
            
      </ul>

    </div>
  </div>
</nav>
