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
//------------------------------------->> STATUS ON
	if ($_GET['acao'] == 'status_on'){
		if(($_SESSION['nivel'])<=1){
			$status = mysql_query("UPDATE usuario SET usuario_ativo = 'N',  usuario_msg = 'N' WHERE usuario_login='".$_GET['id']."'");
			if($status) $resultado = "Registro: ".$_GET['id'].", alterado com sucesso -> Status = NAO!";
			else $resultado = "Erro na alteração do registro: ".$_GET['id'];
		}
		else $resultado = "Você não tem permissão para alterar o registro: ".$_GET['id'].", Contacte o Administrador do Sistema!";	
	}	
//------------------------------------->> STATUS OFF	
	else if ($_GET['acao'] == 'status_off'){		
		if(($_SESSION['nivel'])<=1){
			$status = mysql_query("UPDATE usuario SET usuario_ativo = 'S',  usuario_msg = 'S' WHERE usuario_login='".$_GET['id']."'");
			if($status) $resultado = "Registro: ".$_GET['id'].", alterado com sucesso -> Status = SIM!";
			else $resultado = "Erro na alteração do registro: ".$_GET['id'];
		}
		else $resultado = "Você não tem permissão para alterar o registro: ".$_GET['id'].", Contacte o Administrador do Sistema!";			
	}	
//------------------------------------->> EXCLUIR ON	
	else if ($_GET['acao'] == 'excluido_on'){
		if(($_SESSION['nivel'])<=1){			
			$desbloqueia = mysql_query("UPDATE usuario SET usuario_excluido = 'N' WHERE usuario_login='".$_GET['id']."'");
			if($desbloqueia) $resultado = "Registro: ".$_GET['id'].", alterado com sucesso -> Exclusao = NAO!";
			else $resultado = "Erro na alteração do registro: ".$_GET['id'];			
		}
		else $resultado = "Você não tem permissão para alterar o registro: ".$_GET['id'].", Contacte o Administrador do Sistema!";		
	}	
//------------------------------------->> EXCLUIR OFF	
	else if ($_GET['acao'] == 'excluido_off'){
		if(($_SESSION['nivel'])<=1){
			$desbloqueia = mysql_query("UPDATE usuario SET usuario_ativo = 'N', usuario_msg = 'N', usuario_excluido = 'S' WHERE usuario_login='".$_GET['id']."'");
			if($desbloqueia) $resultado = "Registro: ".$_GET['id'].", alterado com sucesso -> Exclusao = SIM!";
			else $resultado = "Erro na alteração do registro: ".$_GET['id'];
		}
		else $resultado = "Você não tem permissão para alterar o registro: ".$_GET['id'].", Contacte o Administrador do Sistema!";			
	}
//------------------------------------->> EXCLUSAO
	else if ($_GET['acao'] == 'exclusao'){	
	if(($_SESSION['nivel'])<=1){
			mysql_query ("DELETE FROM usuario WHERE usuario_login='".$_GET['id']."'");
			$sucesso = mysql_affected_rows();
			if ($sucesso) $resultado = "Registro: ".$_GET['id'].", excluido com sucesso!";
			else $resultado = "Erro na exclusão do registro: ".$_GET['id'];
	}
	else $resultado = "Você não tem permissão para excluír o registro: ".$_GET['id'].", Contacte o Administrador do Sistema!";	
	}
