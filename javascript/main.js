$(function() {
	// Ici, le DOM est entièrement défini

	// Récupération de la variable GET
  	var $_GET = {};

  	// Parsing des variables GET
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
	    function decode(s) {
	        return decodeURIComponent(s.split("+").join(" "));
	    }
	
	    $_GET[decode(arguments[1])] = decode(arguments[2]);
	});

	// Si on vient de se connecter
	if((typeof $_GET['page'] != 'undefined') && ($_GET['page'] == 'connexion'))
	{
		new PNotify({
		    title: 'Bonjour ! :)',
		    text: 'Bon retour sur Potinoïd !',
		    type: 'success',
		    nonblock: {
		   	    nonblock: true,
		   	    nonblock_opacity: .2
		   	}
		});
	}


	// Requête AJAX pour maj du nb de points 
   	var requete_p = 'index.php?page=ajax&page_ajax=maj_points';
  	$.post(requete_p, // Le fichier cible côté serveur.
	    0,
	    function (data){
		    $('.points-user').replaceWith(data); // On met à jour l'affichage des commentaires
		},
	    'html' // Format des données reçues.
	);



	$('#nav-recherche').autocomplete({
	    source : function(requete, reponse){ // les deux arguments représentent les données nécessaires au plugin
		$.ajax({
	            url : 'index.php?page=ajax&page_ajax=autoc_recherche', // on appelle le script JSON
	            dataType : 'json', // on spécifie bien que le type de données est en JSON
	            data : {
	                term : $('#nav-recherche').val()
	            },
	            
	            success : function(donnee){
	                reponse($.map(donnee, function(objet){
	                    return { // on retourne cette forme de suggestion
	                    	id_user : objet.id_user, 
	                    	label : objet.prenom+' '+objet.nom,
	                    	value : objet.prenom+' '+objet.nom,
	                    	prenom : objet.prenom,
	                    	nom : objet.nom
	                    }
	                }));
	            }
	        });
	    },

	    minLength : 0,

	    select : function(event, ui){ // lors de la sélection d'une proposition

	    	window.location='index.php?page=page_membre&id_concerne='+ui.item.id_user;

	    	// On affiche le groupe sélectionné
	    	/*
	    	if(users_selectionnes.indexOf(ui.item.id_user) == -1) // Si l'user n'a pas déjà été sélectionné
	    	{
        		$('.users-selectionnes').append('<span class="item-user badge badge-success" iduser="' + ui.item.id_user + '">' + ui.item.prenom + ' ' + ui.item.nom + ' <span class="rm-user"> <span class="glyphicon glyphicon-remove"></span></span></span>'); 
        		users_selectionnes.push(ui.item.id_user);
        	}

        	$('#recherche-user').val('');
        	$('#recherche-user').attr('placeholder', 'Ajouter quelqu\'un');
			*/

        	event.preventDefault();
    	}
	});

	// Désinscription

	

	soundManager.setup({
					  url: '/',
					  flashVersion: 9, // optional: shiny features (default = 8)
					  // optional: ignore Flash where possible, use 100% HTML5 mode
					  // preferFlash: false,
					  onready: function() {
					    soundManager.url = 'librairies/soundmanager/soundmanager2.swf';
						soundManager.debugMode = false;
						soundManager.onload = function()
						{
							var s = soundManager.createSound('mySound','librairies/soundmanager/marche.mp3');

							$('.nav-whitepage').on('click', '.btn-desinscription', function(){
								$('.desinscription-wrapper').css('display', 'block');

								$('.chat-triste').delay( 1000 ).fadeIn("slow");
								$('.des-text').delay( 5000 ).fadeIn("slow");
								$('.chat-des-text').delay( 7000 ).fadeIn("slow");
								$('.suite-des-text').delay( 10000 ).fadeIn("slow");
								$('#des-mdp').delay( 15000 ).fadeIn("slow");
								$('.des-quit-groupes').delay( 16000 ).fadeIn("slow");
								$('.des-quit-tout').delay( 16000 ).fadeIn("slow");
								s.play();
							});

							$('.nav-whitepage').on('click', '.btn-desinscription-gp', function(){
								$('.desinscription-gp-wrapper').css('display', 'block');

								$('.chat-triste').delay( 1000 ).fadeIn("slow");
								$('.des-text').delay( 5000 ).fadeIn("slow");
								$('.chat-des-text').delay( 7000 ).fadeIn("slow");
								$('.suite-des-text').delay( 10000 ).fadeIn("slow");
								$('#des-mdp').delay( 15000 ).fadeIn("slow");
								$('.des-gp-quit-groupe').delay( 16000 ).fadeIn("slow");
								s.play();
							});


						}
					}
	});

	$('.desinscription-gp-wrapper').on('click', '.des-gp-quit-groupe', function(){

		var requete = 'index.php?page=ajax&page_ajax=desinscription_gp&id_groupe=' + $('.desinscription-gp-wrapper').attr('idGroupe') + '&mdp=' + $('#des-mdp').val();
  
	    $.get(requete, function(data){
	    	if(data == 'error_mdp')
	    	{
	    		new PNotify({
					    title: 'Oups ! :s',
					    text: 'Ce n\'est pas le bon mot de passe',
					    type: 'error',
					    nonblock: {
    					    nonblock: true,
    					    nonblock_opacity: .2
    					}
					});
	    	}
	    	else
	    	{
	      		window.location.reload();

	      	}
	    });
	});


	$('.desinscription-wrapper').on('click', '.des-quit-groupes', function(){

		var requete = 'index.php?page=ajax&page_ajax=desinscription&type=groupes&mdp=' + $('#des-mdp').val();
  
	    $.get(requete, function(data){
	    	if(data == 'error_mdp')
	    	{
	    		new PNotify({
					    title: 'Oups ! :s',
					    text: 'Ce n\'est pas le bon mot de passe',
					    type: 'error',
					    nonblock: {
    					    nonblock: true,
    					    nonblock_opacity: .2
    					}
					});
	    	}
	    	else
	    	{
	      		window.location.reload();

	      	}
	    });
	});

	$('.desinscription-wrapper').on('click', '.des-quit-tout', function(){

		var requete = 'index.php?page=ajax&page_ajax=desinscription&type=tout&mdp=' + $('#des-mdp').val();
  
	    $.get(requete, function(data){
	    	if(data == 'error_mdp')
	    	{
	    		new PNotify({
					    title: 'Oups ! :s',
					    text: 'Ce n\'est pas le bon mot de passe',
					    type: 'error',
					    nonblock: {
    					    nonblock: true,
    					    nonblock_opacity: .2
    					}
					});
	    	}
	    	else
	    	{
	      		window.location.reload();
	      	}
	    });
	});

});