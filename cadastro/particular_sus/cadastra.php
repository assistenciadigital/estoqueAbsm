<?php 
session_start();

if($_SESSION['acesso_cadastro']!="S"){
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
							 		
	if ($_GET['id']) $titulo = "ALTERANDO PARTICULAR / SUS";
				else $titulo = "CADASTRANDO PARTICULAR / SUS";
		
	$select = @mysql_fetch_assoc(mysql_query("SELECT * FROM particular_sus where id = '".$_GET['id']."'"));

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

preco      = document.frmcadastro.precoatual.value; 
preco      = preco.replace(/[.]/g, ""); 
preco      = preco.replace(/[,]/g, "."); 
percentual = document.frmcadastro.percentualcustoatual.value; 
percentual = percentual.replace(/[.]/g, ""); 
percentual = percentual.replace(/[,]/g, "."); 
 
custo      = Math.round(((parseFloat(preco) * parseInt(percentual)/100)) + parseFloat(preco));

document.frmcadastro.calculocustoatual.value=parseFloat(custo); 
 
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
          <input style="border:transparent; background:transparent" name="id" type="text" id="id" value="<?php if ($_GET['id']) echo $select['id']; else echo 'registro novo';?>" size="20" maxlength="20" readonly="readonly" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Código de Barras:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="codigobarras" type="text" id="codigobarras" value="<?php echo $select['codigobarras']?>" size="30" maxlength="30" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td width="20%" align="right" valign="middle" bgcolor="#CCCCCC"><strong>Descrição:</strong></td>
        <td width="80%" align="left" valign="middle" bgcolor="#CCCCCC"><input name="descricao" type="text" id="descricao" value="<?php echo $select['descricao']?>" size="100" maxlength="200" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Nome Generico:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="generico" type="text" id="generico" onfocus="this.value=descricao.value" value="<?php echo $select['generico']?>" size="100" maxlength="200" /></td>
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
        <input name="peso" type="text" id="peso" class="frm_number_only" value="<?php echo number_format($select['peso'], 0, ',', '.')?>" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Preço Atual:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><label for="precoatual"></label>
        <input name="precoatual" type="text" id="precoatual" onKeyPress="return(adjustMoney(event,this,',','.'))"  onblur="calculo()" value="<?php echo $select['preco']?>" />
        <strong>%  Custo:        
        <input name="percentualcustoatual" type="text" class="frm_number_only" id="percentualcustoatual" value="<?php if (empty($select['percentualcustoatual'])) echo '20'; else echo $select['percentualcustoatual'];?>" onblur="calculo()" size="5" maxlength="5" />
        <strong> Resultado % Custo Atual => 
        <input name="calculocustoatual" type="text" disabled="disabled" id="calculocustoatual" style="background:transparent; border:transparent" onkeypress="return(adjustMoney(event,this,',','.'))" readonly="readonly" /></strong>
        </strong></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Custo Atual:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><label for="custoatual"></label>
        <input name="custoatual" type="text" id="custoatual" onKeyPress="return(adjustMoney(event,this,',','.'))" value="<?php echo $select['custoatual']?>" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Estoque Minimo do Produto:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><label for="estoqueminimo"></label>
        <input name="estoqueminimo" type="text" id="estoqueminimo" class="frm_number_only" value="<?php echo number_format($select['estoqueminimo'], 0, ',', '.')?>" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Estoque Maximo do Produto:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><label for="estoquemaximo"></label>
        <input name="estoquemaximo" type="text" id="estoquemaximo" class="frm_number_only" value="<?php echo number_format($select['estoquemaximo'], 0, ',', '.')?>" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Lote:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="lote" type="text" id="lote" value="<?php echo $select['lote']?>" />
          &nbsp;&nbsp;<strong>Fabricação:</strong>&nbsp;
          <input name="fabricacao" type="text" id="fabricacao" value="<?php echo $select['fabricacao']?>" size="15" />&nbsp;&nbsp;<strong>Validade:</strong>&nbsp;
          <input name="validade" type="text" id="validade" value="<?php echo $select['validade']?>" size="15" />&nbsp;&nbsp;<strong>Fabricante:</strong>
          <select name="fabricante" id="fabricante" style="width:250px; height: 20px;">
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
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Aliquota ICMS:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="aliquotaicms" id="aliquotaicms">
          <option value="">Alíquota ICMS</option>
          <option value="07">07</option>
          <option value="12">12</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="25">25</option>
        </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Código Situação Tributária:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><strong>
          <select name="codigosituacaotributariaicms" id="codigosituacaotributariaicms" style="width:600px">
            <option value="">Codigo Situação Tributária</option>
            <option value="00">00 - Venda Tributada Integralmente</option>
            <option value="10">10 - Tributada e com cobranca de ICMS por Substitucao Tributaria</option>
            <option value="20">20 - Com Redu&ccedil;&atilde;o de base de c&aacute;lculo</option>
            <option value="30">30 - Isenta ou N&atilde;o tributada e com cobranca de ICMS por Substituicao Tributaria</option>
            <option value="40">40 - Isenta </option>
            <option value="41">41 - N&atilde;o Tributada </option>
            <option value="50">50 - Suspens&atilde;o </option>
            <option value="51">51 - Diferimento </option>
            <option value="60">60 - ICMS Cobrando anteriormente por Substituicao Tributaria</option>
            <option value="70">70 - Com redu&ccedil;&atilde;o de base de c&aacute;lculo e Conbran&ccedil;a do ICMS por Substituicao Tributaria</option>
            <option value="90">90 - Outras</option>
          </select>
        % Redução ICMS:
        <label for="reducaoicms"></label>
        <input name="reducaoicms" type="text" id="reducaoicms" class="frm_number_only" value="0" size="10" maxlength="10" />
        </strong></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Origem:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><strong>
          <select name="origem" id="origem" style="width:600px">
            <option value="">Origem</option>
            <option value="0">0 - Nacional</option>
            <option value="1">1 - Estrangeira - Importa&ccedil;&atilde;o Direta</option>
            <option value="2">2 - Estrangeira - Adquirida no mercado interno</option>
          </select>
        % Substituição Tributos:
        <label for="substituicaotributos"></label>
        <input name="substituicaotributos" type="text" class="frm_number_only" id="substituicaotributos" size="10" maxlength="10" />
        </strong></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Data de Entrada:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"> <label for="dataentrada"></label>
          <input name="dataentrada" type="text" disabled="disabled" id="dataentrada" style="border: #FFFFFF; background:transparent" value="<?php if (empty($select['data_entrada'])) echo date('d/m/Y H:m'); else echo datetime_datatempo($select['data_entrada'])?>" readonly="readonly" /></td>
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