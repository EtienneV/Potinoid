$(function() {
  	// Ici, le DOM est entièrement défini

  	// Style de notifications
	//PNotify.prototype.options.styling = "bootstrap2";

  	var idUser = $('#recherche-groupe').attr('idUser'); // On récupère l'id de l'user
  	var groupe_courant = 'none';
  	var users_selectionnes = [];

  	var retour_potin; // Les infos du potin de retour

  	// Récupération de la variable GET
  	var $_GET = {};
  	var membre_concerne = -1;

  	// Parsing des variables GET
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
	    function decode(s) {
	        return decodeURIComponent(s.split("+").join(" "));
	    }
	
	    $_GET[decode(arguments[1])] = decode(arguments[2]);
	});

	// Si on est sur une page de groupe
	if((typeof $_GET['page'] != 'undefined') && ($_GET['page'] == 'groupe'))
	{
		if((typeof $_GET['id_groupe'] != 'undefined'))
		{
			$('.groupe-selectionne').html('<span class="item-groupe badge badge-primary">' + $('.header-page-user-v2 h2').html() + ' <span class="rm-groupe"> <span class="glyphicon glyphicon-remove"></span></span></span>'); 

    		groupe_courant = $_GET['id_groupe']; // on récupère le groupe sélectionné

    		$('.form-selection-groupe').css('display', 'none');
    		$('.selection-concernes').css('display', 'inline');
    		$('#recherche-groupe').val('');
 			$('#recherche-user').focus();
		}
	}

	// Si on est sur une page de groupe
	if((typeof $_GET['page'] != 'undefined') && ($_GET['page'] == 'page_membre'))
	{
		if((typeof $_GET['id_concerne'] != 'undefined'))
		{
			membre_concerne = $_GET['id_concerne'];
			users_selectionnes.push(membre_concerne); // On ajoute le membre courant aux sélectionnés
		}
	}


  	// Quand on clique sur le faux formulaire, on affiche le vrai
  	$('.wrapper-nouveau-potin').on('click', '#fake-write-potin', function(){
  		$('#fake-write-potin').hide();
  		$('.wrapper-write-potin').show();
  		$('#recherche-groupe').focus();
  	});

  	// Autocompletion choix du groupe
	$('#recherche-groupe').autocomplete({
	    source : function(requete, reponse){ // les deux arguments représentent les données nécessaires au plugin
		$.ajax({
	            url : 'index.php?page=ajax&page_ajax=autoc_groupes', // on appelle le script JSON
	            dataType : 'json', // on spécifie bien que le type de données est en JSON
	            data : {
	                term : $('#recherche-groupe').val(), // on donne la chaîne de caractère tapée dans le champ de recherche
	            	id_user : idUser,
	            	id_concerne : membre_concerne
	            },
	            
	            success : function(donnee){
	                reponse($.map(donnee, function(objet){
	                    return { // on retourne cette forme de suggestion
	                    	id_groupe : objet.id_groupe, 
	                    	label : objet.nom_groupe,
	                    	value : objet.nom_groupe
	                    }

	                }));
	            }
	        });
	    },

	    minLength : 0,

	    select : function(event, ui){ // lors de la sélection d'une proposition
	    	// On affiche le groupe sélectionné
        	$('.groupe-selectionne').html('<span class="item-groupe badge badge-primary">' + ui.item.label + ' <span class="rm-groupe"> <span class="glyphicon glyphicon-remove"></span></span></span>'); 

    		groupe_courant = ui.item.id_groupe; // on récupère le groupe sélectionné

    		$('.form-selection-groupe').css('display', 'none');
    		$('.selection-concernes').css('display', 'inline');
    		$('#recherche-groupe').val('');
 			$('#recherche-user').focus();

        	event.preventDefault();
    	}
	});
	
	$('.groupe-selectionne').on('click', '.rm-groupe', function(){ // Si on clique sur la croix de déselection d'un groupe
		$('.groupe-selectionne').html(''); // On vide le groupe sélectionné
		$('.form-selection-groupe').css('display', 'inline'); // On affiche le form de sélection d'un groupe
		$('.selection-concernes').css('display', 'none'); // On cache le formulaire de sélection des concernés
		$('.users-selectionnes').html(''); // On vide les users sélectionnés
		users_selectionnes = []; // on vide le tableau des users sélectionnés
	});



	// Autocomplétion choix de l'user
	$('#recherche-user').autocomplete({
	    source : function(requete, reponse){ // les deux arguments représentent les données nécessaires au plugin
		$.ajax({
	            url : 'index.php?page=ajax&page_ajax=autoc_users', // on appelle le script JSON
	            dataType : 'json', // on spécifie bien que le type de données est en JSON
	            data : {
	                term : $('#recherche-user').val(), // on donne la chaîne de caractère tapée dans le champ de recherche
	            	id_groupe : groupe_courant
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
	    	// On affiche le groupe sélectionné

	    	if(users_selectionnes.indexOf(ui.item.id_user) == -1) // Si l'user n'a pas déjà été sélectionné
	    	{
        		$('.users-selectionnes').append('<span class="item-user badge badge-success" iduser="' + ui.item.id_user + '">' + ui.item.prenom + ' ' + ui.item.nom + ' <span class="rm-user"> <span class="glyphicon glyphicon-remove"></span></span></span>'); 
        		users_selectionnes.push(ui.item.id_user);
        	}

        	$('#recherche-user').val('');
        	$('#recherche-user').attr('placeholder', 'Ajouter quelqu\'un');

        	event.preventDefault();
    	}
	});

	$('.users-selectionnes').on('click', '.rm-user', function(){ // Si on clique sur la croix de déselection d'un groupe
		$(this).parent().remove();
		users_selectionnes.splice(users_selectionnes.indexOf($(this).parent().attr('idUser')), 1);
	});

	function envoyer_potin()
	{
   		if(groupe_courant != 'none') // Si un groupe est sélectionné
   		{
   			if(users_selectionnes.length != 0) // Si au moins un user est sélectionné
   			{
   				if($('.textarea-potin-txt').val().trim() != '') // Si un potin est écrit (on enlève les espaces)
   				{
   					

					$("#uploadimage").submit(); // Déclenchement de la soumission du formulaire
					
   				}
   				else
   				{
   					new PNotify({
					    title: 'Oups ! :s',
					    text: 'Il faut écrire un potin !',
					    type: 'error',
					    nonblock: {
    					    nonblock: true,
    					    nonblock_opacity: .2
    					}
					});
   				}  			
   			}
   			else
   			{
   				new PNotify({
				    title: 'Oups ! :s',
				    text: 'Il faut choisir des personnes concernées par le potin',
				    type: 'error',
				    nonblock: {
    				    nonblock: true,
    				    nonblock_opacity: .2
    				}
				});
   			}
   		}
   		else
   		{
   			new PNotify({
			    title: 'Oups ! :s',
			    text: 'Il faut choisir un groupe où publier ton potin',
			    type: 'error',
			    nonblock: {
    			    nonblock: true,
    			    nonblock_opacity: .2
    			}
			});
   		} 		
	}

	// clic sur le bouton envoyer
	$('.wrapper-btn-nv-ptn').on('click', '.btn-nv-ptn-envoyer', function(){
		envoyer_potin();
	});

	// Appui sur la touche entrée
	$('.textarea-potin-txt').keyup(function(e) {
	    if(e.keyCode == 13 && !e.shiftKey) {
	      	e.preventDefault(); // prevent default behavior
	      	envoyer_potin();
	     }
	});


	// S'active à la soumission du formulaire déclenchée par envoyer_potin
	$("#uploadimage").on('submit',(function(e) { 

		e.preventDefault(); // Bloque l'envoi du formulaire

		var form_data = new FormData(this); // création du formulaire à envoyer, en récupérant le fichier
		form_data.append("id_groupe", groupe_courant); // On ajoute l'id_groupe
		form_data.append("users_concernes", JSON.stringify(users_selectionnes));
		form_data.append("potin", $('.textarea-potin-txt').val());

		// Requête AJAX pour envoyer le potin, et avoir la présentation du nouveau potin à afficher
   		var requete_p = 'index.php?page=ajax&page_ajax=new_potin';

		// requête AJAX
		$.ajax({
			url: requete_p, // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				$('#emplacement-nouveau-potin').after(data); // On met à jour
//				$(".wrapper-potins").gridalicious('append', (function(data) {
//					var boxes = new Array;   
//					boxes.push(data);   
//					return boxes;
//				}));
				$('.col-potin-test:eq(0)').hide(); // on fait disparaître le potin qu'on vient d'ajouter
				$('.col-potin-test:eq(0)').fadeIn("slow"); // Pour le faire apparaître en fondu

				// On affiche une notif pour dire que le potin a été envoyé
				new PNotify({
				    title: 'Merci ! :D',
				    text: 'Ton potin a bien été publié anonymement !',
				    type: 'success',
				    nonblock: {
			    	    nonblock: true,
			    	    nonblock_opacity: .2
			    	}
				});
			}
		});

		// on réinitialise les champs du formulaire
		$('.groupe-selectionne').html(''); // On vide le groupe sélectionné
		$('.form-selection-groupe').css('display', 'inline'); // On affiche le form de sélection d'un groupe
		$('.selection-concernes').css('display', 'none'); // On cache le formulaire de sélection des concernés
		$('.users-selectionnes').html(''); // On vide les users sélectionnés
		$('.textarea-potin-txt').val('');
		$('#image_preview').css("display", "none");
		users_selectionnes = []; // on vide le tableau des users sélectionnés
		groupe_courant = 'none'; // On réinitialise le groupe sélectionné

	}));



	// Function to preview image after validation
	$(function() {
		// Lorsqu'on change le fichier sélectionné
		$("#file").change(function() {
			//$("#message").empty(); // To remove the previous error message

			var file = this.files[0]; // Sélectionne le premier fichier sélectionné
			var imagefile = file.type; // Prend le type du fichier sélectionné
			var match= ["image/jpeg","image/png","image/jpg"]; // Extensions autorisées

			// Si l'extension ne correspond pas
			if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))) 
			{
				new PNotify({
				    title: 'Oups ! :s',
				    text: 'L\'image doit être de type jpg, jpeg ou png',
				    type: 'error',
				    nonblock: {
    				    nonblock: true,
    				    nonblock_opacity: .2
    				}
				});
				//$('#previewing').attr('src','noimage.png'); // On affiche une image standard
				// On affiche un message d'erreur
				//$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
				return false; // On quitte la fonction
			}
			else // Si l'extension est bonne
			{
				var reader = new FileReader(); 

				reader.onload = imageIsLoaded; // Associe à l'évènement "onload" le gestionnaire d'événement ImageIsLoaded
				reader.readAsDataURL(file); // Lit le contenu du fichier
			}
		});
	});

	function imageIsLoaded(e) {
		//$("#file").css("color","green");
		$('#image_preview').css("display", "block");
		$('#previewing').attr('src', e.target.result);
		$('#previewing').attr('width', '100%'); //250
		$('#previewing').attr('height', '100%'); //230
	};
	
});

