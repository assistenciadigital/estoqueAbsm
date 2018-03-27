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
	
	$estabelecimento = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));
		
	$usuario = @mysql_fetch_assoc(mysql_query("SELECT usuario_login, usuario_nome, usuario_ultimo_acesso FROM usuario WHERE usuario_login = '".$_SESSION['login']."'"));

	$data_ultimo_acesso = $usuario['usuario_ultimo_acesso'];
	$data= datetime_datatempo($data_ultimo_acesso);
	$data = str_replace ("-", "às", $data);	
	
	if ($_POST['id']){
	$select = mysql_query("SELECT * FROM produto where produto_id = '".$_POST['id']."' and depto_id like '%".$_POST['depto']."%' and grupo_id like '%".$_POST['grupo']."%' and produto_descricao like '%".$_POST['descricao']."%' order by produto_id");	
	}else if ($_POST['depto']){
	$select = mysql_query("SELECT * FROM produto where depto_id = '".$_POST['depto']."' and grupo_id like '%".$_POST['grupo']."%' and produto_descricao like '%".$_POST['descricao']."%' order by produto_descricao");	
	}else if ($_POST['grupo']){
	$select = mysql_query("SELECT * FROM produto where grupo_id = '".$_POST['grupo']."' and depto_id like '%".$_POST['depto']."%' and produto_descricao like '%".$_POST['descricao']."%' order by produto_descricao");	
	}else{
	$p = explode (" ", $_POST['descricao']);
    $d = explode ("-", $p[0]);
	$s = "$d[1]";
	
$select = mysql_query("SELECT * FROM produto where produto_id like '%".$_POST['id']."%' and depto_id like '%".$_POST['depto']."%' and grupo_id like '%".$_POST['grupo']."%' and produto_descricao like '%".$s."%' order by produto_descricao	");	
	}
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from produto")); // Quantidade de registros pra paginação
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../../css/site.css" rel="stylesheet">
<link type="text/css" href="../../js/autocomplete/jquery-ui.css" rel="stylesheet"/>

<script type="text/javascript" src="../../js/autocomplete/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../js/autocomplete/jquery-ui.js"></script>

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
	document.getElementById("descricao").value = "";	
	document.getElementById("grupo").value = "";
	document.getElementById("depto").value = "";
	document.getElementById("frmlista").submit();	
}
</script>

<script type="text/javascript">
//auto complete
$(document).ready(function() {
// Captura o retorno do retornaCliente.php
    $.getJSON('../../includes/autocomplete_retorna_produto.php', function(data){
    var dados = [];
    // Armazena na array capturando somente o nome do EC
    $(data).each(function(key, value) {
        dados.push(value.produto_id + '-' + value.produto_descricao + ' L: ' + value.produto_lote + ' F: ' + value.produto_fabricacao + ' V: ' + value.produto_validade + ' Qtde: ' + value.produto_estoque);
    });
    // Chamo o Auto complete do JQuery ui setando o id do input, array com os dados e o mínimo de caracteres para disparar o AutoComplete
    $('#descricao').autocomplete({ source: dados, minLength: 1});
    });
});
</script>

<script type="text/javascript">

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

<body onLoad="frmlista.pesquisar.focus()">
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
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><strong>PRODUTOS</strong></td>
  </tr>
</table>

<form action="lista.php" name="frmlista" id="frmlista" method="post" enctype="multipart/form-data">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <th width="10%" align="left" valign="middle" bgcolor="#CCCCCC" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'" <?php if(($_SESSION['nivel'])<=2){ ?>onClick="location='cadastra.php'"<?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?>
        <strong>
        <input type="button" name="novo" id="novo" value="Novo Registro..." style="cursor:pointer"/>
        </strong>        <?php } ?></th>
      <th width="70%" align="center" valign="middle" bgcolor="#CCCCCC" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'"> ID:
