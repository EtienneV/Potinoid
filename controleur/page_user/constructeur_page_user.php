<?php

// Ceci est la page d'un membre

// L'user doit être dans au moins un groupe du membre

// On cherche les groupes que l'user a en commun avec le membre
include_once('modele/appartient_au_groupe.php');
$groupes_communs = groupes_en_commun($id_user, $user_concerne['id_user'], $bdd);

/**
Si user n'a pas de liens, proposer de rejoindre un de ses groupes
gérer le cas où l'user va sur sa propre page
*/

?>


<!-- Header -->

<?php

if($user_concerne['avatar'] != '')
{
  $avatar = $user_concerne['avatar'];
}
else{
  $avatar = 'default';
}


?>

<div class="header-page-user-v2">

   <div class="hpu-fond-v2">

   		<?php
   		
	    ?>

      <img src="images/profile/<?php echo $avatar; ?>-bd.jpg">
   </div>

   <div class="hpu-user-v2">
		<div class="hpu-v2-roundedImage" style="background:url(images/profile/<?php echo $avatar; ?>-180.jpg) no-repeat 0px 0px;">&nbsp;</div>

		<div class="hpu-nom-v2">
			<h2><?php echo htmlspecialchars($user_concerne['prenom']).' '.htmlspecialchars($user_concerne['nom']); ?></h2>

			<?php
			include_once('modele/infos_user.php');
			$infos_user = infos_user($id_user, $bdd);
			echo '<em>'.$user_concerne['description'].'</em>';
			?>
        </div>
    </div> 

<!-- Onglets -->
<ul class="hpu-v2-onglets">
  <?php include('controleur/page_user/onglets_page_user.php'); ?>
</ul>

</div>



<!--
<div class="page-header page-user">
	<div class="row">
		<div class="col-lg-1">
		<?php 
			if($user_concerne['avatar'] != '')
			{
				echo '<img class="avatar" src="'.$user_concerne['avatar'].'" alt="Avatar" id="avatar_potin"/>';
				//echo '<div class="div-avatar" style="background:url('.$user_concerne['avatar'].') no-repeat 0px 0px;"></div>';
			}
			else{
				echo '<img src="images/profile/default.png" alt="Photo du potin" id="avatar_potin"/>';
			}
		?>
		</div>
		<div class="col-lg-11">
			<h2><?php echo htmlspecialchars($user_concerne['prenom']).' '.htmlspecialchars($user_concerne['nom']); ?></h2>
			<?php
			echo '<em>'.$user_concerne['description'].'</em>';
			?>
		</div>
	</div>
</div>
-->
<?php

if($groupes_communs != 'rien_en_commun')
{
	

	echo '<div class="well nav-whitepage">';

	if($onglet == 'potins')
	{
		include('controleur/page_user/onglets/potins.php');
	}
	else if($onglet == 'infos')
	{
		include('controleur/page_user/onglets/infos.php');
	}
	else if($onglet == 'groupes')
	{
		include('controleur/page_user/onglets/groupes.php');
	}

	echo '</div>';
}
else
{
	echo 'Aucun groupes en commun';
}

?>