//------------------------------------->> ALTERAR	
	else if ($_GET['acao'] == 'alteracao'){	//SE FOR ALTERACAO
	if(($_SESSION['nivel'])<=1){
	if (!(mysql_num_rows (mysql_query ("SELECT usuario_login FROM usuario WHERE usuario_login='".$_GET['id']."'")))) { //VERIFICA DUPLICIDADE
			$altera = mysql_query("UPDATE usuario SET usuario_nome='".trim(addslashes(htmlentities(strtoupper($_POST['nome']))))."', usuario_nascimento='".data_date($_POST['nascimento'])."', usuario_sexo='".$_POST['sexo']."', usuario_email='".$_POST['email']."', usuario_telefone='".$_POST['telefone']."', usuario_celular='".$_POST['celular']."', usuario_admin='".$_POST['admin']."', usuario_adminpainel='".$_POST['adminpainel']."', usuario_cadastro='".$_POST['cadastro']."', usuario_convenio='".$_POST['convenio']."', usuario_compras='".$_POST['compras']."', usuario_estoque='".$_POST['estoque']."', usuario_financeiro='".$_POST['financeiro']."', usuario_hospital='".$_POST['hospital']."', usuario_painel='".$_POST['painel']."', usuario_nivel='".$_POST['nivel']."', data='".date('Y-m-d H:i:s')."', usuario_logado='".$_SESSION['login']."' where usuario_login='".$_POST['id']."'");
			if($altera) $resultado = "Registro: ".$_POST['id'].", alterado com sucesso!";
			else $resultado = "Erro ao alterar o registro: ".$_POST['id'].".";
	}
	else $resultado = "Registro Duplicado!";
	}
	else $resultado = "Você não tem permissão para alterar o registro: ".$_POST['id'].", contacte o administrador do sistema!";
	}
	
//------------------------------------->> ALTERAR SENHA	
	else if ($_GET['acao'] == 'senha'){	//SE FOR ALTERACAO DE SENHA
	if(($_SESSION['nivel'])<=1){
	if (!(mysql_num_rows (mysql_query ("SELECT usuario_login FROM usuario WHERE usuario_login='".$_GET['id']."'")))) { //VERIFICA DUPLICIDADE
			$altera = mysql_query("UPDATE usuario SET usuario_senha='".md5(trim(addslashes(htmlentities($_POST['senha']))))."', data='".date('Y-m-d H:i:s')."', usuario_logado='".$_SESSION['login']."' where usuario_login='".$_POST['id']."'");
			if($altera) $resultado = "Registro / Senha: ".$_POST['id'].", alterada com sucesso!";
			else $resultado = "Erro ao alterar o registro / senha: ".$_POST['id'].".";
	}
	else $resultado = "Registro Duplicado!";
	}
	else $resultado = "Você não tem permissão para alterar o registro: ".$_POST['id'].", contacte o administrador do sistema!";
	}	

//------------------------------------->> INCLUIR
	else {
		if(($_SESSION['nivel'])<=1){ //TESTA NIVEL DO USUARIO LOGADO		
		if (!(mysql_num_rows (mysql_query ("SELECT usuario_login FROM usuario WHERE usuario_login='".$_POST['descricao']."'")))) {//VERFICA DUPLICIDADE
			$insere = mysql_query ("INSERT usuario(usuario_login, usuario_senha, usuario_nome, usuario_nascimento, usuario_sexo, usuario_nivel, usuario_email, usuario_celular, usuario_telefone, usuario_ativo, usuario_msg, usuario_excluido, usuario_admin, usuario_adminpainel, usuario_cadastro, usuario_convenio, usuario_compras, usuario_estoque, usuario_financeiro, usuario_hospital, usuario_painel, data, usuario_logado) VALUES('".trim(addslashes(htmlentities($_POST['id'])))."', '".md5($_POST['senha'])."','".trim(addslashes(htmlentities(strtoupper($_POST['nome']))))."', '".data_date($_POST['nascimento'])."', '".$_POST['sexo']."', '".$_POST['nivel']."', '".$_POST['email']."', '".$_POST['celular']."', '".$_POST['telefone']."', 'S', 'S', 'N', '".$_POST['admin']."', '".$_POST['adminpainel']."', '".$_POST['cadastro']."', '".$_POST['convenio']."', '".$_POST['compras']."', '".$_POST['estoque']."', '".$_POST['financeiro']."', '".$_POST['hospital']."', '".$_POST['painel']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");
			if ($insere) $resultado = "Registro inserido com sucesso!";
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