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
		
		if (strlen($_POST['pesquisa'])==13)
		$select = @mysql_fetch_assoc(mysql_query("SELECT * FROM produto where produto_codigobarras = '".substr($_POST['pesquisa'], 0, 12)."'"));
	else if (is_numeric($_POST['pesquisa']))
		$select = @mysql_fetch_assoc(mysql_query("SELECT * FROM produto where produto_id = '".$_POST['pesquisa']."'"));
		else
		$p = explode (" ", $_POST['pesquisa']);
	    $d = explode ("-", $p[0]);
		$s = "$d[0]";
		$select = @mysql_fetch_assoc(mysql_query("SELECT * FROM produto where produto_id = '".$s."'"));
		
$forma = @mysql_fetch_assoc(mysql_query ("SELECT forma_descricao from forma where forma_id = '".$select['forma_id']."'"));
$grupo = @mysql_fetch_assoc(mysql_query ("SELECT grupo_descricao from grupo where grupo_id = '".$select['grupo_id']."'"));
$depto = @mysql_fetch_assoc(mysql_query ("SELECT depto_descricao from departamento where depto_id = '".$select['depto_id']."'"));
$unidade = @mysql_fetch_assoc(mysql_query ("SELECT unidade_descricao from unidade where unidade_id = '".$select['unidade_id']."'"));
$fabricante = @mysql_fetch_assoc(mysql_query ("SELECT fabricante_descricao from fabricante where fabricante_id = '".$select['fabricante_id']."'"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../../css/site.css" rel="stylesheet">
<link type="text/css" href="../../js/autocomplete/jquery-ui.css" rel="stylesheet"/>
<style type="text/css">
body,td,th {
	font-size: 16px;
}
</style>
<script type="text/javascript" src="valida_campo.js"></script>
<script type="text/javascript" src="../../js/autocomplete/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../js/autocomplete/jquery-ui.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput.min.js"></script>

<title>SisHosp</title>

<script language="javascript">

function atualiza(){
 document.getElementById("frmpesquisa").submit();	
}

function limpa() {
	document.getElementById("frmpesquisa").reset();	
	document.getElementById("pesquisa").value = "";
	document.getElementById("frmpesquisa").submit();	
}

function calculo(){ 

qtde       = document.frmprocessa.quantidade.value; 
unitario   = document.frmprocessa.valorunitario.value; 
unitario   = unitario.replace(/[.]/g, ""); 
unitario   = unitario.replace(/[,]/g, "."); 
 
total      = Math.round(parseFloat(unitario) * parseInt(qtde));

document.frmprocessa.valortotal.value=parseFloat(total); 
 
}
 
 function moedaParaNumero(valor)
{
    return isNaN(valor) == false ? parseFloat(valor) :   parseFloat(valor.replace("R$","").replace(".","").replace(",","."));
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
    $('#pesquisa').autocomplete({ source: dados, minLength: 1});
    });
});
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

<body onload="frmpesquisa.pesquisa.focus()">

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
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"></td>
  </tr>
</table>
 
