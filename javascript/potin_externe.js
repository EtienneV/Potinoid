$(function() {
  	// Ici, le DOM est entièrement défini

  	$( ".decouvrir-potin-externe" ).click(function() {
  		var idConcerne = $(this).attr('idConcerne');
  		var idGroupe = $(this).attr('idGroupe');

	  	var requete = 'index.php?page=ajax&page_ajax=new_potin_externe&id_concerne='+idConcerne+'&id_groupe='+idGroupe;

	  	$.post(
		    'index.php?page=ajax&page_ajax=new_potin_externe', // Le fichier cible côté serveur.
		    {
		        id_concerne : idConcerne,
		        id_groupe : idGroupe
		    },
		    function(data)
			{
				var obj = jQuery.parseJSON(data);

				if(obj.message == 'succes')
				{		
			        $('.potin-externe-suivant-'+idGroupe).after(obj.potin);
			
			        // Requête AJAX pour maj du nb de points 
				   	var requete_p = 'index.php?page=ajax&page_ajax=maj_points';
				  	$.post(requete_p, // Le fichier cible côté serveur.
					    0,
					    function (data){
						    $('.points-user').replaceWith(data); // On met à jour l'affichage des commentaires
						},
					    'html' // Format des données reçues.
					);
				}
				else if (obj.message == 'pas_points') 
				{
					new PNotify({
					    title: 'Oups ! :s',
					    text: 'Tu n\'as pas assez de points :(',
					    type: 'error',
					    nonblock: {
    					    nonblock: true,
    					    nonblock_opacity: .2
    					}
					});
				}
				else if (obj.message == 'pas_potins') 
				{
					new PNotify({
					    title: 'Oups ! :s',
					    text: 'Il n\'y a plus de potins à découvrir',
					    type: 'error',
					    nonblock: {
    					    nonblock: true,
    					    nonblock_opacity: .2
    					}
					});
				}
				else if (obj.message == 'non_autorise') 
				{
					new PNotify({
					    title: 'Oups ! :s',
					    text: 'Tu n\'est pas autorisé(e) à découvrir ce potin ...',
					    type: 'error',
					    nonblock: {
    					    nonblock: true,
    					    nonblock_opacity: .2
    					}
					});
				}
			},
		    'text' // Format des données reçues.
		);
	});

});