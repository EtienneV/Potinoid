$(function() {
// Ici, le DOM est entièrement défini

	$( "#lien_oubli_mdp" ).click(function(event) {

		event.preventDefault();

		$('.oubli_mdp_w').css("display", "inline");

		$('#mail_nv_mdp').val($('#mail').val());

	});

	$( "#btn_nv_mdp" ).click(function(event) {

		event.preventDefault();

		$.post(
		    'index.php?page=oubli_mdp', // Le fichier cible côté serveur.
		    {
		        mail : $('#mail_nv_mdp').val(),
		    },
		    function(data)
			{				
				if(data == 'ok') // Si le mail existe -> mdp changé
				{
					new PNotify({
					    title: 'Bravo !',
					    text: 'Ton nouveau mot de passe a été envoyé à '+$('#mail_nv_mdp').val(),
					    type: 'success',
					    nonblock: {
    					    nonblock: true,
    					    nonblock_opacity: .2
    					}
					});
				}
				else // Sinon, le mail n'existe pas
				{
					new PNotify({
					    title: 'Oups ! :s',
					    text: 'Ce mail n\'existe pas.',
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