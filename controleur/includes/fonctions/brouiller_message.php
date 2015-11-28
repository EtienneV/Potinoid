<?php
function brouiller_message($message)
{
	$taille = strlen($message);

	$brouille = chr(rand(65, 90));

	for ($i=1; $i < $taille - 1; $i++) {
		if (mb_substr($message,$i,1,'UTF-8') != ' ') {
			$brouille .= chr(rand(97, 122));
		}
		else
		{
			$brouille .= ' ';
		}
	}

	$brouille .= '.';

	echo $brouille;
}
