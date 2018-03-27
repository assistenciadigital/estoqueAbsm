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
		
    $select = @mysql_fetch_assoc(mysql_query("SELECT * FROM fornecedor where fornecedor_id = '".$_POST['id']."'"));
	
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
			mysql_query ("DELETE FROM fornecedor WHERE fornecedor_id ='".$_GET['id']."'");
			$sucesso = mysql_affected_rows();
			if ($sucesso) $resultado = "Registro: ".$_GET['id'].", excluido com sucesso!";
			else $resultado = "Erro na exclusão do registro: ".$_GET['id'];
	}
	else $resultado = "Você não tem permissão para excluír o registro: ".$_GET['id'].", Contacte o Administrador do Sistema!";	
	}
//------------------------------------->> ALTERAR	
	else if ($_GET['acao'] == 'alteracao'){	//SE FOR ALTERACAO
	if(($_SESSION['nivel'])<=2){
	if (!(mysql_num_rows (mysql_query ("SELECT fornecedor_id FROM fornecedor WHERE fornecedor_razaosocial = '".$_POST['NAO VERIFICA DUPLICIDADE']."'")))) { //VERIFICA DUPLICIDADE
			$altera = mysql_query("UPDATE fornecedor SET fornecedor_inscricao='".$_POST['inscricao']."', fornecedor_razaosocial='".trim(addslashes(htmlentities(strtoupper($_POST['razao']))))."', fornecedor_nomefantasia='".trim(addslashes(htmlentities(strtoupper($_POST['fantasia']))))."', fornecedor_cep='".$_POST['cep']."', fornecedor_endereco='".trim(addslashes(htmlentities(strtoupper($_POST['endereco']))))."', fornecedor_numero='".$_POST['numero']."', fornecedor_complemento='".trim(addslashes(htmlentities(strtoupper($_POST['complemento']))))."', fornecedor_bairro='".trim(addslashes(htmlentities(strtoupper($_POST['bairro']))))."', fornecedor_cidade='".$_POST['cidade']."', fornecedor_uf='".$_POST['uf']."', fornecedor_telefone='".$_POST['fone']."', fornecedor_fax='".$_POST['fax']."', fornecedor_celular='".$_POST['celular']."', fornecedor_email='".trim(addslashes(htmlentities(strtolower($_POST['email']))))."', fornecedor_responsavel='".trim(addslashes(htmlentities(strtoupper($_POST['autorizador']))))."', fornecedor_funcao='".trim(addslashes(htmlentities(strtoupper($_POST['funcao']))))."', fornecedor_depto='".trim(addslashes(htmlentities(strtoupper($_POST['setor']))))."', data='".date('Y-m-d H:i:s')."', usuario_login='".$_SESSION['login']."' where fornecedor_id ='".$_POST['id']."'");
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
		if (!(mysql_num_rows (mysql_query ("SELECT fornecedor_id FROM fornecedor WHERE fornecedor_razaosocial = '".$_POST['NAO VERIFICA DUPLICIDADE']."'")))) { // VERFICA DUPLICIDADE
			$insere = mysql_query ("INSERT fornecedor(fornecedor_inscricao, fornecedor_razaosocial, fornecedor_nomefantasia, fornecedor_cep, fornecedor_endereco, fornecedor_numero, fornecedor_complemento, fornecedor_bairro, fornecedor_cidade, fornecedor_uf, fornecedor_telefone, fornecedor_fax, fornecedor_celular, fornecedor_email, fornecedor_responsavel, fornecedor_funcao, fornecedor_depto, data, usuario_login) VALUES('".$_POST['inscricao']."', '".trim(addslashes(htmlentities(strtoupper($_POST['razao']))))."', '".trim(addslashes(htmlentities(strtoupper($_POST['fantasia']))))."', '".$_POST['cep']."', '".trim(addslashes(htmlentities(strtoupper($_POST['endereco']))))."', '".$_POST['numero']."', '".trim(addslashes(htmlentities(strtoupper($_POST['complemento']))))."', '".trim(addslashes(htmlentities(strtoupper($_POST['bairro']))))."', '".$_POST['cidade']."', '".$_POST['uf']."', '".$_POST['fone']."', '".$_POST['fax']."', '".$_POST['celular']."', '".trim(addslashes(htmlentities(strtolower($_POST['email']))))."', '".trim(addslashes(htmlentities(strtoupper($_POST['autorizador']))))."', '".trim(addslashes(htmlentities(strtoupper($_POST['funcao']))))."', '".trim(addslashes(htmlentities(strtoupper($_POST['setor']))))."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");
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