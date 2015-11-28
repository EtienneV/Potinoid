<?php

if(isset($_GET['page_ajax']))
{
	if($_GET['page_ajax'] == 'accueil_scrolling_g')
	{
		include('controleur/ajax/scrolling/accueil_scrolling_g.php');
	}
	else if($_GET['page_ajax'] == 'accueil_scrolling_d')
	{
		include('controleur/ajax/scrolling/accueil_scrolling_d.php');
	}
	else if($_GET['page_ajax'] == 'accueil_scrolling')
	{
		include('controleur/ajax/scrolling/accueil_scrolling.php');
	}
	else if($_GET['page_ajax'] == 'groupe_scrolling')
	{
		include('controleur/ajax/scrolling/groupe_scrolling.php');
	}
	else if($_GET['page_ajax'] == 'perso_mespotins_scrolling')
	{
		include('controleur/ajax/scrolling/perso_mespotins_scrolling.php');
	}
	else if($_GET['page_ajax'] == 'perso_surmoi_scrolling')
	{
		include('controleur/ajax/scrolling/perso_surmoi_scrolling.php');
	}
	else if($_GET['page_ajax'] == 'accueil_scrolling_v4')
	{
		include('controleur/ajax/scrolling/accueil_scrolling_v4.php');
	}
	else if($_GET['page_ajax'] == 'potin_nouv_com')
	{
		include('controleur/ajax/potin/nouveau_commentaire.php');
	}
	else if($_GET['page_ajax'] == 'potin_nouv_com_v4')
	{
		include('controleur/ajax/potin/nouveau_commentaire_v4.php');
	}
	else if($_GET['page_ajax'] == 'potin_nouv_vote')
	{
		include('controleur/ajax/potin/nouveau_vote.php');
	}
	else if($_GET['page_ajax'] == 'potin_nouv_vote_v4')
	{
		include('controleur/ajax/potin/nouveau_vote_v4.php');
	}
	else if($_GET['page_ajax'] == 'maj_points')
	{
		include('controleur/ajax/maj_interface/maj_points.php');
	}
	else if($_GET['page_ajax'] == 'autoc_groupes')
	{
		include('controleur/ajax/redaction_potin/autoc_groupes.php');
	}
	else if($_GET['page_ajax'] == 'autoc_users')
	{
		include('controleur/ajax/redaction_potin/autoc_users.php');
	}
	else if($_GET['page_ajax'] == 'new_potin')
	{
		include('controleur/ajax/redaction_potin/new_potin.php');
	}
	else if($_GET['page_ajax'] == 'supprimer_potin')
	{
		include('controleur/ajax/potin/supprimer_potin.php');
	}
	else if($_GET['page_ajax'] == 'autoc_recherche')
	{
		include('controleur/ajax/recherche/autoc_recherche.php');
	}
	else if($_GET['page_ajax'] == 'new_potin_externe')
	{
		include('controleur/ajax/page_membre/new_potin_externe.php');
	}
	else if($_GET['page_ajax'] == 'desinscription')
	{
		include('controleur/ajax/espace_perso/desinscription.php');
	}
	else if($_GET['page_ajax'] == 'desinscription_gp')
	{
		include('controleur/ajax/espace_perso/desinscription_gp.php');
	}
}

?>