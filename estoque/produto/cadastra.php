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

	$estabelecimento = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));
		
	$usuario = @mysql_fetch_assoc(mysql_query("SELECT usuario_login, usuario_nome, usuario_ultimo_acesso FROM usuario WHERE usuario_login = '".$_SESSION['login']."'"));

	$data_ultimo_acesso = $usuario['usuario_ultimo_acesso'];
	$data= datetime_datatempo($data_ultimo_acesso);
	$data = str_replace ("-", "às", $data);
							 		
	if ($_GET['id']) $titulo = "ALTERANDO PRODUTO";
				else $titulo = "CADASTRANDO PRODUTO";
		
	$select = @mysql_fetch_assoc(mysql_query("SELECT * FROM produto where produto_id = '".$_GET['id']."'"));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<link href="../../css/calendario/calendario_marron.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../../css/calendario/calendar.js"></script>
<script type="text/javascript" src="../../css/calendario/calendario.js"></script>
<script type="text/javascript" src="../../css/calendario/calendar-pt.js"></script>
<script type="text/javascript" src="../../css/calendario/calendar-setup.js"></script>

<script type="text/javascript" src="valida_campo.js"></script>
<script type="text/javascript" src="../../js/funcoes.js"></script>
<script type="text/javascript" src="../../js/mascaras.js"></script>
<script type="text/javascript" src="../../js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput.min.js"></script>

<script language="javascript">

function calculo(){ 

custo      = document.frmcadastro.custoatual.value; 
custo      = custo.replace(/[.]/g, ""); 
custo      = custo.replace(/[,]/g, "."); 
percentual = document.frmcadastro.percentualcustoatual.value; 
percentual = percentual.replace(/[.]/g, ""); 
percentual = percentual.replace(/[,]/g, "."); 
 
preco      = Math.round(((parseFloat(custo) * parseInt(percentual)/100)) + parseFloat(custo));

document.frmcadastro.calculocustoatual.value=parseFloat(preco); 
 
}
 
 function moedaParaNumero(valor)
{
    return isNaN(valor) == false ? parseFloat(valor) :   parseFloat(valor.replace("R$","").replace(".","").replace(",","."));
}
 </script>
 
<script type="text/javascript">
<!-- Fim do JavaScript que validará os campos obrigatórios! -->
// Inicio Máscaras//
$(document).ready(function(){
	$("input[name='fabricacao']").mask('99/9999');
	$("input[name='validade']").mask('99/9999');
}) // Fim Máscaras

// FUNCAO SOMENTE NUMEROS
function frm_number_only_exc(){
	// allowed: numeric keys, numeric numpad keys, backspace, del and delete keys
	if ( event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || ( event.keyCode < 106 && event.keyCode > 95 ) ) { 
		return true;
	}else{
		return false;
	}
}

$(document).ready(function(){
						   
	$("input.frm_number_only").keydown(function(event) { 
 
        if ( frm_number_only_exc() ) { 
 
        } else { 
                if ( event.keyCode < 48 || event.keyCode > 57 ) { 
                        event.preventDefault();  
                }        
        } 
    }); 
	
});
</script>

<script>
function adjustMoney(event,obj,thousands, decimals){ 
var sep = 0; 
var key = ''; 
var i = j = 0; 
var lenght = lenght2 = 0; 
var strCheck = '0123456789'; 
var aux = aux2 = ''; 
var whichCode = (window.Event) ? event.which : event.keyCode; 

if (whichCode == 13){ 
return false; 
} 

if (whichCode != 13 && whichCode < 20){ 
return true; 
} 

key = String.fromCharCode(whichCode);// Valor para o código da Chave 

// Chave inválida 
if (strCheck.indexOf(key) == -1){ 
return false; 
} 

lenght = obj.value.length; 

for(i = 0; i < lenght; i++){ 
if ((obj.value.charAt(i) != '0') && (obj.value.charAt(i) != decimals)){ 
break; 
} 
} 

aux = ''; 

for(; i < lenght; i++){ 
if (strCheck.indexOf(obj.value.charAt(i))!=-1){ 
aux += obj.value.charAt(i); 
} 
} 

aux += key; 

lenght = aux.length; 

if (lenght == 0){ 
obj.value = ''; 
} 

if (lenght == 1){ 
obj.value = '0'+ decimals + '0' + aux; 
} 

if (lenght == 2){ 
obj.value = '0'+ decimals + aux; 
} 

if (lenght > 2){ 
aux2 = ''; 

for (j = 0, i = lenght - 3; i >= 0; i--) { 
if (j == 3) { 
aux2 += thousands; 
j = 0; 

} 
aux2 += aux.charAt(i); 
j++; 
} 

obj.value = ''; 
lenght2 = aux2.length; 

for (i = lenght2 - 1; i >= 0; i--) 
obj.value += aux2.charAt(i); 
obj.value += decimals + aux.substr(lenght - 2, lenght); 
} 
return false; 
}
</script>

