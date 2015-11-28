<p>Donnez votre avis pour savoir ce que les autres pensent de ce potin</p>

<form action="#" method="post" class="vote-potin" class="form-lien">
	<input type="hidden" name="vote_potin" value="ok" />
	<input type="hidden" name="numero_potin" value="<?php echo $potin_courant['id_Potin']; ?>" /> 

	<button class="btn btn-success btn-block" type="submit" name="vote_positif" value="vote_positif">C'est vrai</button>
	<button class="btn btn-info btn-block" type="submit" name="vote_neutre" value="vote_neutre">Je ne sais pas</button>
	<button class="btn btn-danger btn-block" type="submit" name="vote_negatif" value="vote_negatif">C'est faux !</button>

</form>