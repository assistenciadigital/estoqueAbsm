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
	include "../../includes/converte_valor.php";
	include "../../includes/ajustadataehora.php";
	
	$estabelecimento = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));
		
	$usuario = @mysql_fetch_assoc(mysql_query("SELECT usuario_login, usuario_nome, usuario_ultimo_acesso FROM usuario WHERE usuario_login = '".$_SESSION['login']."'"));
	
	$data_ultimo_acesso = $usuario['usuario_ultimo_acesso'];
	$data= datetime_datatempo($data_ultimo_acesso);
	$data = str_replace ("-", "às", $data);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SisHosp</title>
<link href="../../css/site.css" rel="stylesheet">
<link type="text/css" href="../../js/autocomplete/jquery-ui.css" rel="stylesheet"/>

<script type="text/javascript" src="valida_campo.js"></script>
<script type="text/javascript" src="../../js/autocomplete/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../js/autocomplete/jquery-ui.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput.min.js"></script>



<script language="javascript">

function atualiza(){
 document.getElementById("frmpesquisa").submit();	
}

function limpa() {
	document.getElementById("frmcadastro").reset();	
	document.getElementById("pesquisa").value = "";
	document.getElementById("frmcadastro").submit();
	document.getElementById("fornecedor").value = "";
	document.getElementById("documento").value = "";
	document.getElementById("datadocumento").value = "";
	document.getElementById("valordocumento").value = "";		
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
	$("input[name='datadocumento']").mask('99/99/9999');
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

<body onload="frmcadastro.destino.focus()">

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

<form onsubmit="return validacampo(this)" action="processa.php?acao=entrada_movimento_transferencia" name="frmcadastro" method="post">
<div style="border: 1px solid #c30; background: #ffe; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px" align="center">

<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td colspan="2" align="center" bgcolor="#00FFFF" scope="col"><strong>SAÍDA POR TRANSFERÊNCIA - DISPENSAÇÃO PARA OUTROS SETORES</strong></td>
      </tr>
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="50%" align="right" bgcolor="#00FFFF" scope="col"><strong>Destino:</strong></td>
      <td width="50%" align="left"  bgcolor="#00FFFF" scope="col"><select name="destino" id="destino">
        <option value="">Informe o Destino</option>
        <?php
            $select_local = mysql_query ("SELECT local_id, local_descricao from localestoque where local_id <> 1 ORDER BY local_id");
            if (mysql_num_rows ($select_local)) {
            while ($row_local= mysql_fetch_assoc($select_local)) {
            ?>
        <option value="<?php echo $row_local['local_id']?>"<?php if($_POST['destino'] == $row_local['local_id']) echo "selected"?>><?php echo $row_local['local_descricao']?></option>
        <?php }}?>
      </select></td>
      </tr>
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="50%" align="right" bgcolor="#00FFFF" scope="col"><strong>Numero Documento:</strong></td>
      <td width="50%" align="left"  bgcolor="#00FFFF" scope="col"><input name="documento" type="text" id="documento" value="<?php if ($_POST['documento']) echo $_POST['documento']; else echo date(dmYHis);?>" /></td>
      </tr>
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="50%" align="right" bgcolor="#00FFFF" scope="col"><strong>Data Documento:</strong></td>
      <td width="50%" align="left"  bgcolor="#00FFFF" scope="col"><input name="datadocumento" type="text" id="datadocumento" value="<?php if ($_POST['datadocumento']) echo $_POST['datadocumento']; else echo date(dmY)?>" /></td>
    </tr>
    <tr align="center" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td colspan="2" bgcolor="#00FFFF" scope="col"><input type="submit" name="enviar" id="enviar" value="Cadastrar..." />
        <input type="reset" name="limpar" id="limpar" value="Limpar..." onclick="limpa()"/>
        <a href="lista.php">
          <input type="button" name="voltar" id="voltar" value="Voltar..." />
          </a></td>
    </tr>
  </table>
&nbsp;</div></p>
  
  
</form>

<div style="border: 1px solid #c30; background: #ffe; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px" align="center">
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="50%" align="left" bgcolor="#00FFFF" scope="col"><strong>Destino</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Numero Documento</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Data Documento</strong></td>
      <td width="10%" align="right"  bgcolor="#00FFFF" scope="col"><strong>Usuario</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Data Incluão</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Opções</strong></td>
    </tr>
  </table>
  <?php 
  
  if(!($_POST['destino']) and !($_POST['documento']) and !($_POST['datadocumento'])){
	$select = mysql_query("SELECT movimento.movimento_id, movimento.movimento_destino, movimento.numero_documento, movimento.data_documento, movimento.usuario_login, movimento.data, localestoque.local_descricao FROM movimento inner join localestoque on movimento.movimento_destino = localestoque.local_id where movimento.movimento_destino <> 1 order by movimento.data_documento");
  }else{
	$select = mysql_query("SELECT movimento.movimento_id, movimento.movimento_destino, movimento.numero_documento, movimento.data_documento, movimento.usuario_login, movimento.data, localestoque.local_descricao FROM movimento inner join localestoque on movimento.movimento_origem = localestoque.local_id where  movimento.movimento_destino = '".$_POST['destino']."' and  movimento.numero_documento = '".$_POST['documento']."' and  movimento.data_documento = '".data_date($_POST['datadocumento'])."' where movimento.movimento_destino <> 1 order by movimento.data_documento");	  
  }
  	
  if (mysql_num_rows ($select)) {
	  while ($row = mysql_fetch_assoc($select)) {
  	        $i++;
    		if ($i%2) $cor = '#F0F0F0';
				else $cor = '#FFFFFF';
  ?>    
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="50%" align="left" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['local_descricao']?></td>
      <td width="10%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['numero_documento']?></td>
      <td width="10%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo date_data($row['data_documento'])?></td>
      <td width="10%" align="right"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo ($row['usuario_login'])?></td>
      <td width="10%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo datetime_datatempo($row['data'])?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?><a href="continua_saida_por_transferencia.php?movimento_id=<?php echo $row['movimento_id']?>"><img src="../../imgs/editar.png" width="20" height="20" /></a><?php } ?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?><a target="_blank" href="saida_por_transferencia_pdf.php?movimento_id=<?php echo $row['movimento_id']?>"><img src="../../imgs/ico-printer.png" width="26" height="26" /></a><?php } ?></td>
    </tr>
  </table>
<?php }}?></div></p>

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