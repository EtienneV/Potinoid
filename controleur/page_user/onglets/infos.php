<?php
include_once('modele/infos_user.php');
include_once('modele/commentaires.php');

echo nb_potins_sur_user($user_concerne['id_user']).' potins sur '.$user_concerne['prenom'].'.<br>';

echo nb_com_de_user($user_concerne['id_user']).' commentaires postés';