<?php
session_start();
if(($_SESSION['login']) AND ($_SESSION['nivel'])){
	
date_default_timezone_set("America/Cuiaba");
setlocale(LC_ALL, 'pt_BR');

function salvaLog($mensagem) {

$insere_log = mysql_query("INSERT log(ip, data, usuario, observacao) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."', '".mysql_escape_string($mensagem)."')");

}
}
?>