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
	
	$estabelecimento = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));
		
	$usuario = @mysql_fetch_assoc(mysql_query("SELECT usuario_login, usuario_nome, usuario_ultimo_acesso FROM usuario WHERE usuario_login = '".$_SESSION['login']."'"));

	$data_ultimo_acesso = $usuario['usuario_ultimo_acesso'];
	$data= datetime_datatempo($data_ultimo_acesso);
	$data = str_replace ("-", "às", $data);	
	
	if ($_POST['datainicio'] and $_POST['datafim']){	
	
	$datainicio = data_date($_POST['datainicio']);
	$datafim    = data_date($_POST['datafim']);
    $datainicio = $datainicio.' 00:00:00';
	$datafim    = $datafim.' 23:59:59';

	$select = mysql_query("SELECT * FROM pedido where data_pedido between '".$datainicio."%' and '".$datafim."%' order by data_pedido ASC");	
	}else if ($_POST['local']){
	$select = mysql_query("SELECT * FROM pedido where local_id = '".$_POST['local']."' order by data_pedido ASC");	
	}else if ($_POST['id']){
	$select = mysql_query("SELECT * FROM pedido where historicomovimento_id = '".$_POST['id']."' order by data_pedido ASC");	
	}else{
	$select = mysql_query("SELECT * FROM pedido order by data_pedido ASC");
	}

	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from historicomovimento")); // Quantidade de registros pra paginação
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../../css/site.css" rel="stylesheet">
<link type="text/css" href="../../js/autocomplete/jquery-ui.css" rel="stylesheet"/>

<script type="text/javascript" src="../../js/autocomplete/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../js/autocomplete/jquery-ui.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput.min.js"></script>

<title>SisHosp</title>

<script language="javascript">
function exclui (produto_id){
	if(confirm ('Confirma a exclusão do registro: ' + produto_id + '?')) {
		location = 'processa.php?acao=exclusao&id=' + produto_id;
	}
}
function atualiza(){
 document.getElementById("frmlista").submit();	
}

function limpa() {
	document.getElementById("frmlista").reset();	
	document.getElementById("id").value = "";
	document.getElementById("datainicio").value = "";	
	document.getElementById("datafim").value = "";
	document.getElementById("fornecedor").value = "";
	document.getElementById("frmlista").submit();	
}
</script>

<script type="text/javascript">

$(document).ready(function(){
	$("input[name='datainicio']").mask('99/99/9999');
	$("input[name='datafim']").mask('99/99/9999');
}) // Fim Máscaras

var digital = new Date(); // crio um objeto date do javascript
//digital.setHours(15,59,56); // caso não queira testar com o php, comente a linha abaixo e descomente essa
digital.setHours(<?php echo date("H,i,s"); ?>); // seto a hora usando a hora do servidor
<!--
function clock() {
var hours = digital.getHours();
var minutes = digital.getMinutes();
var seconds = digital.getSeconds();
digital.setSeconds( seconds+1 ); // aquin que faz a mágica
// acrescento zero
if (minutes <= 9) minutes = "0" + minutes;
if (seconds <= 9) seconds = "0" + seconds;

dispTime = hours + ":" + minutes + ":" + seconds;
document.getElementById("clock").innerHTML = dispTime; // coloquei este div apenas para exemplo
setTimeout("clock()", 1000); // chamo a função a cada 1 segundo

}

window.onload = clock;
//-->

</script>

</head>

<body onload="updateBarcode()">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" scope="col"><div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF" align="left"><?php echo 'Usuário: '.strtoupper($usuario['usuario_login']).' | ' .strtoupper($usuario['usuario_nome']).' | Seu Último Acesso Foi Em: '.$data?></div></td>
    <td width="50%" scope="col"><div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF" align="right"><?php echo date('d/m/Y')?></div>
<div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF" align="right" id="clock"></div></td>
  </tr>
</table>
<nav id="nav01"></nav>
<div id="main">
<table width="100%" align="center">
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><div align="right">
<?php echo 'Usuário: '.strtoupper($usuario['usuario_login']).' | ' .strtoupper($usuario['usuario_nome']).' | Seu Último Acesso Foi Em: '.$data?>
</div></td>
  </tr>
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><strong>COTAÇÃO / PEDIDO DE COMPRAS</strong></td>
  </tr>
