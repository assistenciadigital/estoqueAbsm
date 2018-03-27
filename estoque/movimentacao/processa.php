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
if ($_GET['acao'] == 'entrada_movimento_transferencia'){	
	
	if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO
	if (!(mysql_num_rows (mysql_query ("SELECT movimento_id FROM movimento WHERE movimento_origem = '1' and numero_documento = '".$_POST['documento']."' and data_documento = '".data_date($_POST['datadocumento'])."'")))) {
	$insere = mysql_query ("INSERT movimento(movimento_origem, movimento_destino, numero_documento, data_documento, data, usuario_login) VALUES('1', '".$_POST['destino']."', '".$_POST['documento']."', '".data_date($_POST['datadocumento'])."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");
	$insert_id = mysql_insert_id();
	$form    = 'continua_saida_por_transferencia.php?movimento_id='.$insert_id;

	if ($insere) $resultado = "Registro: ".$insert_id.", inserido com sucesso!";
		else $resultado = "Erro ao cadastrar registro! Saida por Transferencia.";			
		}
		else{ $resultado = "Registro Duplicado! Destino, Documento e Data.";
		      
			  $form = 'saida_por_transferencia.php';
		}
		      
	}
	else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	}
	
	// ### FIM ENTRADA MOVIMENTO POR TRANSFERENCIA ### //

