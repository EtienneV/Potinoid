<!-- Onglets -->
<ul class="nav nav-tabs">
  <li role="presentation" 
  	<?php 
  	if($onglet == 'potins')
  	{
  		echo 'class="active"';
  	}
  	?>
  	><a href="<?php echo INDEX; ?>?page=groupe&onglet=potins&id_groupe=<?php echo $groupe['id_groupe'] ?>">Potins</a></li>
  <li role="presentation" 
  	<?php 
  	if($onglet == 'membres')
  	{
  		echo 'class="active"';
  	}
  	?>
  	><a href="<?php echo INDEX; ?>?page=groupe&onglet=membres&id_groupe=<?php echo $groupe['id_groupe'] ?>">Membres</a></li>

    <li role="presentation" 
    <?php 
    if($onglet == 'parametres')
    {
      echo 'class="active"';
    }
    ?>
    ><a href="<?php echo INDEX; ?>?page=groupe&onglet=parametres&id_groupe=<?php echo $groupe['id_groupe'] ?>">Paramètres du groupe</a></li>
    
    <?php
    include_once('modele/admin_groupe.php');

    if(role_gpe($id_user, $groupe['id_groupe'], $bdd) == 2)
    {

      echo '<li role="presentation"';
      if($onglet == 'admin_gpe')
      {
        echo 'class="active"';
      }

      echo '><a href="'.INDEX.'?page=groupe&onglet=admin_gpe&id_groupe='.$groupe['id_groupe'].'">Administration</a></li>';
    }
    ?>
</ul>