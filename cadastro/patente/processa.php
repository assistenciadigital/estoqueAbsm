﻿<?php 
session_start();

if($_SESSION['acesso_cadastro']!="S"){
$resultado = "Sem Permissão de Acesso! Contacte o Administrador do Sistema.";
$url_de_destino = "../../index.php";
include "../../includes/redireciona.php";	
}

if(($_SESSION['login']) AND ($_SESSION['nivel'])){
	include "../../includes/conect.php";
	include "../../includes/ajustadataehora.php";
		
    $select = @mysql_fetch_assoc(mysql_query("SELECT * FROM patente where patente_id = '".$_POST['id']."'"));
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="../../css/lista.css" rel="stylesheet">
</head>

<body>

<div id="main" align="center">

<strong><h1>Aguarde... Processando solicitação de <?php if ($_GET['acao']) echo '<strong>'.$_GET['acao'].'</strong>'; else echo 'inclusao';?></h1></strong>
</div>
</body>
</html>
<?php
	if ($_GET['acao'] == 'exclusao'){	
	if(($_SESSION['nivel'])<=2){
			mysql_query ("DELETE FROM patente WHERE patente_id='".$_GET['id']."'");
			$sucesso = mysql_affected_rows();
			if ($sucesso) $resultado = "Registro: ".$_GET['id'].", excluido com sucesso!";
			else $resultado = "Erro na exclusão do registro: ".$_GET['id'];
	}
	else $resultado = "Você não tem permissão para excluír o registro: ".$_GET['id'].", Contacte o Administrador do Sistema!";	
	}
//------------------------------------->> ALTERAR	
	else if ($_GET['acao'] == 'alteracao'){	//SE FOR ALTERACAO
	if(($_SESSION['nivel'])<=2){
	if (!(mysql_num_rows (mysql_query ("SELECT patente_id FROM patente WHERE patente_descricao = '".$_POST['descricao']."'")))) { //VERIFICA DUPLICIDADE
			$altera = mysql_query("UPDATE patente SET patente_descricao='".trim(addslashes(htmlentities(strtoupper($_POST['descricao']))))."', patente_detalhe='".trim(addslashes(htmlentities(strtoupper($_POST['detalhe']))))."', patente_ordem='".$_POST['ordem']."', data='".date('Y-m-d H:i:s')."', usuario_login='".$_SESSION['login']."' where patente_id='".$_POST['id']."'");
			if($altera) $resultado = "Registro: ".$_POST['id'].", alterado com sucesso!";
			else $resultado = "Erro ao alterar o registro: ".$_POST['id'].".";
	}
	else $resultado = "Registro Duplicado! Descrição.";
	}
	else $resultado = "Você não tem permissão para alterar o registro: ".$_POST['id'].", contacte o administrador do sistema!";
	}

//------------------------------------->> INCLUIR
	else {
		if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO		
		if (!(mysql_num_rows (mysql_query ("SELECT patente_id FROM patente WHERE patente_descricao='".$_POST['descricao']."'")))) { // VERFICA DUPLICIDADE
			$insere = mysql_query ("INSERT patente(patente_descricao, patente_detalhe, patente_ordem, data, usuario_login) VALUES('".trim(addslashes(htmlentities(strtoupper($_POST['descricao']))))."', '".trim(addslashes(htmlentities(strtoupper($_POST['detalhe']))))."', '".$_POST['ordem']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");
			if ($insere) $resultado = "Registro: ".mysql_insert_id().", inserido com sucesso!";
			else $resultado = "Erro ao cadastrar registro!";			
		}
		else $resultado = "Registro Duplicado! Descrição.";
	}
	else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	}

	if (!$url_de_destino) $url_de_destino = "lista.php";
	include "../../includes/redireciona.php";
	}else{
	$url_de_destino = "../../login.php";
	include "../../includes/redireciona.php";
}
?>