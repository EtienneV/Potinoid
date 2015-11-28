		<footer>
			<div class="container">
				(C) Chips Ondulée <br/>
			</div>
		</footer>

		<script src="librairies/bootstrap/js/jquery.js"></script>
		<script type="text/javascript" src="librairies/bootstrap/js/jquery-ui.js"></script>
		<script src="librairies/bootstrap/js/bootstrap.js "></script>
		<script src="librairies/bootstrap/js/dropdowns-enhancement.js "></script>
		<script type="text/javascript" src="javascript/pnotify.custom.min.js"></script>
		<script type="text/javascript" src="librairies/soundmanager/soundmanager2.js"></script>

		<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->
    	<script src="javascript/ajax_modal.js"></script>

    	<script src="javascript/main.js"></script>
    	<script src="javascript/accueil_scrolling.js"></script>
    	<script src="javascript/affichage_potin.js"></script>
		<script type="text/javascript" src="javascript/write_potin.js"></script>
		<script type="text/javascript" src="javascript/potin_externe.js"></script>

		<script src="javascript/jquery.grid-a-licious.js"></script>
		
		<script src="javascript/jquery.shapeshift.js"></script>
		<script src="javascript/jquery.nested.js"></script>

		<!-- Pour la "fenêtre-potin" -->
		<script type="text/javascript">
			$(function () {
			  $('[data-toggle="tooltip"]').tooltip()
			})
		</script>

		<script type="text/javascript">
			function onComplete(){}

		   $(document).ready(function () {

        // example
       $(".wrapper-potins").gridalicious({
        	width: 400,
        	gutter: 20,
		  animate: true,
		  animationOptions: {
		    queue: true,
		    speed: 200,
		    duration: 300,
		    effect: 'fadeInOnAppear',
		    complete: onComplete
		  }
		});
    });
		</script>
</html>