</table>

<form action="lista.php" name="frmlista" id="frmlista" method="post">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <th align="left" valign="middle" bgcolor="#CCCCCC" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'"> <strong><?php if(($_SESSION['nivel'])<=2){ ?><a href="pedido.php"><input type="button" name="novo" id="novo" value="Novo Registro..." style="cursor:pointer"/></a>
        <?php } ?>&nbsp;Fornecedor:
             <select name="fornecedor" id="fornecedor" style="width:150px; height: 20px;" onchange="atualiza();">
            <option value=""></option>
            <?php
            $select_fornecedor = mysql_query ("SELECT fornecedor_id, fornecedor_razaosocial from fornecedor ORDER BY fornecedor_razaosocial");
            if (mysql_num_rows ($select_fornecedor)) {
            while ($row_fornecedor = mysql_fetch_assoc($select_fornecedor)) {
            ?>
            <option value="<?php echo $row_fornecedor['fornecedor_id']?>"<?php if($_POST['fornecedor'] == $row_fornecedor['fornecedor_id']) echo "selected"?>><?php echo $row_fornecedor['fornecedor_razaosocial']?></option>
            <?php }}?>
          </select>
          </strong>ID:</strong>
        <select name="id" id="id" style="width:100px; height: 20px;" onchange="atualiza();">
          <option value=""></option>
          <?php
			 $select_pedido = mysql_query ("SELECT id, data_pedido from pedido ORDER BY id");
            if (mysql_num_rows ($select_pedido)) {
            while ($row_pedido = mysql_fetch_assoc($select_pedido)) {
            ?>
          <option value="<?php echo $row_pedido['historicomovimento_id']?>"<?php if($_POST['id'] == $row_pedido['id']) echo "selected"?>><?php echo $row_pedido['id']?></option>
          <?php }}?>
          </select>
        </strong>Data Inicio:&nbsp;
        <strong>&nbsp;<strong>
          <input name="datainicio" type="text" id="datainicio" value="<?php echo $_POST['datainicio']?>" size="10" maxlength="10" />
          </strong>Data Fim:&nbsp;
          <label for="datafim"></label>
          <input name="datafim" type="text" id="datafim" value="<?php echo $_POST['datafim']?>" size="10" maxlength="10" />
          <label for="datainicio"></label>
          <input name="pesquisar" type="submit" id="pesquisar" style="cursor:pointer" value="Pesquisar..."/>
          <input type="button" name="limpar" id="limpar" value="Limpar..." onclick="limpa()" style="cursor:pointer"/>
        </strong></th>
      <th width="20%" align="right" valign="middle" bgcolor="#CCCCCC" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'"<?php if(($_SESSION['nivel'])<=2){ ?><?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?><a href="../../estoque/produto/lista.php" target="_self"><input type="button" name="produto" id="produto" value="Produto" style="cursor:pointer"/></a>&nbsp;<a href="relatorio.php?id=<?php echo $_POST['id']?>&amp;depto=<?php echo $_POST['depto']?>&amp;grupo=<?php echo $_POST['grupo']?>&amp;descricao=<?php echo $_POST['descricao']?>" target="_blank"><input type="button" name="verpdf" id="verpdf" value="Vêr PDF" style="cursor:pointer"/></a><?php } ?></th>
      </tr>
  </table>

  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr align="left" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="5%" align="center" bgcolor="#00FFFF" scope="col"><strong>Id</strong></td>
      <td width="35%"  bgcolor="#00FFFF" scope="col"><strong>Produto</strong></td>
      <td width="10%"  bgcolor="#00FFFF" scope="col"><strong>Local</strong></td>
      <td width="5%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Data</strong></td>
      <td width="5%" align="center" valign="middle"  bgcolor="#00FFFF" scope="col"><strong>Docto</strong></td>
      <td width="5%" align="center" valign="middle"  bgcolor="#00FFFF" scope="col"><strong>Tipo</strong></td>
      <td width="5%" align="right"  bgcolor="#00FFFF" scope="col"><strong>Qtde</strong></td>
      <td width="10%" align="right"  bgcolor="#00FFFF" scope="col"><strong>Valor Unitario</strong></td>
      <td width="10%" align="right"  bgcolor="#00FFFF" scope="col"><strong>Valor Total</strong></td>
      <td width="5%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Estorno</strong></td>
      <td width="5%" align="right"  bgcolor="#00FFFF" scope="col"><strong>Estoque Anterior</strong></td>
    </tr>
  </table>
  
  <?php 
  //<div style="color:#009; width:100%; height:303; overflow: auto; vertical-align: left;">   
  if (mysql_num_rows ($select)) {
	  while ($row = mysql_fetch_assoc($select)) {
  	        $i++;
    		if ($i%2) $cor = '#F0F0F0';
				else $cor = '#FFFFFF';
$select_produto=@mysql_fetch_assoc(mysql_query("SELECT * FROM produto WHERE produto_id='".$row['produto_id']."'"));
$select_localestoque=@mysql_fetch_assoc(mysql_query("SELECT * FROM localestoque WHERE local_id='".$row['local_id']."'"));
  ?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['historicomovimento_id']?></td>
      <td width="35%" align="left"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $select_produto['produto_descricao'].'<br/>L.: '.$select_produto['produto_lote'].' Fab.: '.$select_produto['produto_fabricacao'].' Val.: <strong>'.$select_produto['produto_validade'].'</strong>'?></td>
      <td width="10%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $select_localestoque['local_descricao']?></td>
      <td width="5%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo datetime_datatempo($row['historicomovimento_data'])?></td>
      <td width="5%" align="center" valign="middle"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['historicomovimento_documento']?></td>
      <td width="5%" align="center" valign="middle"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['tipomovimento_tipo']?></td>
      <td width="5%" align="right"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php if ($row['tipomovimento_tipo']=='E'){ echo number_format($row['historicomovimento_quantidade'], 0, ',', '.'); }else{ echo number_format($row['historicomovimento_quantidade'] * -1, 0, ',', '.');}?></td>
      <td width="10%" align="right"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php if ($row['tipomovimento_tipo']=='E'){ echo number_format($select_produto['produto_custoatual'], 2, ',', '.'); }else{ echo number_format($select_produto['produto_preco'], 2, ',', '.');}?></td>
      <td width="10%" align="right" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if($row['historicomovimento_valortotal']==0) echo number_format($row['historicomovimento_quantidade'] * $select_produto['produto_custoatual'], 2, ',', '.'); else echo number_format($row['historicomovimento_valortotal'], 2, ',', '.');?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php echo $row['historicomovimento_estorno']?></td>
      <td width="5%" align="right" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php echo number_format($row['historicomovimento_estoque_anterior'], 0, ',', '.')?></td>
    </tr>
  </table>

  <?php
    if ($row['tipomovimento_tipo']=='E'){
		$soma_qtde = $soma_qtde + $row['historicomovimento_quantidade'];
		$soma_valorunitario = $soma_valorunitario + $select_produto['produto_custoatual'];
		}else{
		$soma_qtde = $soma_qtde + ($row['historicomovimento_quantidade']*-1);
		$soma_valorunitario = $soma_valorunitario + $select_produto['produto_preco'];
		}
	
		//$soma_valorunitario = $soma_valorunitario + $row['historicomovimento_valorunitario'];
		//$soma_valortotal    = $soma_valortotal + $row['historicomovimento_valortotal'];
	
   }}?>
    </form> 
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="65%" align="center" valign="middle" bgcolor="#FFFFFF"><strong>Registro(s): <?php echo number_format(($qtde_pesquisado), 0, ',', '.').' de '.number_format(($qtde_registro), 0, ',', '.')?></strong></td>
      <td width="5%" align="right" valign="middle" bgcolor="#FFFFFF"><strong><?php echo number_format($soma_qtde, 0, ',', '.')?></strong></strong></td>
      <td width="10%" align="right" valign="middle" bgcolor="#FFFFFF"><strong><?php echo number_format($soma_valorunitario, 2, ',', '.')?></strong></td>
      <td width="10%" align="right" valign="middle" bgcolor="#FFFFFF"><strong><?php echo number_format($soma_valortotal, 2, ',', '.')?></strong></td>
      <td width="10%" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
  </table>
<footer id="foot01" align="center"></footer>
</div>
<script src="../../js/menu.js"></script>
</body>
</html>
<?php } else{
	$url_de_destino = "../../login.php";
	include "../../includes/redireciona.php";
}
?>