<form onsubmit="return validacampo(this)" action="ajuste_entrada.php" id="frmpesquisa" method="post">
<div style="border: 1px solid #c30; background: #ffe; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px" align="center"><p><strong>AJUSTE DE ENTRADA</strong><p/>&nbsp;<input name="pesquisa" type="text" id="pesquisa" placeholder="Digite o que você está procurando e clique em Pesquisar" value="<?php echo $_POST['pesquisa']?>" size="50" maxlength="50" />&nbsp;<input type="submit" name="pesquisar" id="pesquisar" value="Pesquisar..." />
<input type="button" name="limpar" id="limpar" value="Limpar..." onclick="limpa()"/>
<a href="lista.php"><input type="button" name="voltar" id="voltar" value="Voltar..." /></a></div></p>
</form>
<?php if ($select['produto_id']){?>
<form action="processa.php?acao=ajuste_entrada" method="post" id="frmprocessa">
<div style="border: 1px solid #c30; background: #ffe; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px" align="center">

  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"  class="pesquisa">
      <tr>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Id:</td>
        <td width="40%" align="left" valign="middle" bgcolor="#CCCCCC"><input name="produto" type="text" id="produto" style="border:transparent; background:transparent" value="<?php echo $select['produto_id']?>" size="20" maxlength="20" readonly="readonly" /></td>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Codigo Barras:</td>
        <td width="40%" align="left" valign="middle" bgcolor="#CCCCCC"><?php echo $select['produto_codigobarras']?></td>
      </tr>
      <tr>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Descrição:</td>
        <td width="40%" align="left" valign="middle" bgcolor="#CCCCCC"><?php echo $select['produto_descricao']?> / <?php echo $forma['forma_descricao']?> / <?php echo $unidade['unidade_descricao']?> / <?php echo number_format($select['produto_peso'], 0, ',', '.')?></td>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Nome Generico:</td>
        <td width="40%" align="left" valign="middle" bgcolor="#CCCCCC"><?php echo $select['produto_generico']?> / <?php echo $forma['forma_descricao']?> / <?php echo $unidade['unidade_descricao']?> / <?php echo number_format($select['produto_peso'], 0, ',', '.')?></td>
      </tr>
      <tr>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Tipo:</td>
        <td width="40%" align="left" valign="middle" bgcolor="#CCCCCC"><?php echo $grupo['grupo_descricao']?> / <?php echo $depto['depto_descricao']?></td>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Data Entrada:</td>
        <td width="40%" align="left" valign="middle" bgcolor="#CCCCCC"><?php echo datetime_datatempo($select['produto_data_entrada'])?></td>
      </tr>
      <tr>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Estoque Minimo:</td>
        <td width="40%" align="left" valign="middle" bgcolor="#CCCCCC"><?php echo number_format($select['produto_estoqueminimo'], 0, ',', '.')?></td>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Estoque Maximo:</td>
        <td width="40%" align="left" valign="middle" bgcolor="#CCCCCC"><?php echo number_format($select['produto_estoquemaximo'], 0, ',', '.')?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" bgcolor="#CCCCCC">Estoque Atual:</td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><?php echo number_format($select['produto_estoque'], 0, ',', '.')?></td>
        <td align="right" valign="middle" bgcolor="#CCCCCC">Custo Médio:</td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><?php echo number_format($select['produto_customedio'], 0, ',', '.')?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" bgcolor="#CCCCCC">Lote:</td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><?php echo $select['produto_lote']?>&nbsp;&nbsp;Fab:&nbsp;<?php echo $select['produto_fabricacao']?> &nbsp;&nbsp;Val:&nbsp;<?php echo $select['produto_validade']?></td>
        <td align="right" valign="middle" bgcolor="#CCCCCC">Fabricante:</td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><?php echo $fabricante['fabricante_descricao']?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" bgcolor="#CCCCCC">Local Estoque:</td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="local" id="local">
          <?php
            $select_local = mysql_query ("SELECT local_id, local_descricao from localestoque WHERE local_id = 1 ORDER BY local_id");
            if (mysql_num_rows ($select_local)) {
            while ($row_local= mysql_fetch_assoc($select_local)) {
            ?>
          <option value="<?php echo $row_local['local_id']?>"<?php if($_POST['id'] == $row_local['local_id']) echo "selected"?>><?php echo $row_local['local_descricao']?></option>
          <?php }}?>
        </select></td>
        <td align="right" valign="middle" bgcolor="#CCCCCC"> Estoque do Local:</td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><?php echo number_format($select['produto_estoquemaximo'], 0, ',', '.')?></td>
      </tr>
      <tr>
        <td align="right" valign="middle" bgcolor="#CCCCCC">Documento:</td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="documento" type="text" id="documento"/></td>
        <td align="right" valign="middle" bgcolor="#CCCCCC">Quantidade:</td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="quantidade" type="text" class="frm_number_only" id="quantidade" />
          <strong>AJUSTE DE ENTRADA</strong></td>
      </tr>
      <tr>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Custo Atual R$</td>
        <td width="40%" align="left" valign="middle" bgcolor="#CCCCCC"><?php echo number_format($select['produto_custoatual'], 2, ',', '.')?></td>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Preço Atual R$::</td>
        <td width="40%" align="left" valign="middle" bgcolor="#CCCCCC"><?php echo number_format($select['produto_preco'], 2, ',', '.')?></td>
      </tr>
      <tr>
        <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC">Historico:</td>
        <td colspan="3" align="left" valign="middle" bgcolor="#CCCCCC"><input name="historico" type="text" id="historico" value="<?php echo "AJUSTE DE ENTRADA - EM: ".date('d/m/Y H:m')." USUARIO: ".$_SESSION['login'] ?>" size="120" /></td>
      </tr>
      <tr>
        <td colspan="4" align="right" valign="middle" bgcolor="#CCCCCC">
          <?php if ($select['produto_id']){?>
          <input type="submit" name="cadastrar" id="cadastrar" value="Cadastrar..." />
          &nbsp;&nbsp;&nbsp;
          <input type="reset" name="limpa_processa" id="limpa_processa" value="Limpar..."/></td>
           <?php } ?>
      </tr>
    </table>
    </div>
</form>
 <?php } ?>
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