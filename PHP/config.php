<?php
// Conexão à BD
	$host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
	$user="#######"; // -> substituir pelo nome de utilizador
	$password="#####"; // -> substituir pela password dada pelo mysql_reset
	$dbname = $user; // a BD tem nome identico ao utilizador
	$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password,
	array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");
?>