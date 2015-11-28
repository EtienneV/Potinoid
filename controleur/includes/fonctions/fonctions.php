<?php
/*
function connexionbdd()
{
	// On se connecte à la base de données
	try
	{
	  $bdd = new PDO('mysql:host=localhost;dbname=potins', 'root', 'roclesperrier-07');
	  $bdd->exec("SET CHARACTER SET utf8");
	  $bdd->exec('SET lc_time_names = "fr_FR"');  
	  return $bdd;
	}
	catch (Exception $e)
	{
	    die('Erreur : ' . $e->getMessage());
	}
}*/

function connexionbdd()
{
	// On se connecte à la base de données
	try
	{
	  $bdd = new PDO('mysql:host=db558181341.db.1and1.com;dbname=db558181341', 'dbo558181341', 'roclesperrier-07');
	  $bdd->exec("SET CHARACTER SET utf8");
	  $bdd->exec('SET lc_time_names = "fr_FR"');  
	  return $bdd;
	}
	catch (Exception $e)
	{
	    die('Erreur : ' . $e->getMessage());
	}
}

function actualiser_session($bdd)
{
	if(isset($_SESSION['membre_id']) && intval($_SESSION['membre_id']) != 0) //Vérification id
	{

		$req = $bdd->prepare('SELECT * FROM users WHERE id_user = ?'); 
        $req->execute(array($_SESSION['membre_id']));
        $retour = $req->fetch();
        $req->closeCursor();
		
		//Si la requête a un résultat (id est : si l'id existe dans la table membres)
		if(isset($retour['id_user']) && $retour['id_user'] != '')
		{
			if($_SESSION['membre_mdp'] != $retour['mdp'])
			{
				//Dehors vilain pas beau !
				$informations = Array(/*Mot de passe de session incorrect*/
									true,
									'Session invalide',
									'Le mot de passe de votre session est incorrect, vous devez vous reconnecter.',
									'',
									'indeeeeeex.php',
									3
									);
				require_once('information.php');
				vider_cookie();
				session_destroy();
				exit();
			}
			
			else
			{
				//Validation de la session.
					$_SESSION['membre_id'] = $retour['id_user'];
					$_SESSION['membre_mdp'] = $retour['mdp'];
					$_SESSION['membre_prenom'] = $retour['prenom'];
					$_SESSION['membre_nom'] = $retour['nom'];
					$_SESSION['membre_avatar'] = $retour['avatar'];
			}
		}
	}
	
	
}

function vider_cookie()
{
	
	foreach($_COOKIE as $cle => $element)
	{
		//setcookie($cle, '', time()-3600, '/', '', '', ''); // <--- Ligne qui a fait bugger tout l'univers !
		setcookie($cle, '', time()-3600, '/');
	}
}

function checkpseudo($pseudo)
{
	if($pseudo == '') return 'empty';
	else if(strlen($pseudo) < 3) return 'tooshort';
	else if(strlen($pseudo) > 32) return 'toolong';
	
	else
	{
		$result = sqlquery("SELECT COUNT(*) AS nbr FROM membres WHERE membre_pseudo = '".mysql_real_escape_string($pseudo)."'", 1);
		global $queries;
		$queries++;
		
		if($result['nbr'] > 0) return 'exists';
		else return 'ok';
	}
}

function checkmdp($mdp)
{
	if($mdp == '') return 'empty';
	else if(strlen($mdp) < 4) return 'tooshort'; // Au moins 4 caractères
	else if(strlen($mdp) > 50) return 'toolong'; // Au plus 50 caractères
	
	else
	{
		/*if(!preg_match('#[0-9]{1,}#', $mdp)) return 'nofigure';
		else if(!preg_match('#[A-Z]{1,}#', $mdp)) return 'noupcap';
		else return 'ok';*/
		return 'ok';
	}
}

function checkmdpS($mdp, $mdp2)
{
	if($mdp != $mdp2) return 'different';
	else return checkmdp($mdp);
}

function checkmail($email, $bdd)
{
	if($email == '') return 'empty'; // Si aucun mail n'est envoyé
	else if(!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#is', $email)) return 'isnt'; // Si ce n'est pas un mail
	
	else
	{
		// On cherche si l'adresse est déjà utilisée
	    $req = $bdd->prepare('SELECT COUNT(*) AS nb_appartitions FROM users WHERE mail = ?'); 
	    $req->execute(array($email));
	    $nb_apparitions = $req->fetch();
	    $req->closeCursor();

	    if($nb_apparitions['nb_appartitions'] > 0) return 'exists';

	    else return 'ok';
	}
}

function checkmailS($email, $email2)
{
	if($email != $email2 && $email != '' && $email2 != '') return 'different';
	else return 'ok';
}

function birthdate($date)
{
	if($date == '') return 'empty';

	else if(substr_count($date, '/') != 2) return 'format';
	else
	{
		$DATE = explode('/', $date);
		if(date('Y') - $DATE[2] <= 4) return 'tooyoung';
		else if(date('Y') - $DATE[2] >= 135) return 'tooold';
		
		else if($DATE[2]%4 == 0)
		{
			$maxdays = Array('31', '29', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');
			if($DATE[0] > $maxdays[$DATE[1]-1]) return 'invalid';
			else return 'ok';
		}
		
		else
		{
			$maxdays = Array('31', '28', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');
			if($DATE[0] > $maxdays[$DATE[1]-1]) return 'invalid';
			else return 'ok';
		}
	}
}

function vidersession()
{
	foreach($_SESSION as $cle => $element)
	{
		unset($_SESSION[$cle]);
	}
}
?>