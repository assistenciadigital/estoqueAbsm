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

	$estabelecimento = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));
		
	$usuario = @mysql_fetch_assoc(mysql_query("SELECT usuario_login, usuario_nome, usuario_ultimo_acesso FROM usuario WHERE usuario_login = '".$_SESSION['login']."'"));

	$data_ultimo_acesso = $usuario['usuario_ultimo_acesso'];
	$data= datetime_datatempo($data_ultimo_acesso);
	$data = str_replace ("-", "às", $data);
	
	if ($_POST['id']){
	$select = mysql_query("SELECT * FROM tipomovimento where tipomovimento_id = '".$_POST['id']."'");	
	}else{	
	$select = mysql_query("SELECT * FROM tipomovimento where tipomovimento_id like '%".$_POST['id']."%' and tipomovimento_descricao like '%".$_POST['descricao']."%' and tipomovimento_tipo like '%".$_POST['tipo']."%' and tipomovimento_estorno like '%".$_POST['estorno']."%' and tipomovimento_customedio like '%".$_POST['customedio']."%' and tipomovimento_custoatual like '%".$_POST['custoatual']."%' order by tipomovimento_descricao");	
	}
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from tipomovimento")); // Quantidade de registros pra paginação
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
function exclui (tipomovimento_id){
	if(confirm ('Confirma a exclusão do registro: ' + tipomovimento_id + '?')) {
		location = 'processa.php?acao=exclusao&id=' + tipomovimento_id;
	}
}
function atualiza(){
 document.getElementById("frmlista").submit();	
}

function limpa() {
	document.getElementById("frmlista").reset();
	document.getElementById("descricao").value = "";	
	document.getElementById("id").value = "";
	document.frmlista.tipo[0].checked = null; 
	document.frmlista.tipo[1].checked = null;
	document.frmlista.estorno[0].checked = null; 
	document.frmlista.estorno[1].checked = null;
	document.frmlista.customedio[0].checked = null; 
	document.frmlista.customedio[1].checked = null;	
	document.frmlista.custoatual[0].checked = null; 
	document.frmlista.custoatual[1].checked = null;	
	document.getElementById("frmlista").submit();	
	}
</script>

<script type="text/javascript">
//auto complete
$(document).ready(function() {
// Captura o retorno do retornaCliente.php
    $.getJSON('../../includes/autocomplete_retorna_tipomovimento.php', function(data){
    var dados = [];
    // Armazena na array capturando somente o nome do EC
    $(data).each(function(key, value) {
        dados.push(value.assistencia_descricao);
    });
    // Chamo o Auto complete do JQuery ui setando o id do input, array com os dados e o mínimo de caracteres para disparar o AutoComplete
    $('#descricao').autocomplete({ source: dados, minLength: 1});
    });
});
</script>

