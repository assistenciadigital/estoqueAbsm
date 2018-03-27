<?php 
session_start();

if($_SESSION['acesso_convenio']!="S"){
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
						 		
	if ($_GET['id']) $titulo = "ALTERANDO CONVENIO - DEPENDENTE";
				else $titulo = "CADASTRANDO CONVENIO - DEPENDENTE";
		
	$select = @mysql_fetch_assoc(mysql_query("SELECT * FROM convenio_dependente where id = '".$_GET['id']."'"));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<script type="text/javascript" src="valida_campo.js"></script>
<script type="text/javascript" src="valida_cpf_cnpj.js"></script><!-- onBlur="validar(this)" -->
<script type="text/javascript" src="../../js/funcoes.js"></script>
<script type="text/javascript" src="../../js/mascaras.js"></script>
<script type="text/javascript" src="../../js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput.min.js"></script>

<script type="text/javascript">
<!-- Fim do JavaScript que validará os campos obrigatórios! -->
// Inicio Máscaras//
$(document).ready(function(){
	if($("input[name='inscricao']").length > 11) return $("input[name='inscricao']").mask('99.999.999/9999-99'); //else return $("input[name='inscricao']").mask('999.999.999-99');
	$("input[name='celular']").mask('(99)9999-9999');
	$("input[name='fone']").mask('(99)9999-9999');
	$("input[name='nascimento']").mask('99/99/9999');
	$("input[name='cep']").mask('99.999-999');
// Fim Máscaras

   $("select[name=uf]").change(function(){
		$("select[name=cidade]").html('<option value="">Carregando Cidade</option>');
        $.post("../../includes/filtra_cidade.php",
          {uf:$(this).val()},
		  function(valor){
           $("select[name=cidade]").html(valor);
          }
         )
	})
// Fim Evento change no campo uf  
})
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

<body onLoad="frmcadastro.inscricao.focus()">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" scope="col"><div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; align="left"><?php echo 'Usuário: '.strtoupper($usuario['usuario_login']).' | ' .strtoupper($usuario['usuario_nome']).' | Seu Último Acesso Foi Em: '.$data?></div></td>
    <td width="50%" align="right" scope="col"><div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; align="right"><?php echo date('d/m/Y')?></div>
<div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; align="right" id="clock"></div></td>
  </tr>
</table>

<div id="main">
<table width="100%" align="center">
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><strong><?php echo $titulo?></strong></td>
  </tr>
</table>
  <form onsubmit="return validacampo(this)" action="processa.php<?php if ($_GET['id']) echo "?acao=alteracao"?>" name="frmcadastro" method="post">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td width="20%" align="right" valign="middle" bgcolor="#CCCCCC"><strong>Id:</strong></td>
        <td width="80%" align="left" valign="middle" bgcolor="#CCCCCC"><label for="id"></label>
          <input style="border:transparent; background:transparent" name="id" type="text" id="id" value="<?php if ($_GET['id']) echo $select['id']; else echo 'registro novo';?>" size="20" maxlength="20" readonly="readonly" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Titular:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><strong>
          <select name="titular" id="titular">
            <?php
            $select_convenio_titular = mysql_query ("SELECT id, nome from convenio_empregado where id = '".$_GET['titular']."' ORDER BY nome");
            if (mysql_num_rows ($select_convenio_titular)) {
            while ($row_convenio_titular = mysql_fetch_assoc($select_convenio_titular)) {
            ?>
            <option value="<?php echo $row_convenio_titular['id']?>"<?php if($select['titular'] == $row_convenio_titular['id']) echo "selected"?>><?php echo $row_convenio_titular['nome']?></option>
            <?php }}?>
          </select>
        </strong></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Nome:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="nome" type="text" id="nome" value="<?php echo $select['nome']?>" size="80" maxlength="auto" onfocus="this.value=razao.value"/></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Nascimento: </strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="nascimento" type="text" id="nascimento" value="<?php echo date_data($select['nascimento'])?>" size="10" maxlength="auto" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Sexo:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="sexo" type="radio" value="F" <?php if($select['sexo'] == "F") echo "checked" ?>/>
          Feminino
            <input name="sexo" type="radio" value="M" <?php if($select['sexo'] == "M") echo "checked" ?>/>
        Masculino</td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Parentesco:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><strong>
          <select name="parentesco" id="parentesco">
            <option value=""></option>
            <?php
            $select_parentesco = mysql_query ("SELECT parentesco_id, parentesco_descricao from parentesco ORDER BY parentesco_descricao");
            if (mysql_num_rows ($select_parentesco)) {
            while ($row_parentesco = mysql_fetch_assoc($select_parentesco)) {
            ?>
            <option value="<?php echo $row_parentesco['parentesco_descricao']?>"<?php if($select['parentesco'] == $row_parentesco['parentesco_descricao']) echo "selected"?>><?php echo $row_parentesco['parentesco_descricao']?></option>
            <?php }}?>
          </select>
        </strong></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><?php if ($_GET['id']){?>
          <input type="image" src="../../imgs/bt_alterar.png" />
          &nbsp;&nbsp;&nbsp;
          <?php }else{ ?>
          <input type="image" src="../../imgs/bt_cadastrar.png" />
          &nbsp;&nbsp;&nbsp;
          <?php } ?>
        <img src="../../imgs/bt_limpar.png" style="cursor:hand" onclick="limpa();"/>&nbsp;&nbsp;&nbsp;<a href="lista.php?titular=<?php echo $_GET['titular']?>"><img src="../../imgs/bt_voltar.png" border="0" /></a></td>
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