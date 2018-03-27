<?php
	$hostname_conexao = "localhost";
	$database_conexao = "estoque";	
	$username_conexao = "root";
	$password_conexao = "root";

$con = mysql_connect($hostname_conexao, $username_conexao, $password_conexao) or trigger_error(mysql_error(),E_USER_ERROR);
$db = mysql_select_db ($database_conexao, $con) or die ('Não foi possível abrir o banco de dados');
?>