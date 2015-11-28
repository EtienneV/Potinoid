<?php

function parse_smileys($texte)
{

	$in=array(
	           ":-)", //sourire
	           ":-D", // mort de rire
	           ":-p", // passe la langue
	           ":-(", // triste
	           ":)", //sourire
	           ":D", // mort de rire
	           ":p", // passe la langue
	           ":(", // triste
	           "^^", // Content
	           "Oo"
	           );

	$path = 'images/smiley/';
	
	$out=array(
	           '<img src="'.$path.'smile.png" alt="" width="18" height="18"/>',
	           '<img src="'.$path.'grin.png" alt="" width="18" height="18"/>',
	           '<img src="'.$path.'tongue.png" alt="" width="18" height="18"/>',
	           '<img src="'.$path.'sad.png" alt="" width="18" height="18"/>',
	           '<img src="'.$path.'smile.png" alt="" width="18" height="18"/>',
	           '<img src="'.$path.'grin.png" alt="" width="18" height="18"/>',
	           '<img src="'.$path.'tongue.png" alt="" width="18" height="18"/>',
	           '<img src="'.$path.'sad.png" alt="" width="18" height="18"/>',
	           '<img src="'.$path.'happy.png" alt="" width="18" height="18"/>',
	           '<img src="'.$path.'blink.png" alt="" width="18" height="18"/>'
	           );

    return str_replace($in,$out,$texte);
}

?>