<?php
session_start();
if(($_SESSION['login']) AND($_SESSION['nivel'])){
	
	include "../../painel/funcoesPHP/conexao.php";
	include "../../painel/funcoesPHP/data.php";

	$nulo = "NULL";
	$no_alt = "o Seu Nível não da permissao para esta solicitação, por favor informe seu Administrador!";
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../painel/css/estilos.css" rel="stylesheet" type="text/css" />
</head>
<script type="text/javascript" src="../../painel/funcoesJS/funcoes.js"></script>

<body onLoad="reajusta()" class="bg_pg_internas">
<div align="center">

<br/>
<table width="100%" height="800" border="0" align="center" cellpadding="0" cellspacing="0"  id="conteudo">
<tr valign="top">
<td align="center">
    <table width="800" height="50" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
    <td align="center" valign="middle" bgcolor="#4DBDCB"><h1 class="txtBranco">Aguarde processando...</h1></td>
    </tr>
    </table>
</td>
</tr>
</table>
</div>
</body>
</html>
<?php
if(($_SESSION['nivel_usuario'])<=2){
			$altera = mysql_query("UPDATE chamasenha SET chamado='".date('Y-m-d H:i:s')."', finalizado='".date('Y-m-d H:i:s')."', status='Chamado', data='".date('Y-m-d H:i:s')."', usuario='".$_SESSION['login']."' where id='".$_GET['id']."'");
			if($altera) $resultado = "Registro: ".$_GET['id'].", alterado com sucesso!";
			else $resultado = "Erro ao alterar o registro: ".$_GET['id'].".";
	}
	else $resultado = "Você não tem permissão para alterar o registro ".$_GET['id'].", contacte o administrador do sistema!";
	
	if (!$url_de_destino) $url_de_destino = "lista.php";
	include'../../painel/funcoesPHP/redireciona.php';
	
}else {
	$url_de_destino = "../../expira.php";
	$target = "_parent";
	include "../../painel/funcoesPHP/redireciona.php";
}
?>