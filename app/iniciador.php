<?php
	require_once("config/configurar.php");
	require_once("helpers/redirect.php");
	// require_once("helpers/url_helper.php");
	// require_once("helpers/bootstrap_alerts.php");

	spl_autoload_register(function($nombreClase) {
		require_once('librerias/' . $nombreClase . '.php');
	});

?>