<select name="id" id="id" style="width:80px; height: 20px;" onchange="atualiza();">
            <option value=""></option>
            <?php
            $select_produto = mysql_query ("SELECT produto_id, produto_descricao from produto ORDER BY produto_id");
            if (mysql_num_rows ($select_produto)) {
            while ($row_produto = mysql_fetch_assoc($select_produto)) {
            ?>
            <option value="<?php echo $row_produto['produto_id']?>"<?php if($_POST['id'] == $row_produto['produto_id']) echo "selected"?>><?php echo $row_produto['produto_id'].' - '.$row_produto['produto_descricao']?></option>
            <?php }}?>
          </select>
Descrição:&nbsp;
          <strong>
          <input name="descricao" id="descricao" type="text" style="alignment-adjust:middle" value="<?php echo $_POST['descricao']?>" size="30" maxlength="auto" />
          &nbsp;Depto:&nbsp;
          <select name="depto" id="depto" style="width:100px; height: 20px;" onchange="atualiza();">
            <option value=""></option>
            <?php
            $select_depto = mysql_query ("SELECT depto_id, depto_descricao from departamento ORDER BY depto_descricao");
            if (mysql_num_rows ($select_depto)) {
            while ($row_depto = mysql_fetch_assoc($select_depto)) {
            ?>
            <option value="<?php echo $row_depto['depto_id']?>"<?php if($_POST['depto'] == $row_depto['depto_id']) echo "selected"?>><?php echo $row_depto['depto_descricao']?></option>
            <?php }}?>
          </select>
          &nbsp;Grupo:&nbsp;
          <select name="grupo" id="grupo" style="width:100px; height: 20px;" onchange="atualiza();">
            <option value=""></option>
            <?php
            $select_grupo = mysql_query ("SELECT grupo_id, grupo_descricao from grupo ORDER BY grupo_descricao");
            if (mysql_num_rows ($select_grupo)) {
            while ($row_grupo = mysql_fetch_assoc($select_grupo)) {
            ?>
            <option value="<?php echo $row_grupo['grupo_id']?>"<?php if($_POST['grupo'] == $row_grupo['grupo_id']) echo "selected"?>><?php echo $row_grupo['grupo_descricao']?></option>
            <?php }}?>
          </select>
          <input name="pesquisar" type="submit" id="pesquisar" style="cursor:pointer" value="Pesquisar..."/>
          <input type="button" name="limpar" id="limpar" value="Limpar..." onclick="limpa()" style="cursor:pointer" />
          </strong></th>
      <th width="20%" align="right" valign="middle" bgcolor="#CCCCCC" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'"<?php if(($_SESSION['nivel'])<=2){ ?><?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?>&nbsp;<a href="../movimentacao/lista.php" target="_self"><input type="button" name="movimentacao" id="movimentacao" value="Mov." style="cursor:pointer"/></a>&nbsp;
          <a href="relatorio.php?id=<?php echo $_POST['id']?>&amp;depto=<?php echo $_POST['depto']?>&amp;grupo=<?php echo $_POST['grupo']?>&amp;descricao=<?php echo $_POST['descricao']?>" target="_blank"><input type="button" name="relatorio" id="relatorio" value="Relatorio" style="cursor:pointer"/></a>&nbsp;<a href="ean13/cb.php?id=<?php echo $_POST['id']?>&amp;depto=<?php echo $_POST['depto']?>&amp;grupo=<?php echo $_POST['grupo']?>&amp;descricao=<?php echo $_POST['descricao']?>" target="_blank"><input type="button" name="codigodebarras" id="codigodebarras" value="CB" style="cursor:pointer"/></a>&nbsp;<a href="ean13/lista_para_ajuste.php?id=<?php echo $_POST['id']?>&amp;depto=<?php echo $_POST['depto']?>&amp;grupo=<?php echo $_POST['grupo']?>&amp;descricao=<?php echo $_POST['descricao']?>" target="_blank"><input type="button" name="codigodebarrasajuste" id="codigodebarrasajuste" value="CB Ajuste" style="cursor:pointer"/></a>
          <?php } ?></th>
      </tr>
  </table>
