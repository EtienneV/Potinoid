<!-- Onglets -->
<ul class="nav nav-tabs">
  <li role="presentation" 
  	<?php 
  	if($onglet == 'potins')
  	{
  		echo 'class="active"';
  	}
  	?>
  	><a href="<?php echo INDEX.'?page=page_membre&id_concerne='.$user_concerne['id_user'].'&onglet=potins'; ?>">Potins</a></li>

  <li role="presentation" 
  	<?php 
  	if($onglet == 'infos')
  	{
  		echo 'class="active"';
  	}
  	?>
  	><a href="<?php echo INDEX.'?page=page_membre&id_concerne='.$user_concerne['id_user'].'&onglet=infos'; ?>">Infos</a></li>
  
  <li role="presentation" 
    <?php 
    if($onglet == 'groupes')
    {
      echo 'class="active"';
    }
    ?>
    ><a href="<?php echo INDEX.'?page=page_membre&id_concerne='.$user_concerne['id_user'].'&onglet=groupes'; ?>">Groupes</a></li>
    
</ul>