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
//------------------------------------->> INCLUIR
if ($_GET['acao'] == 'finaliza'){	

if(($_SESSION['nivel_usuario'])<=2){
			$altera = mysql_query("UPDATE chamasenha SET chamado='".date('Y-m-d H:i:s')."', finalizado='".date('Y-m-d H:i:s')."', status='Ausente', data='".date('Y-m-d H:i:s')."', usuario='".$_SESSION['login']."' where id='".$_POST['id']."'");
			if($altera) $resultado = "Registro: ".$_POST['id'].", alterado com sucesso!";
			else $resultado = "Erro ao alterar o registro: ".$_POST['id'].".";

	}
	else $resultado = "Você não tem permissão para alterar o registro ".$_POST['id'].", contacte o administrador do sistema!";
	}

switch($_GET['acao']) {
		case 'npa':
			$tipo ='N';
			$setor = 'PA';
			break;
		case 'namb':
			$tipo ='N';
			$setor = 'Ambulatorio';
			break;
		case 'nodonto':
			$tipo ='N';
			$setor = 'Odontologia';
			break;
		case 'nlab':
			$tipo ='N';
			$setor = 'Laboratorio';
			break;
		case 'ppa':
			$tipo ='P';
			$setor = 'PA';
			break;
		case 'pamb':
			$tipo ='P';
			$setor = 'Ambulatorio';
			break;
		case 'podonto':
			$tipo ='P';
			$setor = 'Odontologia';
			break;
		case 'plab':
			$tipo ='P';
			$setor = 'Laboratorio';
			break;
}
		//$_GET['acao']
		if(($_SESSION['nivel_usuario'])<=2){
		
			$insere = mysql_query ("INSERT chamasenha(tipo, setor, sala, profissional, gravado, status, data, usuario) VALUES('".$tipo."', '".$setor."', '".$_POST['sala']."', '".$_POST['profissional']."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 'Aguardando', '".$_SESSION['login']."')");
		$id = mysql_insert_id();
			if ($insere) $resultado = "Registro  ".$id." cadastrado com sucesso!";
			else $resultado = "Erro ao cadastrar registro!";
		}
		else $resultado = "Você não tem permissão para incluír resgistro, contacte o administrador do sistema!";
	
	if (!$url_de_destino) $url_de_destino = "cadastro.php";
	include'../../painel/funcoesPHP/redireciona.php';
	
}else {
	$url_de_destino = "../../expira.php";
	$target = "_parent";
	include "../../painel/funcoesPHP/redireciona.php";
}
?>