<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">

      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <!--
      <a class="navbar-brand" href=<?php echo '"'.INDEX.'"'; ?>>
        <img alt="Potinoïd" src="images/icones/potinoid.png">
      </a>
      -->


      <a class="navbar-brand" href=<?php echo '"'.INDEX.'"'; ?>><img alt="Brand" src="images/logo_potinoid.png"><!--<h1>Potinoïd</h1>--></a>


    </div>

    <form class="navbar-form navbar-left" role="search">
      <div class="form-group">
        <input type="text" class="form-control" id="nav-recherche" placeholder="Rechercher">
      </div>
    </form>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">          
      <ul class="nav navbar-nav navbar-right">

        <!--
        <li>

          <button type="button" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#potinExpress">Potin express</button>
          
        </li>
        -->

        <li>
          <a href="<?php echo INDEX; ?>?page=page_perso">
          <?php
            echo htmlspecialchars($_SESSION['membre_prenom']) . ' ' . htmlspecialchars($_SESSION['membre_nom']); // On affiche le nom      
          ?>
          </a>
        </li>

        <!-- Notifications -->
        <?php include_once('controleur/interface/menus/notifications.php'); ?>


        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Options <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo INDEX; ?>?page=page_perso&onglet=parametres">Modifier mes infos personnelles</a></li>
            <li><a href="<?php echo INDEX; ?>?page=feedback">Problème / Suggestion</a></li>

            <?php 
              if($id_user == 1) // Si c'est l'admin
              {
                echo '<li class="divider"></li>';
                echo '<li><a href="'.INDEX.'?page=admin"><span class="fa fa-eye"></span>  Administration</a></li>';
              }
            ?>
            <li class="divider"></li>
            <li><a href="<?php echo 'hamham.php';?>">Infinite hamham :3</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo INDEX;?>?page=deconnexion"><span class="glyphicon glyphicon-off"></span>  Se déconnecter</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>