<?php 
session_start();

if($_SESSION['acesso_compras']!="S"){
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

if ($_GET['acao'] == 'pedido'){	
	
	if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO
	if (!(mysql_num_rows (mysql_query ("SELECT id FROM pedido WHERE fornecedor = '".$_POST['fornecedor']."' and data_pedido = '".data_date($_POST['datapedido'])."' and status<>'FECHADO'")))) {
	$insere = mysql_query ("INSERT pedido(data_pedido, status, fornecedor, usuario_login, data) VALUES('".data_date($_POST['datapedido'])."', 'ABERTO', '".$_POST['fornecedor']."', '".$_SESSION['login']."', '".date('Y-m-d H:i:s')."')");
	$insert_id = mysql_insert_id();
	$form    = 'continua_pedido.php?pedido_id='.$insert_id;

	if ($insere) $resultado = "Registro: ".$insert_id.", inserido com sucesso!";
		else $resultado = "Erro ao cadastrar registro! Entrada Pedido.";			
		}
		else{ $resultado = "Registro Duplicado! Fornecedor, Data e Status.";
		      
			  $form = 'pedido.php';
		}
		      
	}
	else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	}
	
	// ### FIM ENTRADA PEDIDO ### //

	if ($_GET['acao'] == 'continua_pedido'){	
	$form    = 'continua_pedido.php?pedido_id='.$_POST['pedido_id'];
	
	if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO
		
	$insere = mysql_query ("INSERT historicopedido(produto, local, data_pedido, pedido, historico, quantidade, valorunitario, percentualcusto, valorcusto, data, usuario_login) VALUES('".$_POST['produto']."', '".$_POST['local']."', '".date('Y-m-d H:i:s')."', '".$_POST['pedido_id']."', '".$_POST['historico']."', '".$_POST['quantidade']."', '".$_POST['produto_preco']."', '".$_POST['percentualcusto']."', '".$_POST['preco_custoatual']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");

	if ($insere) $resultado = "Registro: ".mysql_insert_id().", inserido com sucesso!";
		else $resultado = "Erro ao cadastrar registro! Continua Entrada Pedido.";			
	}
	else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	}	
	
	if (!$url_de_destino) $url_de_destino = $form;
	include "../../includes/redireciona.php";
	
}else{
	$url_de_destino = "../../login.php";
	include "../../includes/redireciona.php";
}
?>