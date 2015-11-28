<!-- Onglets -->
<ul class="nav nav-tabs">
  <li role="presentation" 
  	<?php 
  	if($onglet == 'mes_potins')
  	{
  		echo 'class="active"';
  	}
  	?>
  	><a href="<?php echo INDEX; ?>?page=page_perso&onglet=mes_potins">Ce que je raconte</a></li>
  <li role="presentation" 
  	<?php 
  	if($onglet == 'potins_sur_moi')
  	{
  		echo 'class="active"';
  	}
  	?>
  	><a href="<?php echo INDEX; ?>?page=page_perso&onglet=potins_sur_moi">Ce qu'on raconte sur moi</a></li>
  <li role="presentation" 
    <?php 
    if($onglet == 'recap_points')
    {
      echo 'class="active"';
    }
    ?>
    ><a href="<?php echo INDEX; ?>?page=page_perso&onglet=recap_points">Mes points</a></li>
  <li role="presentation" 
    <?php 
    if($onglet == 'parametres')
    {
      echo 'class="active"';
    }
    ?>
    ><a href="<?php echo INDEX; ?>?page=page_perso&onglet=parametres">Paramètres du compte</a></li>
</ul>