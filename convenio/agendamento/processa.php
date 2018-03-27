<?php 
session_start();

if($_SESSION['acesso_convenio']!="S"){
$resultado = "Sem Permissão de Acesso! Contacte o Administrador do Sistema.";
$url_de_destino = "../../index.php";
include "../../includes/redireciona.php";	
}

if(($_SESSION['login']) AND ($_SESSION['nivel'])){
	include "../../includes/conect.php";
	include "../../includes/data.php";
	include "../../includes/ajustadataehora.php";
	
	$rota = "../../imagens/encaminha/";
	if(!file_exists($rota)) @mkdir ($rota, 0777);
	
	$foto = $_FILES[imagem]['name'];
		
    $select = @mysql_fetch_assoc(mysql_query("SELECT * FROM convenio_encaminha where id = '".$_POST['id']."'"));
	
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
		    $select = @mysql_fetch_assoc(mysql_query("SELECT * FROM convenio_encaminha where id = '".$_GET['id']."'"));
			mysql_query ("DELETE FROM convenio_encaminha WHERE id ='".$_GET['id']."'");
			$sucesso = mysql_affected_rows();
			if ($sucesso) $resultado = "Registro: ".$_GET['id'].", excluido com sucesso!";
			else $resultado = "Erro na exclusão do registro: ".$_GET['id'];
	}
	else $resultado = "Você não tem permissão para excluír o registro: ".$_GET['id'].", Contacte o Administrador do Sistema!";	
	}
//------------------------------------->> ALTERAR	
	else if ($_GET['acao'] == 'alteracao'){	//SE FOR ALTERACAO
	if(($_SESSION['nivel'])<=2){
	if (!(mysql_num_rows (mysql_query ("SELECT id FROM convenio_encaminha WHERE numero_guia = '".$_POST['numero_ERRO_guia']."' and titular = '".$_POST['titular']."'")))) { //VERIFICA DUPLICIDADE
			$altera = mysql_query("UPDATE convenio_encaminha SET numero_guia='".$_POST['numero_guia']."', data_guia='".data_date($_POST['data_guia'])."', titular='".$_POST['titular']."', dependente='".$_POST['dependente']."', tipo_especialidade='".$_POST['tipo']."', especialidade='".$_POST['especialidade']."', descricao='".trim(addslashes(htmlentities(strtoupper($_POST['descricao']))))."', profissional='".$_POST['profissional']."', origem='".$_POST['origem']."', destino='".$_POST['destino']."', motivo='".$_POST['motivo']."', data_agenda='".data_date($_POST['data_agenda'])."', hora_agenda='".$_POST['hora_agenda']."', observacao='".trim(addslashes(htmlentities(strtoupper($_POST['observacao']))))."', data='".date('Y-m-d H:i:s')."', usuario_login='".$_SESSION['login']."' where id ='".$_POST['id']."'");

			if($altera) $resultado = "Registro: ".$_POST['id'].", alterado com sucesso!";
			else $resultado = "Erro ao alterar o registro: ".$_POST['id'].$rota.$_POST['id'].'.jpg';
	}
	else $resultado = "Registro Duplicado! Descrição.";
	}
	else $resultado = "Você não tem permissão para alterar o registro: ".$_POST['id'].", contacte o administrador do sistema!";
	}

//------------------------------------->> INCLUIR
	else {
		if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO		
		if (!(mysql_num_rows (mysql_query ("SELECT id FROM convenio_encaminha WHERE numero_guia = '".$_POST['numero_guia']."' and titular = '".$_POST['titular']."'")))) { // VERFICA DUPLICIDADE
			$insere = mysql_query ("INSERT convenio_encaminha(numero_guia, data_guia, titular, dependente, tipo_especialidade, especialidade, descricao, profissional, data_agenda, hora_agenda, origem, destino, motivo, observacao, data, usuario_login) VALUES('".$_POST['numero_guia']."', '".data_date($_POST['data_guia'])."', '".$_POST['titular']."', '".$_POST['dependente']."', '".$_POST['tipo']."', '".$_POST['especialidade']."', '".trim(addslashes(htmlentities(strtoupper($_POST['descricao']))))."', '".$_POST['profissional']."', '".data_date($_POST['data_agenda'])."', '".$_POST['hora_agenda']."', '".$_POST['origem']."', '".$_POST['destino']."', '".$_POST['motivo']."', '".trim(addslashes(htmlentities(strtoupper($_POST['observacao']))))."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");
			
			if ($insere){
				$resultado = "Registro: ".mysql_insert_id().", inserido com sucesso!";
				$select = @mysql_fetch_assoc(mysql_query("SELECT * FROM convenio_encaminha where id = '".mysql_insert_id()."'"));}
			else $resultado = "Erro ao cadastrar registro!";			
		}
		else $resultado = "Registro Duplicado! Descrição.";
	}
	else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	}

	if (!$url_de_destino) $url_de_destino = "lista.php?titular=".$select['titular'];
	include "../../includes/redireciona.php";
	}else{
	$url_de_destino = "../../login.php";
	include "../../includes/redireciona.php";
}
?>