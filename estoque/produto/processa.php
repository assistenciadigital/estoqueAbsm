<?php 
session_start();

if($_SESSION['acesso_estoque']!="S"){
$resultado = "Sem Permissão de Acesso! Contacte o Administrador do Sistema.";
$url_de_destino = "../../index.php";
include "../../includes/redireciona.php";	
}

if(($_SESSION['login']) AND ($_SESSION['nivel'])){
	include "../../includes/conect.php";
	include "../../includes/data.php";
	include "../../includes/ajustadataehora.php";
	include "../../includes/converte_valor.php";
		
    $select = @mysql_fetch_assoc(mysql_query("SELECT produto_id, produto_descricao FROM produto where produto_id = '".$_POST['id']."'"));
	
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
			mysql_query ("DELETE FROM produto WHERE produto_id='".$_GET['id']."'");
			$sucesso = mysql_affected_rows();
			if ($sucesso) $resultado = "Registro: ".$_GET['id'].", excluido com sucesso!";
			else $resultado = "Erro na exclusão do registro: ".$_GET['id'];
	}
	else $resultado = "Você não tem permissão para excluír o registro: ".$_GET['id'].", Contacte o Administrador do Sistema!";	
	}
//------------------------------------->> ALTERAR	
	else if ($_GET['acao'] == 'alteracao'){	//SE FOR ALTERACAO
	if(($_SESSION['nivel'])<=2){
	//if (!(mysql_num_rows (mysql_query ("SELECT produto_id FROM produto WHERE produto_codigobarras = '".$_POST['codigobarras']."' and produto_descricao='".$_POST['descricao']."' and produto_generico = '".$_POST['generico']."' and produto_preco = '".$_POST['precoatual']."' and produto_custoatual = '".$_POST['custoatual']."'")))) { //VERIFICA DUPLICIDADE
	
			$altera = mysql_query("UPDATE produto SET produto_descricao='".trim(addslashes(htmlentities(strtoupper($_POST['descricao']))))."', produto_generico='".trim(addslashes(htmlentities(strtoupper($_POST['generico']))))."', produto_codigobarras='".$_POST['codigobarras']."', grupo_id='".$_POST['grupo']."', depto_id='".$_POST['depto']."', unidade_id='".$_POST['unidade']."', produto_peso='".$_POST['peso']."', produto_preco='".($_POST['precoatual'])."', produto_percentualcustoatual='".$_POST['percentualcustoatual']."', produto_custoatual='".($_POST['custoatual'])."', produto_estoqueminimo='".$_POST['estoqueminimo']."', produto_estoquemaximo='".$_POST['estoquemaximo']."', produto_lote='".strtoupper($_POST['lote'])."', produto_fabricacao='".$_POST['fabricacao']."', produto_validade='".$_POST['validade']."', forma_id='".$_POST['forma']."', fabricante_id='".$_POST['fabricante']."',  produto_data_atualiza='".date('Y-m-d H:i:s')."', usuario_atualiza='".$_SESSION['login']."', data='".date('Y-m-d H:i:s')."', usuario_login='".$_SESSION['login']."' where produto_id='".$_POST['id']."'");
			if($altera) $resultado = "Registro: ".$_POST['id'].", alterado com sucesso!";
			else $resultado = "Erro ao alterar o registro: ".$_POST['id'].".";
	//}
	//else $resultado = "Registro Duplicado!";
	}
	else $resultado = "Você não tem permissão para alterar o registro: ".$_POST['id'].", contacte o administrador do sistema!";
	}

//------------------------------------->> INCLUIR
	else {
		if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO		
		if (!(mysql_num_rows (mysql_query ("SELECT produto_id FROM produto WHERE produto_lote = '".$_POST['lote']."' and produto_fabricacao = '".$_POST['fabricacao']."' and produto_validade = '".$_POST['validade']."'")))) { // VERFICA DUPLICIDADE
		
			$insere = mysql_query ("INSERT produto(produto_descricao, produto_generico, produto_codigobarras, grupo_id, depto_id, unidade_id, produto_peso, produto_preco, produto_percentualcustoatual, produto_custoatual, produto_estoqueminimo, produto_estoquemaximo, produto_lote, produto_fabricacao, produto_validade, forma_id, fabricante_id, produto_data_entrada, data, usuario_login, usuario_entrada) VALUES('".trim(addslashes(htmlentities(strtoupper($_POST['descricao']))))."', '".trim(addslashes(htmlentities(strtoupper($_POST['generico']))))."', '".$_POST['codigobarras']."', '".$_POST['grupo']."', '".$_POST['depto']."', '".$_POST['unidade']."', '".$_POST['peso']."', '".$_POST['precoatual']."', '".$_POST['percentualcustoatual']."', '".$_POST['custoatual']."', '".$_POST['estoqueminimo']."', '".$_POST['estoquemaximo']."', '".strtoupper($_POST['lote'])."', '".$_POST['fabricacao']."', '".$_POST['validade']."', '".$_POST['forma']."', '".$_POST['fabricante']."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."', '".$_SESSION['login']."')");
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