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

  var num_page = 1;
  var nb_potins_requete = 10;


  var deviceAgent = navigator.userAgent.toLowerCase();
  var agentID = deviceAgent.match(/(iphone|ipod|ipad)/);
  
  // on déclence une fonction lorsque l'utilisateur utilise sa molette 
  $(window).scroll(function() {   
    // cette condition vaut true lorsque le visiteur atteint le bas de page
    // si c'est un iDevice, l'évènement est déclenché 150px avant le bas de page
    if(($(window).scrollTop() + $(window).height()) == $(document).height()
    || agentID && ($(window).scrollTop() + $(window).height()) + 150 > $(document).height()) {
      // on effectue nos traitements

      scroll();
    }
  }); 

  	// Si on clique sur le bouton de scrolling
	$('#bouton-accueil-scrolling').bind('click',function(){
    scroll();
	});



  function scroll() {
    if(typeof $_GET['page'] != 'undefined') // si on est sur une page particulière
    {
      if($_GET['page'] == 'groupe') // Si on est sur la page de groupe
      {
        scroll_page_groupe();
      }
      else if($_GET['page'] == 'page_perso')
      {
        if(typeof $_GET['onglet'] != 'undefined')
        {
          if($_GET['onglet'] == 'mes_potins')
          {
            scroll_perso_mespotins();
          }
          else if($_GET['onglet'] == 'potins_sur_moi')
          {
            scroll_perso_surmoi();
          }
        }
        else
        {
          scroll_perso_mespotins();
        }
          
      }
      else
      {
        scroll_accueil_v4();
      }
    }
    else // Sinon, on est sur la page d'accueil
    {
      scroll_accueil_v4();
      //scroll_accueil();
    }
  }

  function scroll_accueil() {
    $('#suite-scrolling').html('<div id="suite-scrolling"><img src="images/ajax-loader.gif"></img></div>');
  
    var requete = 'index.php?page=ajax&page_ajax=accueil_scrolling&num_page=' + num_page + '&limit=' + nb_potins_requete;
  
    $.get(requete, function(data){
      $('#fin-colonne').replaceWith(data);
    });
  
    num_page++;
  
    $('#suite-scrolling').html('');
  }

  function scroll_accueil_v4() {
    $('#suite-scrolling').html('<div id="suite-scrolling"><img src="images/ajax-loader.gif"></img></div>');
  
    var requete = 'index.php?page=ajax&page_ajax=accueil_scrolling_v4&num_page=' + num_page + '&limit=' + nb_potins_requete + '&id_groupe=' + $_GET['id_groupe'];
  
    $.get(requete, function(data){

      var obj = jQuery.parseJSON(data);

      if(obj.nb_potins > 0)
      {
        $(".wrapper-potins").gridalicious('append', makeboxes(obj));
      }
      else
      {
        new PNotify({
             title: 'Oups ! :s',
             text: 'Plus de potins !',
             type: 'error',
             nonblock: {
                 nonblock: true,
                 nonblock_opacity: .2
             }
         });
      }
    });
  
    num_page++;

  
    $('#suite-scrolling').html('');
  }

  function scroll_page_groupe() {
    $('#suite-scrolling').html('<div id="suite-scrolling"><img src="images/ajax-loader.gif"></img></div>');
  
    var requete = 'index.php?page=ajax&page_ajax=groupe_scrolling&num_page=' + num_page + '&limit=' + nb_potins_requete + '&id_groupe=' + $_GET['id_groupe'];
  
    $.get(requete, function(data){

      var obj = jQuery.parseJSON(data);

      if(obj.nb_potins > 0)
      {
        $(".wrapper-potins").gridalicious('append', makeboxes(obj));
      }
      else
      {
        new PNotify({
             title: 'Oups ! :s',
             text: 'Plus de potins dans ce groupe.',
             type: 'error',
             nonblock: {
                 nonblock: true,
                 nonblock_opacity: .2
             }
         });
      }
    });
  
    num_page++;

  
    $('#suite-scrolling').html('');
  }

  function scroll_perso_mespotins() {
    $('#suite-scrolling').html('<div id="suite-scrolling"><img src="images/ajax-loader.gif"></img></div>');
  
    var requete = 'index.php?page=ajax&page_ajax=perso_mespotins_scrolling&num_page=' + num_page + '&limit=' + nb_potins_requete;
  
    $.get(requete, function(data){

      var obj = jQuery.parseJSON(data);

      if(obj.nb_potins > 0)
      {
        $(".wrapper-potins").gridalicious('append', makeboxes(obj));
      }
      else
      {
        new PNotify({
             title: 'Oups ! :s',
             text: 'Plus de potins !',
             type: 'error',
             nonblock: {
                 nonblock: true,
                 nonblock_opacity: .2
             }
         });
      }
    });
  
    num_page++;

  
    $('#suite-scrolling').html('');
  }

  function scroll_perso_surmoi() {
    $('#suite-scrolling').html('<div id="suite-scrolling"><img src="images/ajax-loader.gif"></img></div>');
  
    var requete = 'index.php?page=ajax&page_ajax=perso_surmoi_scrolling&num_page=' + num_page + '&limit=' + nb_potins_requete;
  
    $.get(requete, function(data){

      var obj = jQuery.parseJSON(data);

      if(obj.nb_potins > 0)
      {
        $(".wrapper-potins").gridalicious('append', makeboxes(obj));
      }
      else
      {
        new PNotify({
             title: 'Oups ! :s',
             text: 'Plus de potins !',
             type: 'error',
             nonblock: {
                 nonblock: true,
                 nonblock_opacity: .2
             }
         });
      }
    });
  
    num_page++;

  
    $('#suite-scrolling').html('');
  }

  function makeboxes(obj) {
    var boxes = new Array;   

    for (var i = 0 ; i <= obj.nb_potins ; i++) {
        boxes.push(obj['potin'+i]);
    }

    return boxes;
  }
  
});

