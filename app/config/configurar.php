<?php
	// conexion con la base de datos
	define('DB_HOST','localhost');
	define('DB_USER','root');
	define('DB_PASSWORD','');
	define('DB_NAME','sfvi');

	// Rutas
	define('RUTA_APP', dirname(dirname(__FILE__)));
	define('RUTA_PUBLIC', $_SERVER['DOCUMENT_ROOT'] . '/SFVI/public/');
	define('RUTA_DOCS', $_SERVER['DOCUMENT_ROOT'] . '/SFVI/docs/');
	define('RUTA_URL','http://localhost/SFVI/');
	
	// Nombre de la aplicacion
	define('NAME_SITE', 'SFVI');

	// correo
	define('EMAIL_USERNAME', '');
	define('EMAIL_PASSWORD', '');
?>