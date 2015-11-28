<?php

include_once('modele/groupes/TF_nouveau_groupe.php');

?>

<div class="page-header">
  <h2>Nouveau groupe</h2>
  <div id="pac-charger">Chargement ... <img src="images/ajax-loader.gif"></img></div>
</div>

<form action="#" method="post" name="f_nouv_groupe">

	<input type="hidden" name="form_nouv_groupe" value="ok">

	<div class="form-group">
	  <label for="nom_groupe">Nom du groupe</label>
	  <input type="text" class="form-control" id="nom_groupe" name="nom_groupe">
	</div>

	<div class="form-group">
	  <label for="description_groupe">Description du groupe</label>
	  <textarea class="form-control" rows="3" id="description_groupe" name="description_groupe"></textarea>
	</div>

	<button type="submit" class="btn btn-primary pull-right">Créer le groupe</button>

</form>