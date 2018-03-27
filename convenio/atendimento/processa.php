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
if ($_GET['acao'] == 'alteracao'){	//SE FOR ALTERACAO
	if(($_SESSION['nivel'])<=2){
		$altera = mysql_query("UPDATE convenio_encaminha SET data_atende='".data_date($_POST['data_atende'])."', hora_atende='".$_POST['hora_atende']."', status_atende='".$_POST['status']."', data='".date('Y-m-d H:i:s')."', usuario_atende='".$_SESSION['login']."' where id ='".$_POST['id']."'");
			if($altera) $resultado = "Registro: ".$_POST['id'].", alterado com sucesso!";
			else $resultado = "Erro ao alterar o registro: ".$_POST['id'].$rota.$_POST['id'].'.jpg';
	}
	else $resultado = "Você não tem permissão para alterar o registro: ".$_POST['id'].", contacte o administrador do sistema!";
	}

	if (!$url_de_destino) $url_de_destino = "../agendamento/lista.php?titular=".$select['titular'];
	include "../../includes/redireciona.php";
	}else{
	$url_de_destino = "../../login.php";
	include "../../includes/redireciona.php";
}
?>