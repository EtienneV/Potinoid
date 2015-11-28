$(function() {
  // Ici, le DOM est entièrement défini

  $('#pac-charger').replaceWith('');


  // Complète la fenêtre de vote losqu'on la demande
  $('#voteModal').on('show.bs.modal', function (event) {

	  var button = $(event.relatedTarget); // Le bouton qu'on a appuyé
	  var recipient = button.data('whatever') ;
	  var modal = $(this);

	  var num_potin=recipient.substring(recipient.lastIndexOf(".") + 1);

	  var param = 'num_potin=' + num_potin;

	  //recipient = '<div id="inserer">' + num_potin + '</div>';

	  //$('#inserer').replaceWith(recipient);

	  $('#inserer_vote').load('vote_ajax.php', param);
	});

  // Complète la fenêtre de commentaire losqu'on la demande
  $('#commentModal').on('show.bs.modal', function (event) {

	  var button = $(event.relatedTarget); // Le bouton qu'on a appuyé
	  var recipient = button.data('whatever') ;
	  var modal = $(this);

	  var num_comment=recipient.substring(recipient.lastIndexOf(".") + 1);

	  var param = 'num_potin=' + num_comment;

	  //recipient = '<div id="inserer">' + num_potin + '</div>';

	  //$('#inserer').replaceWith(recipient);

	  $('#inserer_comment').load('comment_ajax.php', param);
	});

  
});