</form>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" bgcolor="#00FFFF" scope="col"><strong>Id</strong></td>
      <td width="30%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Descrição</strong></td>
      <td width="20%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Departamento</strong></td>
      <td width="15%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Grupo</strong></td>
      <td width="5%" align="right"  bgcolor="#00FFFF" scope="col"><strong>Estoque Atual</strong></td>
      <td width="5%" align="right"  bgcolor="#00FFFF" scope="col"><strong>R$ Preço Atual</strong></td>
      <td width="5%" align="right"  bgcolor="#00FFFF" scope="col"><strong>R$ Custo Atual</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Opções</strong></td>
    </tr>
  </table>
  
  <?php 
  //<div style="color:#009; width:100%; height:303; overflow: auto; vertical-align: left;">   
  if (mysql_num_rows ($select)) {
	  while ($row = mysql_fetch_assoc($select)) {
  	        $i++;
    		if ($i%2) $cor = '#F0F0F0';
				else $cor = '#FFFFFF';
$select_depto=@mysql_fetch_assoc(mysql_query("SELECT depto_descricao FROM departamento WHERE depto_id='".$row['depto_id']."'"));
$select_grupo=@mysql_fetch_assoc(mysql_query("SELECT grupo_descricao FROM grupo WHERE grupo_id='".$row['grupo_id']."'"));
$select_unidade=@mysql_fetch_assoc(mysql_query("SELECT unidade_descricao FROM unidade WHERE unidade_id='".$row['unidade_id']."'"));
  ?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['produto_id'].'<br/>'.$row['produto_codigobarras']?></td>
      <td width="30%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['produto_descricao'].'<br/>L.: '.$row['produto_lote'].' Fab.: '.$row['produto_fabricacao'].' Val.: <strong>'.$row['produto_validade'].'</strong>'?></td>
      <td width="20%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $select_depto['depto_descricao']?></td>
      <td width="15%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $select_grupo['grupo_descricao']?></td>
      <td width="5%" align="right"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['produto_estoque']?></td>
      <td width="5%" align="right"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo number_format($row['produto_preco'], 2, ',', '.')?></td>
      <td width="5%" align="right"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo number_format($row['produto_custoatual'], 2, ',', '.')?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?><a href="cadastra.php?id=<?php echo $row['produto_id']?>"><img src="../../imgs/editar.png" width="20" height="20" /></a><?php } ?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?><a href="javascript: exclui (<?php echo $row['produto_id']?>)"> <img src="../../imgs/excluir.png" width="20" height="20" /></a><?php } ?></td>
    </tr>
  </table>
  
  <?php
    $soma_produto_estoque = $soma_produto_estoque + $row['produto_estoque'];
  	$soma_produto_preco = $soma_produto_preco + $row['produto_preco'];
	$soma_produto_custoatual = $soma_produto_custoatual + $row['produto_custoatual'];
   }}?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td align="center" valign="middle" bgcolor="#FFFFFF"><strong>Registro(s): <?php echo number_format(($qtde_pesquisado), 0, ',', '.').' de '.number_format(($qtde_registro), 0, ',', '.')?></strong></td>
      <td width="5%" align="right" valign="middle" bgcolor="#FFFFFF"><strong><?php echo number_format($soma_produto_estoque, 0, ',', '.')?></strong></td>
      <td width="5%" align="right" valign="middle" bgcolor="#FFFFFF"><strong><?php echo number_format($soma_produto_preco, 2, ',', '.')?></strong></td>
      <td width="5%" align="right" valign="middle" bgcolor="#FFFFFF"><strong><?php echo number_format($soma_produto_custoatual, 2, ',', '.')?></strong></td>
      <td width="5%" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="5%" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
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