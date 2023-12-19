<?php
	require 'vendor/autoload.php';
	require_once("config/configurar.php");
	require_once("helpers/redirect.php");
	require_once("helpers/mail.php");

	spl_autoload_register(function($nombreClase) {
		require_once('librerias/' . $nombreClase . '.php');
	});

?>