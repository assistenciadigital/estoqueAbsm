<?php
session_start();
if(($_SESSION['login']) AND($_SESSION['nivel'])){
	
	include "../../painel/funcoesPHP/conexao.php";
	
	$c = @mysql_fetch_assoc(mysql_query("SELECT * FROM auxiliar WHERE id='".$_GET['id']."'"));

	if ($_GET['id']) $titulo_pg = "GERANDOR DE SENHA DO PAINEL DE ATENDIMENTO ";
	else $titulo_pg = "GERADOR DE SENHA DO PAINEL DE ATENDIMENTO";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../painel/css/estilos.css" rel="stylesheet" type="text/css">
<link href="../../painel/css/calendario_marron.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../painel/funcoesJS/funcoes.js"></script>
<script type="text/javascript" src="../../painel/funcoesJS/jquery-1.9.1.js"></script>

<script type="text/Javascript">
function send(action) {
	switch(action) {
		case 'npa':
			url = 'processando.php?acao=npa';
			break;
		case 'namb':
			url = 'processando.php?acao=namb';
			break;
		case 'nodonto':
			url = 'processando.php?acao=nodonto';
			break;
		case 'nlab':
			url = 'processando.php?acao=nlab';
			break;
		case 'ppa':
			url = 'processando.php?acao=ppa';
			break;
		case 'pamb':
			url = 'processando.php?acao=pamb';
			break;
		case 'podonto':
			url = 'processando.php?acao=podonto';
			break;
		case 'plab':
			url = 'processando.php?acao=plab';
			break;
	}
		document.forms[0].action = url;
		document.forms[0].submit();
}
</script>
</head>
<body>
<div align="center">
<table width="800" border="0" align="center" cellpadding="0" cellspacing="2" id="conteudo">
<tr>
<td width="10%" height="25" align="center" valign="middle" bgcolor="#4DBDCB"><img src="icon.png"></td>
<td width="90%" style="cursor:hand" align="left" bgcolor="#4DBDCB"><h2 class="txtBranco"><strong><?php if ($_GET['id']) echo $titulo_pg." ID: ".$_GET['id']; else echo $titulo_pg?></strong></h2></td>
</tr>
<tr>
<td colspan="2" align="center" valign="top" height="400">
<form method="post" action="processando.php">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10">
  <tr>
    <th align="center" valign="middle" scope="col">NORMAL<?php echo $_GET['acao']?></th>
    <th align="center" valign="middle" scope="col">PREFERÊNCIAL</th>
  </tr>
  <tr>
    <td align="center" valign="middle"><input type="submit" name="npa" onclick="send('npa');" style="height:50px; width:300px; cursor:pointer" value="PA - PRONTO ATENDIMENTO" id="npa"/></td>
    <td align="center" valign="middle"><input type="submit" name="ppa" onclick="send('ppa');" style="height:50px; width:300px; cursor:pointer" value="PA - PRONTO ATENDIMENTO" id="ppa"/></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><input type="submit" name="namb" onclick="send('namb');" style="height:50px; width:300px; cursor:pointer" value="AMBULATORIO" id="namb"/></td>
    <td align="center" valign="middle"><input type="submit" name="pamb" onclick="send('pamb');" style="height:50px; width:300px; cursor:pointer" value="AMBULATORIO" id="pamb"/></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><input type="submit" name="nodonto" onclick="send('nodonto');" style="height:50px; width:300px; cursor:pointer" value="ODONTOLOGIA" id="nodonto"/></td>
    <td align="center" valign="middle"><input type="submit" name="podonto" onclick="send('podonto');" style="height:50px; width:300px; cursor:pointer" value="ODONTOLOGIA" id="podonto"/></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><input type="submit" name="nlab" onclick="send('nlab');" style="height:50px; width:300px; cursor:pointer" value="LABORATORIO" id="nlab"/></td>
    <td align="center" valign="middle"><input type="submit" name="plab" onclick="send('plab');" style="height:50px; width:300px; cursor:pointer" value="LABORATORIO" id="plab"/></td>
  </tr>
  </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th align="right" scope="col"><img src="../../painel/imgs/space.png" width="10" height="25"><a href="lista.php"><img src="../../painel/imgs/bt_voltar.png" border="0"></a><img src="../../painel/imgs/space.png" width="10" height="25"><img src="../../painel/imgs/spaceT.png" width="20" height="25">
</th>
  </tr>
</table>
</form>  
</td>
</tr>
</table>
</div>
</body>
</html>
<?php }
else {
	$url_de_destino = "../../expira.php";
	$target = "_parent";
	include "../../painel/funcoesPHP/redireciona.php";
}
?>