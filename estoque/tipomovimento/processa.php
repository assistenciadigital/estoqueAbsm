<?php 
session_start();

if($_SESSION['acesso_admin']!="S"){
$resultado = "Sem Permissão de Acesso! Contacte o Administrador do Sistema.";
$url_de_destino = "../../index.php";
include "../../includes/redireciona.php";	
}

if(($_SESSION['login']) AND ($_SESSION['nivel'])){
	include "../../includes/conect.php";
	include "../../includes/data.php";
	include "../../includes/ajustadataehora.php";
		
    $select = @mysql_fetch_assoc(mysql_query("SELECT tipomovimento_id, tipomovimento_descricao, tipomovimento_tipo, tipomovimento_estorno, tipomovimento_customedio, tipomovimento_custoatual FROM tipomovimento where tipomovimento_id = '".$_POST['id']."'"));
	
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
			mysql_query ("DELETE FROM tipomovimento WHERE tipomovimento_id='".$_GET['id']."'");
			$sucesso = mysql_affected_rows();
			if ($sucesso) $resultado = "Registro: ".$_GET['id'].", excluido com sucesso!";
			else $resultado = "Erro na exclusão do registro: ".$_GET['id'];
	}
	else $resultado = "Você não tem permissão para excluír o registro: ".$_GET['id'].", Contacte o Administrador do Sistema!";	
	}
//------------------------------------->> ALTERAR	
	else if ($_GET['acao'] == 'alteracao'){	//SE FOR ALTERACAO
	if(($_SESSION['nivel'])<=2){
	if (!(mysql_num_rows (mysql_query ("SELECT tipomovimento_id FROM tipomovimento WHERE tipomovimento_descricao = '".$_POST['descricao']."' AND tipomovimento_tipo = '".$_POST['tipo']."' AND tipomovimento_estorno = '".$_POST['estorno']."'")))) { //VERIFICA DUPLICIDADE
			$altera = mysql_query("UPDATE tipomovimento SET tipomovimento_descricao='".trim(addslashes(htmlentities(strtoupper($_POST['descricao']))))."',  tipomovimento_tipo='".$_POST['tipo']."', tipomovimento_estorno='".$_POST['estorno']."',  tipomovimento_customedio='".$_POST['customedio']."',  tipomovimento_custoatual='".$_POST['custoatual']."', data='".date('Y-m-d H:i:s')."', usuario_login='".$_SESSION['login']."' where tipomovimento_id='".$_POST['id']."'");
			if($altera) $resultado = "Registro: ".$_POST['id'].", alterado com sucesso!";
			else $resultado = "Erro ao alterar o registro: ".$_POST['id'].".";
	}
	else $resultado = "Registro Duplicado!";
	}
	else $resultado = "Você não tem permissão para alterar o registro: ".$_POST['id'].", contacte o administrador do sistema!";
	}

//------------------------------------->> INCLUIR
	else {
		if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO		
		if (!(mysql_num_rows (mysql_query ("SELECT tipomovimento_id FROM tipomovimento WHERE tipomovimento_descricao='".$_POST['descricao']."' AND tipomovimento_tipo = '".$_POST['tipo']."' AND tipomovimento_estorno = '".$_POST['estorno']."'")))) { // VERFICA DUPLICIDADE
			$insere = mysql_query ("INSERT tipomovimento(tipomovimento_descricao, tipomovimento_tipo, tipomovimento_estorno, tipomovimento_customedio, tipomovimento_custoatual, data, usuario_login) VALUES('".trim(addslashes(htmlentities(strtoupper($_POST['descricao']))))."', '".$_POST['tipo']."', '".$_POST['estorno']."', '".$_POST['customedio']."', '".$_POST['custoatual']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");
			if ($insere) $resultado = "Registro: ".mysql_insert_id().", inserido com sucesso!";
			else $resultado = "Erro ao cadastrar registro!";			
		}
		else $resultado = "Registro Duplicado!";
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