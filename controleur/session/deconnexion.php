<?php
	vider_cookie();

	$_SESSION = array();
	session_destroy();
?>