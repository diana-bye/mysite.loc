<?php
	$DB_CONNECTION_STRING = "host=localhost port=5432 dbname=rating user=postgres password=postgres"; 
    session_start();
		
	$dbconnect = pg_connect($DB_CONNECTION_STRING);
	if (!$dbconnect) {
		echo "Ошибка подключения к БД";
		http_response_code(500);
		exit;
	}
?>