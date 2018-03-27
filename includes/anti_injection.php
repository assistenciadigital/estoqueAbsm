<?php 
// Funcao para filtrar (Anti-SQL-Injection)
Function Filtrar($str){
	$sql = preg_replace(sql_regcase('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/'),'',$str);
	$sql = trim($str);
	$sql = strip_tags($str);
	$sql = addslashes($str);
return $str;
}

?>