$(function() {
  // Ici, le DOM est entièrement défini

  function envoyer_commentaire(idPotin, idUser, typePotin) {
  	// Requête AJAX pour envoyer le com et avoir une mise à jour de l'affichage des com
  	if(typePotin == 'v3')
  	{
   		var requete_p = 'index.php?page=ajax&page_ajax=potin_nouv_com';
   	}
   	else if(typePotin == 'v4')
   	{
   		var requete_p = 'index.php?page=ajax&page_ajax=potin_nouv_com_v4';
   	}

  		$.post(requete_p, // Le fichier cible côté serveur.
		    {
		        id_Potin : idPotin, // Nous supposons que ce formulaire existe dans le DOM.
		        auteur : idUser, // id du commentateur
		        comment : $('#potin-' + idPotin +' .potin-textarea-com').val(), // Le commentaire à publier
		    },
		    function (texte_recu){
		    	if(typePotin == 'v3')
  				{
			    	$('#potin-' + idPotin +' .potin-d-test').replaceWith(texte_recu); // On met à jour l'affichage des commentaires
			    }
			    else if(typePotin == 'v4')
   				{
   					$('#potin-' + idPotin +' .prochain-com').before(texte_recu);
   					$('#potin-' + idPotin +' .potin-textarea-com').val('');
   				}
			},
		    'html' // Format des données reçues.
		);
  }

  	// Clic sur bouton "envoyer commentaire"
  	$('.wrapper-potins').on('click', '.potin-envoyer-com', function(){
	    var idPotin = $(this).attr('idPotin'); // On récupère l'id du potin
   		var idUser = $(this).attr('idUser'); // On récupère l'id de l'user
   		var typePotin = $(this).attr('typePotin'); 

   		envoyer_commentaire(idPotin, idUser, typePotin);
	});

  	// Appui sur la touche entrée pour commenter
	$('.potin-textarea-com').keyup(function(e) {
	    if(e.keyCode == 13 && !e.shiftKey) {
	      	var idPotin = $(this).attr('idPotin'); // On récupère l'id du potin
   			var idUser = $(this).attr('idUser'); // On récupère l'id de l'user
   			var typePotin = $(this).attr('typePotin'); 

   			envoyer_commentaire(idPotin, idUser, typePotin);
   		}
	});

  	// Clic sur bouton de vote
	$('.wrapper-potins').on('click', '.potin-bouton', function(){
	    var idPotin = $(this).attr('idPotin'); // On récupère l'id du potin
   		var idUser = $(this).attr('idUser'); // On récupère l'id de l'user
   		var typeBouton = $(this).attr('typeBouton'); // On récupère l'action du bouton
   		var typePotin = $(this).attr('typePotin');

   		// Requête AJAX pour envoyer le vote et avoir une mise à jour de l'affichage des votes
   		if(typePotin == 'v3')
  		{
   			var requete_p = 'index.php?page=ajax&page_ajax=potin_nouv_vote';
   		}
   		else if(typePotin == 'v4')
   		{
   			var requete_p = 'index.php?page=ajax&page_ajax=potin_nouv_vote_v4';
   		}
   		
   		
  		$.post(requete_p, // Le fichier cible côté serveur.
		    {
		        id_Potin : idPotin, // Nous supposons que ce formulaire existe dans le DOM.
		        auteur : idUser, // id du commentateur
		        vote : typeBouton, // Le commentaire à publier
		    },
		    function (data){
		    	if(typePotin == 'v3')
  				{
			    	$('#potin-' + idPotin +' .potin-vote-wrapper').replaceWith(data); // On met à jour l'affichage des commentaires
				}
				else if(typePotin == 'v4')
   				{
   					$('#potin-' + idPotin +' .potin-vote-wrapper-v4').replaceWith(data); // On met à jour l'affichage des commentaires
				}
			},
		    'html' // Format des données reçues.
		);

		// Requête AJAX pour maj du nb de points 
   		var requete_p = 'index.php?page=ajax&page_ajax=maj_points';
  		$.post(requete_p, // Le fichier cible côté serveur.
		    0,
		    function (data){
			    $('.points-user').replaceWith(data); // On met à jour l'affichage des commentaires
			},
		    'html' // Format des données reçues.
		);
	});

	$('.wrapper-potins').on('click', ".drop-supprimer-potin", function(event){
	//$( ".drop-supprimer-potin" ).click(function( event ) {
		event.preventDefault();
		var idPotin = $(this).attr('idPotin'); // On récupère l'id du potin
		
   		var requete_p = 'index.php?page=ajax&page_ajax=supprimer_potin';
   		
  		$.post(requete_p, // Le fichier cible côté serveur.
		    {
		        id_potin : idPotin,
		    },
		    function (data){
			    $('#potin-' + idPotin).fadeOut("slow");

			    new PNotify({
				    title: 'Et voilà !',
				    text: 'Ton potin a bien été supprimé !',
				    type: 'success',
				    nonblock: {
			    	    nonblock: true,
			    	    nonblock_opacity: .2
			    	}
				});
			},
		    'html' // Format des données reçues.
		);
	});

});