if ($_GET['acao'] == 'entrada_movimento_nf'){	
	
	if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO
	if (!(mysql_num_rows (mysql_query ("SELECT movimento_id FROM movimento WHERE movimento_origem = '".$_POST['fornecedor']."' and numero_documento = '".$_POST['documento']."' and data_documento = '".data_date($_POST['datadocumento'])."' and valor_documento = '".formata_numero($_POST['valordocumento'],10,0,"valor")."'")))) {
	$insere = mysql_query ("INSERT movimento(movimento_origem, movimento_destino, numero_documento, data_documento, valor_documento, data, usuario_login) VALUES('".$_POST['fornecedor']."', '1', '".$_POST['documento']."', '".data_date($_POST['datadocumento'])."', '".formata_numero($_POST['valordocumento'],10,0,"valor")."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");
	$insert_id = mysql_insert_id();
	$form    = 'continua_entrada_por_nf.php?movimento_id='.$insert_id;

	if ($insere) $resultado = "Registro: ".$insert_id.", inserido com sucesso!";
		else $resultado = "Erro ao cadastrar registro! Entrada Por Nota Fiscal.";			
		}
		else{ $resultado = "Registro Duplicado! Fornecedor, Documento, Data e Valor.";
		      
			  $form = 'entrada_por_nf.php';
		}
		      
	}
	else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	}
	
	// ### FIM ENTRADA POR NOTA FISCAL ### //

	if ($_GET['acao'] == 'ajuste_entrada'){	
	$tipo_id =  1 ; // AJUSTE DE ENTRADA
	$tipo_mv = 'E'; // E
	$form    = 'ajuste_entrada.php';
	
	if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO
	
	$estoque_anterior = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque FROM produto WHERE produto_id = '".$_POST['produto']."'"));
	
	$insere = mysql_query ("INSERT historicomovimento(produto_id, local_id, historicomovimento_data, tipomovimento_id, tipomovimento_tipo, historicomovimento_documento, historicomovimento_historico, historicomovimento_quantidade, historicomovimento_estoque_anterior, data, usuario_login) VALUES('".$_POST['produto']."', '".$_POST['local']."', '".date('Y-m-d H:i:s')."', '".$tipo_id."', '".$tipo_mv."', '".$_POST['documento']."', '".$_POST['historico']."', '".$_POST['quantidade']."', '".$estoque_anterior['produto_estoque']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");

	$insert_id = mysql_insert_id();
	
	$estoque = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque, produto_custoatual, produto_customedio, produto_acumulado_entrada_qtde, produto_acumulado_entrada_valor, produto_acumulado_saida_qtde FROM produto WHERE produto_id = '".$_POST['produto']."'"));	
				
	$estoque_atual = $estoque['produto_estoque'] ;
	$estoque_atual = $estoque_atual + $_POST['quantidade'];
	
	$altera = mysql_query("UPDATE produto SET produto_estoque='".$estoque_atual."', produto_data_atualiza='".date('Y-m-d H:i:s')."', usuario_atualiza='".$_SESSION['login']."' where produto_id='".$_POST['produto']."'");
	
	// ESTOQUE LOCAL
	
    if (!(mysql_num_rows (mysql_query ("SELECT local_id, produto_id FROM estoquelocal WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'")))) { //VERIFICA DUPLICIDADE
		$estoquelocal = mysql_query("INSERT estoquelocal(local_id, produto_id, produto_estoque, produto_custoatual, produto_customedio, produto_acumulado_entrada_qtde, produto_acumulado_entrada_valor, produto_acumulado_saida_qtde, usuario_login, data) values ('".$_POST['local']."', '".$_POST['produto']."', '".$estoque_atual."', '".$estoque['produto_custoatual']."', '".$estoque['produto_customedio']."', '".$estoque['produto_acumulado_entrada_qtde']."', '".$estoque['produto_acumulado_entrada_valor']."', '".$estoque['produto_acumulado_saida_qtde']."', '".$_SESSION['login']."', '".date('Y-m-d H:i:s')."')");
	}else{	
	$qtde_estoquelocal = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque FROM estoquelocal WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'"));	
				
	$estoque_do_local = $qtde_estoquelocal['produto_estoque'] ;
	$estoque_do_local = $estoque_do_local + $_POST['quantidade'];	
	    
		$estoquelocal = mysql_query("UPDATE estoquelocal SET local_id='".$_POST['local']."', produto_id='".$_POST['produto']."', produto_estoque='".$estoque_do_local."', produto_custoatual='".$estoque['produto_custoatual']."', produto_customedio='".$estoque['produto_customedio']."', produto_acumulado_entrada_qtde='".$estoque['produto_acumulado_entrada_qtde']."', produto_acumulado_entrada_valor='".$estoque['produto_acumulado_entrada_valor']."', produto_acumulado_saida_qtde='".$estoque['produto_acumulado_saida_qtde']."', usuario_login='".$_SESSION['login']."', data='".date('Y-m-d H:i:s')."' WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'");
	}
			
			if ($insere) $resultado = "Registro: ".$insert_id.", inserido com sucesso!";
			else $resultado = "Erro ao cadastrar registro! Ajuste de Entrada.";			
	}
	else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	}
	
	// ### FIM AJUSTE DE ENTRADA ### //
	
	if ($_GET['acao'] == 'entrada_por_nf'){	
	$tipo_id =  7 ; // CONTINUA ENTRADA POR NOTA FISCAL
	$tipo_mv = 'E'; // E
	$form    = 'continua_entrada_por_nf.php?movimento_id='.$_POST['movimento_id'];
	
	if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO
	
	$estoque_anterior = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque FROM produto WHERE produto_id = '".$_POST['produto']."'"));
	
	$insere = mysql_query ("INSERT historicomovimento(produto_id, local_id, historicomovimento_data, movimento_id, tipomovimento_id, tipomovimento_tipo, historicomovimento_documento, historicomovimento_historico, historicomovimento_quantidade, historicomovimento_estoque_anterior, historicomovimento_valorunitario, historicomovimento_percentualcusto, historicomovimento_valorcusto, data, usuario_login) VALUES('".$_POST['produto']."', '".$_POST['local']."', '".date('Y-m-d H:i:s')."', '".$_POST['movimento_id']."', '".$tipo_id."', '".$tipo_mv."', '".$_POST['documento']."', '".$_POST['historico']."', '".$_POST['quantidade']."', '".$estoque_anterior['produto_estoque']."', '".$_POST['produto_preco']."', '".$_POST['percentualcusto']."', '".$_POST['preco_custoatual']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");

	$insert_id = mysql_insert_id();
	
	$estoque = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque, produto_custoatual, produto_customedio, produto_acumulado_entrada_qtde, produto_acumulado_entrada_valor, produto_acumulado_saida_qtde FROM produto WHERE produto_id = '".$_POST['produto']."'"));	
				
	$estoque_atual = $estoque['produto_estoque'] ;
	$estoque_atual = $estoque_atual + $_POST['quantidade'];
	
	$altera = mysql_query("UPDATE produto SET produto_estoque='".$estoque_atual."', produto_percentualcustoatual='".$_POST['percentualcusto']."', produto_custoatual='".$_POST['preco_custoatual']."', produto_preco='".$_POST['produto_preco']."', produto_data_atualiza='".date('Y-m-d H:i:s')."', usuario_atualiza='".$_SESSION['login']."' where produto_id='".$_POST['produto']."'");
	
	// ESTOQUE LOCAL
	
    if (!(mysql_num_rows (mysql_query ("SELECT local_id, produto_id FROM estoquelocal WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'")))) { //VERIFICA DUPLICIDADE
		$estoquelocal = mysql_query("INSERT estoquelocal(local_id, produto_id, produto_estoque, produto_custoatual, produto_customedio, produto_acumulado_entrada_qtde, produto_acumulado_entrada_valor, produto_acumulado_saida_qtde, usuario_login, data) values ('".$_POST['local']."', '".$_POST['produto']."', '".$estoque_atual."', '".$estoque['produto_custoatual']."', '".$estoque['produto_customedio']."', '".$estoque['produto_acumulado_entrada_qtde']."', '".$estoque['produto_acumulado_entrada_valor']."', '".$estoque['produto_acumulado_saida_qtde']."', '".$_SESSION['login']."', '".date('Y-m-d H:i:s')."')");
	}else{	
	$qtde_estoquelocal = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque FROM estoquelocal WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'"));	
				
	$estoque_do_local = $qtde_estoquelocal['produto_estoque'] ;
	$estoque_do_local = $estoque_do_local + $_POST['quantidade'];	
	    
		$estoquelocal = mysql_query("UPDATE estoquelocal SET local_id='".$_POST['local']."', produto_id='".$_POST['produto']."', produto_estoque='".$estoque_do_local."', produto_custoatual='".$estoque['produto_custoatual']."', produto_customedio='".$estoque['produto_customedio']."', produto_acumulado_entrada_qtde='".$estoque['produto_acumulado_entrada_qtde']."', produto_acumulado_entrada_valor='".$estoque['produto_acumulado_entrada_valor']."', produto_acumulado_saida_qtde='".$estoque['produto_acumulado_saida_qtde']."', usuario_login='".$_SESSION['login']."', data='".date('Y-m-d H:i:s')."' WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'");
	}
			
			if ($insere) $resultado = "Registro: ".$insert_id.", inserido com sucesso!";
			else $resultado = "Erro ao cadastrar registro! Continua Entrada Por Nota Fiscal.";			
	}
	else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	}	
	
	// ### FIM CONTINUA ENTRADA POR NOTA FISCAL ### //
	
	else if ($_GET['acao'] == 'ajuste_saida'){	
	$tipo_id =  2 ; // AJUSTE DE SAIDA
	$tipo_mv = 'S'; // S
	$form    = 'ajuste_saida.php';
	
	if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO	
	
		$estoque_anterior = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque FROM produto WHERE produto_id = '".$_POST['produto']."'"));

	$insere = mysql_query ("INSERT historicomovimento(produto_id, local_id, historicomovimento_data, tipomovimento_id, tipomovimento_tipo, historicomovimento_documento, historicomovimento_historico, historicomovimento_quantidade, historicomovimento_estoque_anterior, data, usuario_login) VALUES('".$_POST['produto']."', '".$_POST['local']."', '".date('Y-m-d H:i:s')."', '".$tipo_id."', '".$tipo_mv."', '".$_POST['documento']."', '".$_POST['historico']."', '".$_POST['quantidade']."', '".$estoque_anterior['produto_estoque']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");

	$insert_id = mysql_insert_id();
	
	$estoque = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque, produto_custoatual, produto_customedio, produto_acumulado_entrada_qtde, produto_acumulado_entrada_valor, produto_acumulado_saida_qtde FROM produto WHERE produto_id = '".$_POST['produto']."'"));	
				
	$estoque_atual = $estoque['produto_estoque'] ;
	$estoque_atual = $estoque_atual - $_POST['quantidade'];
	
	$altera = mysql_query("UPDATE produto SET produto_estoque='".$estoque_atual."', produto_data_atualiza='".date('Y-m-d H:i:s')."', usuario_atualiza='".$_SESSION['login']."' where produto_id='".$_POST['produto']."'");
	
	// ESTOQUE LOCAL
	
 if (!(mysql_num_rows (mysql_query ("SELECT local_id, produto_id FROM estoquelocal WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'")))) { //VERIFICA DUPLICIDADE
		$estoquelocal = mysql_query("INSERT estoquelocal(local_id, produto_id, produto_estoque, produto_custoatual, produto_customedio, produto_acumulado_entrada_qtde, produto_acumulado_entrada_valor, produto_acumulado_saida_qtde, usuario_login, data) values ('".$_POST['local']."', '".$_POST['produto']."', '".$estoque_atual."', '".$estoque['produto_custoatual']."', '".$estoque['produto_customedio']."', '".$estoque['produto_acumulado_entrada_qtde']."', '".$estoque['produto_acumulado_entrada_valor']."', '".$estoque['produto_acumulado_saida_qtde']."', '".$_SESSION['login']."', '".date('Y-m-d H:i:s')."')");
	}else{	
	$qtde_estoquelocal = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque FROM estoquelocal WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'"));	
				
	$estoque_do_local = $qtde_estoquelocal['produto_estoque'] ;
	$estoque_do_local = $estoque_do_local - $_POST['quantidade'];	
	    
		$estoquelocal = mysql_query("UPDATE estoquelocal SET local_id='".$_POST['local']."', produto_id='".$_POST['produto']."', produto_estoque='".$estoque_do_local."', produto_custoatual='".$estoque['produto_custoatual']."', produto_customedio='".$estoque['produto_customedio']."', produto_acumulado_entrada_qtde='".$estoque['produto_acumulado_entrada_qtde']."', produto_acumulado_entrada_valor='".$estoque['produto_acumulado_entrada_valor']."', produto_acumulado_saida_qtde='".$estoque['produto_acumulado_saida_qtde']."', usuario_login='".$_SESSION['login']."', data='".date('Y-m-d H:i:s')."' WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'");
	}
			
			if ($insere) $resultado = "Registro: ".$insert_id.", inserido com sucesso!";
			else $resultado = "Erro ao cadastrar registro! Ajuste de Saida.";			
	}
	else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	}
	// ### FIM AJUSTE DE SAIDA ### //

	else if ($_GET['acao'] == 'saida_por_transferencia'){	
	$tipo_id =  15 ; // SAIDA POR TRANSFERENCIA - DISPENSACAO
	$tipo_mv = 'S'; // S
	$form    = 'continua_saida_por_transferencia.php?movimento_id='.$_POST['movimento_id'];
	
	if(($_SESSION['nivel'])<=2){ //TESTA NIVEL DO USUARIO LOGADO	
	
		$estoque_anterior = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque, produto_preco FROM produto WHERE produto_id = '".$_POST['produto']."'"));

	$insere = mysql_query ("INSERT historicomovimento(produto_id, local_id, historicomovimento_data, movimento_id, tipomovimento_id, tipomovimento_tipo, historicomovimento_documento, historicomovimento_historico, historicomovimento_quantidade, historicomovimento_estoque_anterior, historicomovimento_valorunitario, data, usuario_login) VALUES('".$_POST['produto']."', '".$_POST['destino']."', '".date('Y-m-d H:i:s')."', '".$_POST['movimento_id']."', '".$tipo_id."', '".$tipo_mv."', '".$_POST['documento']."', '".$_POST['historico']."', '".$_POST['quantidade']."', '".$estoque_anterior['produto_estoque']."', '".$estoque_anterior['produto_preco']."', '".date('Y-m-d H:i:s')."', '".$_SESSION['login']."')");

	$insert_id = mysql_insert_id();
	
	$estoque = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque, produto_custoatual, produto_customedio, produto_acumulado_entrada_qtde, produto_acumulado_entrada_valor, produto_acumulado_saida_qtde FROM produto WHERE produto_id = '".$_POST['produto']."'"));	
				
	$estoque_atual = $estoque['produto_estoque'] ;
	$estoque_atual = $estoque_atual - $_POST['quantidade'];
	
	$altera = mysql_query("UPDATE produto SET produto_estoque='".$estoque_atual."', produto_data_atualiza='".date('Y-m-d H:i:s')."', usuario_atualiza='".$_SESSION['login']."' where produto_id='".$_POST['produto']."'");
	
	// ESTOQUE LOCAL	

     if (!(mysql_num_rows (mysql_query ("SELECT local_id, produto_id FROM estoquelocal WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'")))) { //VERIFICA DUPLICIDADE
		$estoquelocal = mysql_query("INSERT estoquelocal(local_id, produto_id, produto_estoque, produto_custoatual, produto_customedio, produto_acumulado_entrada_qtde, produto_acumulado_entrada_valor, produto_acumulado_saida_qtde, usuario_login, data) values ('".$_POST['local']."', '".$_POST['produto']."', '".$_POST['quantidade']."', '".$estoque['produto_custoatual']."', '".$estoque['produto_customedio']."', '".$estoque['produto_acumulado_entrada_qtde']."', '".$estoque['produto_acumulado_entrada_valor']."', '".$estoque['produto_acumulado_saida_qtde']."', '".$_SESSION['login']."', '".date('Y-m-d H:i:s')."')");
	}else{	
	$qtde_estoquelocal = @mysql_fetch_assoc(mysql_query("SELECT produto_estoque FROM estoquelocal WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'"));	
				
	$estoque_do_local = $qtde_estoquelocal['produto_estoque'] ;
	$estoque_do_local = $estoque_do_local + $_POST['quantidade'];	
	    
		$estoquelocal = mysql_query("UPDATE estoquelocal SET local_id='".$_POST['local']."', produto_id='".$_POST['produto']."', produto_estoque='".$estoque_do_local."', produto_custoatual='".$estoque['produto_custoatual']."', produto_customedio='".$estoque['produto_customedio']."', produto_acumulado_entrada_qtde='".$estoque['produto_acumulado_entrada_qtde']."', produto_acumulado_entrada_valor='".$estoque['produto_acumulado_entrada_valor']."', produto_acumulado_saida_qtde='".$estoque['produto_acumulado_saida_qtde']."', usuario_login='".$_SESSION['login']."', data='".date('Y-m-d H:i:s')."' WHERE local_id='".$_POST['local']."' and produto_id='".$_POST['produto']."'");
	}
			
			if ($insere) $resultado = "Registro: ".$insert_id.", inserido com sucesso!";
			else $resultado = "Erro ao cadastrar registro! Saida Por Transferencia.";			
	}
	else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	}
	
	// ### FIM SAIDA POR TRANSFERENCIA - DISPENSACAO ### //
	
	if (!$url_de_destino) $url_de_destino = $form;
	include "../../includes/redireciona.php";
	
}else{
	$url_de_destino = "../../login.php";
	include "../../includes/redireciona.php";
}
?>