</head>

<body onLoad="frmcadastro.codigobarras.focus()">

<div id="main">
<table width="100%" align="center">
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><div align="right">
<?php echo 'Usuário: '.strtoupper($usuario['usuario_login']).' | ' .strtoupper($usuario['usuario_nome']).' | Seu Último Acesso Foi Em: '.$data?>
</div></td>
  </tr>
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><strong><?php echo $titulo?></strong></td>
  </tr>
</table>
  <form onsubmit="return validacampo(this)" action="processa.php<?php if ($_GET['id']) echo "?acao=alteracao"?>" name="frmcadastro" id="frmcadastro" method="post">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td width="20%" align="right" valign="middle" bgcolor="#CCCCCC"><strong>Id:</strong></td>
        <td width="80%" align="left" valign="middle" bgcolor="#CCCCCC"><label for="id"></label>
          <input style="border:transparent; background:transparent" name="id" type="text" id="id" value="<?php if ($_GET['id']) echo $select['produto_id']; else echo 'registro novo';?>" size="20" maxlength="20" readonly="readonly" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Código de Barras:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="codigobarras" type="text" id="codigobarras" value="<?php echo $select['produto_codigobarras']?>" size="12" maxlength="12" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td width="20%" align="right" valign="middle" bgcolor="#CCCCCC"><strong>Descrição:</strong></td>
        <td width="80%" align="left" valign="middle" bgcolor="#CCCCCC"><input name="descricao" type="text" id="descricao" value="<?php echo $select['produto_descricao']?>" size="100" maxlength="200" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Nome Generico:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="generico" type="text" id="generico" onfocus="this.value=descricao.value" value="<?php echo $select['produto_generico']?>" size="100" maxlength="200" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Forma:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="forma" id="forma" style="width:250px; height: 20px;">
          <option value="">Forma</option>
          <?php
            $select_forma = mysql_query ("SELECT forma_id, forma_descricao from forma ORDER BY forma_descricao");
            if (mysql_num_rows ($select_forma)) {
            while ($row_forma= mysql_fetch_assoc($select_forma)) {
            ?>
          <option value="<?php echo $row_forma['forma_id']?>"<?php if($select['forma_id'] == $row_forma['forma_id']) echo "selected"?>><?php echo $row_forma['forma_descricao']?></option>
          <?php }}?>
        </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Grupo:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="grupo" id="grupo" style="width:250px; height: 20px;">
        <option value="">Grupo</option>
        <?php
            $select_grupo = mysql_query ("SELECT grupo_id, grupo_descricao from grupo ORDER BY grupo_descricao");
            if (mysql_num_rows ($select_grupo)) {
            while ($row_grupo = mysql_fetch_assoc($select_grupo)) {
            ?>
        <option value="<?php echo $row_grupo['grupo_id']?>"<?php if($select['grupo_id'] == $row_grupo['grupo_id']) echo "selected"?>><?php echo $row_grupo['grupo_descricao']?></option>
        <?php }}?>
      </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Departamento:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="depto" id="depto" style="width:250px; height: 20px;">
        <option value="">Departamento</option>
        <?php
            $select_depto = mysql_query ("SELECT depto_id, depto_descricao from departamento ORDER BY depto_descricao");
            if (mysql_num_rows ($select_depto)) {
            while ($row_depto = mysql_fetch_assoc($select_depto)) {
            ?>
        <option value="<?php echo $row_depto['depto_id']?>"<?php if($select['depto_id'] == $row_depto['depto_id']) echo "selected"?>><?php echo $row_depto['depto_descricao']?></option>
        <?php }}?>
      </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Unidade de Medida:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="unidade" id="unidade" style="width:150px; height: 20px;">
        <option value="">Unidade de Medida</option>
        <?php
            $select_unidade = mysql_query ("SELECT unidade_id, unidade_descricao from unidade ORDER BY unidade_descricao");
            if (mysql_num_rows ($select_unidade)) {
            while ($row_unidade = mysql_fetch_assoc($select_unidade)) {
            ?>
        <option value="<?php echo $row_unidade['unidade_id']?>"<?php if($select['unidade_id'] == $row_unidade['unidade_id']) echo "selected"?>><?php echo $row_unidade['unidade_descricao']?></option>
        <?php }}?>
      </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Peso / Medida:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><label for="peso"></label>
        <input name="peso" type="text" id="peso" class="frm_number_only" value="<?php echo number_format($select['produto_peso'], 0, ',', '.')?>" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Custo Atual:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><label for="precoatual"></label>
          <strong>
          <input name="custoatual" type="text" id="custoatual" onkeypress="return(adjustMoney(event,this,',','.'))" onblur="calculo()" value="<?php echo $select['produto_custoatual']?>" />
          %  Custo:        
        <input name="percentualcustoatual" type="text" class="frm_number_only" id="percentualcustoatual" value="<?php if (empty($select['produto_percentualcustoatual'])) echo '20'; else echo $select['produto_percentualcustoatual'];?>" onblur="calculo()" size="5" maxlength="5" />
        <strong> Resultado % Custo Atual - Preco de Venda=> 
        <input name="calculocustoatual" type="text" disabled="disabled" id="calculocustoatual" style="background:transparent; border:transparent" onkeypress="return(adjustMoney(event,this,',','.'))" readonly="readonly" /></strong>
        </strong></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Preço Atual - Preco de Venda:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><label for="custoatual"></label>
        <input name="precoatual" type="text" id="precoatual" onkeypress="return(adjustMoney(event,this,',','.'))"  value="<?php echo $select['produto_preco']?>" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Estoque Minimo do Produto:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><label for="estoqueminimo"></label>
        <input name="estoqueminimo" type="text" id="estoqueminimo" class="frm_number_only" value="<?php echo number_format($select['produto_estoqueminimo'], 0, ',', '.')?>" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Estoque Maximo do Produto:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><label for="estoquemaximo"></label>
        <input name="estoquemaximo" type="text" id="estoquemaximo" class="frm_number_only" value="<?php echo number_format($select['produto_estoquemaximo'], 0, ',', '.')?>" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Lote:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="lote" type="text" id="lote" value="<?php echo $select['produto_lote']?>" />
          &nbsp;&nbsp;<strong>Fabricação:</strong>&nbsp;
          <input name="fabricacao" type="text" id="fabricacao" value="<?php echo $select['produto_fabricacao']?>" size="15" />&nbsp;&nbsp;<strong>Validade:</strong>&nbsp;
          <input name="validade" type="text" id="validade" value="<?php echo $select['produto_validade']?>" size="15" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Fabricante:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="fabricante" id="fabricante" style="width:250px; height: 20px;">
          <option value="">Fabricante</option>
          <?php
            $select_fabricante = mysql_query ("SELECT fabricante_id, fabricante_descricao from fabricante ORDER BY fabricante_descricao");
            if (mysql_num_rows ($select_fabricante)) {
            while ($row_fabricante = mysql_fetch_assoc($select_fabricante)) {
            ?>
          <option value="<?php echo $row_fabricante['fabricante_id']?>"<?php if($select['fabricante_id'] == $row_fabricante['fabricante_id']) echo "selected"?>><?php echo $row_fabricante['fabricante_descricao']?></option>
          <?php }}?>
        </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Data de Entrada:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"> <label for="dataentrada"></label>
          <input name="dataentrada" type="text" disabled="disabled" id="dataentrada" style="border: #FFFFFF; background:transparent" value="<?php if (empty($select['produto_data_entrada'])) echo date('d/m/Y H:m'); else echo datetime_datatempo($select['produto_data_entrada'])?>" size="16" maxlength="16" readonly="readonly" /><strong>
          <input name="usuario_login" type="text" disabled="disabled" id="usuario_login" style="border: #FFFFFF; background:transparent" value="<?php echo 'Usuário: '.($select['usuario_entrada'])?>" readonly="readonly" />
          Estoque Atual:</strong>&nbsp;
          <input name="estoqueatual" type="text" disabled="disabled" id="estoqueatual" style="border: #FFFFFF; background:transparent" value="<?php echo number_format($select['produto_estoque'], 0, ',', '.')?>" readonly="readonly" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Data Última Atualização:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="dataatualiza" type="text" disabled="disabled" id="dataatualiza" style="border: #FFFFFF; background:transparent" value="<?php echo datetime_datatempo($select['produto_data_atualiza'])?>" size="16" maxlength="16" readonly="readonly" />
        <input name="usuario_atualiza" type="text" disabled="disabled" id="usuario_atualiza" style="border: #FFFFFF; background:transparent" value="<?php echo 'Usuário: '.($select['usuario_atualiza'])?>" readonly="readonly" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC">&nbsp;</td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><?php if ($_GET['id']){?><input type="image" src="../../imgs/bt_alterar.png">&nbsp;&nbsp;&nbsp;<?php }else{ ?><input type="image" src="../../imgs/bt_cadastrar.png">&nbsp;&nbsp;&nbsp;<?php } ?><img src="../../imgs/bt_limpar.png" style="cursor:hand" onClick="document.formulario.reset();"/>&nbsp;&nbsp;&nbsp;<a href="lista.php"><img src="../../imgs/bt_voltar.png" border="0"></a></td>
        </tr>
      </table>
  </form>
</div>
</body>
</html>
<?php } else{
	$url_de_destino = "../../login.php";
	include "../../includes/redireciona.php";
}
?>