<script type="text/javascript">
//auto complete
$(document).ready(function() {
// Captura o retorno do retornaCliente.php
    $.getJSON('../../includes/autocomplete_retorna_tipomovimento.php', function(data){
    var dados = [];
    // Armazena na array capturando somente o nome do EC
    $(data).each(function(key, value) {
        dados.push(value.tipomovimento_descricao);
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
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><strong>TIPOS DE MOVIMENTO</strong></td>
  </tr>
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col">&nbsp;</td>
  </tr>
</table>

<form action="lista.php" name="frmlista" id="frmlista" method="post" enctype="multipart/form-data">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">    
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><div align="right">
</div></td>
  </tr>
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="left" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'" <?php if(($_SESSION['nivel'])<=2){ ?>onClick="location='cadastra.php'"<?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?>
        <strong>
        <input type="button" name="novo" id="novo" value="Novo Registro..." style="cursor:pointer"/>
        </strong>        <?php } ?></td>
      <td align="center" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'"><strong>Filtra ID: 
          <select name="id" id="id" style="width:250px; height: 20px;" onchange="atualiza();">
            <option value=""></option>
            <?php
            $select_tipo = mysql_query ("SELECT tipomovimento_id, tipomovimento_descricao from tipomovimento ORDER BY tipomovimento_id");
            if (mysql_num_rows ($select_tipo)) {
            while ($row_tipo = mysql_fetch_assoc($select_tipo)) {
            ?>
            <option value="<?php echo $row_tipo['tipomovimento_id']?>"<?php if($_POST['id'] == $row_tipo['tipomovimento_id']) echo "selected"?>><?php echo $row_tipo['tipomovimento_id'].' - '.$row_tipo['tipomovimento_descricao']?></option>
            <?php }}?>
          </select>
          Filtra  Descrição:&nbsp;
        
          <input name="descricao" id="descricao" type="text" style="alignment-adjust:middle" value="<?php echo $_POST['descricao']?>" size="60" maxlength="auto" />
      
          <input name="pesquisar" type="submit" id="pesquisar" style="cursor:pointer" value="Pesquisar..."/>
          <input type="button" name="limpar" id="limpar" value="Limpar..." onclick="limpa()"/>
        </strong></td>
      <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col"<?php if(($_SESSION['nivel'])<=2){ ?> <?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?>
        <strong><a href="relatorio.php?id=<?php echo $_POST['id']?>&descricao=<?php echo $_POST['descricao']?>&tipo=<?php echo $_POST['tipo']?>&estorno=<?php echo $_POST['estorno']?>&customedio=<?php echo $_POST['customedio']?>&custoatual=<?php echo $_POST['custoatual']?>" target="_blank">
        <input type="button" name="impressao" id="impressao" value="Impressão..." style="cursor:pointer"/>
        </a></strong>        <?php } ?></td>
      </tr>
  </table>

  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" bgcolor="#00FFFF" scope="col"><strong>Id</strong></td>
      <td width="20%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Descrição</strong></td>
      <td width="15%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Tipo:<br/>Entrada: 
          <input type="radio" name="tipo" id="tipo" value="E" onchange="atualiza()" <?php echo ($_POST['tipo'] == "E") ? "checked" : null; ?>/>
      Saída:   <input type="radio" name="tipo" id="tipo" value="S" onchange="atualiza()" <?php echo ($_POST['tipo'] == "S") ? "checked" : null; ?>/></strong></td>
      <td width="15%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Estono?<br/>Sim: <input type="radio" name="estorno" id="estorno" value="S" onchange="atualiza()" <?php echo ($_POST['estorno'] == "S") ? "checked" : null; ?>/>
      Não:   
          <input type="radio" name="estorno" id="estorno" value="N" onchange="atualiza()" <?php echo ($_POST['estorno'] == "N") ? "checked" : null; ?>/></strong></td>
      <td width="15%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Afeta Custo Médio?<br/>Sim: <input type="radio" name="customedio" id="customedio" value="S" onchange="atualiza()" <?php echo ($_POST['customedio'] == "S") ? "checked" : null; ?>/>Não:<input type="radio" name="customedio" id="customedio" value="N" onchange="atualiza()" <?php echo ($_POST['customedio'] == "N") ? "checked" : null; ?>/></strong></td>
      <td width="15%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Afeta Custo Atual?<br/>Sim: <input type="radio" name="custoatual" id="custoatual" value="S" onchange="atualiza()" <?php echo ($_POST['custoatual'] == "S") ? "checked" : null; ?>/>Não:<input type="radio" name="custoatual" id="custoatual" value="N" onchange="atualiza()" <?php echo ($_POST['custoatual'] == "N") ? "checked" : null; ?>/></strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Opções</strong></td>
    </tr>
  </table>
  </form>   
  <?php 
  if (mysql_num_rows ($select)) {
	  while ($row = mysql_fetch_assoc($select)) {
  	        $i++;
    		if ($i%2) $cor = '#F0F0F0';
				else $cor = '#FFFFFF';
  ?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['tipomovimento_id']?></td>
      <td width="20%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['tipomovimento_descricao']?></td>
      <td width="15%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['tipomovimento_tipo']?></td>
      <td width="15%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['tipomovimento_estorno']?></td>
      <td width="15%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['tipomovimento_customedio']?></td>
      <td width="15%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['tipomovimento_custoatual']?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?><a href="cadastra.php?id=<?php echo $row['tipomovimento_id']?>"><img src="../../imgs/editar.png" width="20" height="20" /></a><?php } ?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?><a href="javascript: exclui (<?php echo $row['tipomovimento_id']?>)"> <img src="../../imgs/excluir.png" width="20" height="20" /></a><?php } ?></td>
    </tr>
  </table>
  <?php }}?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td align="center" valign="middle" bgcolor="#FFFFFF"><strong>Registro(s): <?php echo number_format(($qtde_pesquisado), 0, ',', '.').' de '.number_format(($qtde_registro), 0, ',', '.')?></strong></td>
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