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
						 		
	if ($_GET['id']) $titulo = "ALTERANDO CONVENIO - EMPREGADO";
				else $titulo = "CADASTRANDO CONVENIO - EMPREGADO";
		
	$select = @mysql_fetch_assoc(mysql_query("SELECT * FROM convenio_empregado where id = '".$_GET['id']."'"));

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
	$("input[name='cpf']").mask('999.999.999-99');
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
  <form onsubmit="return validacampo(this)" action="processa.php<?php if ($_GET['id']) echo "?acao=alteracao"?>" name="frmcadastro" method="post" enctype="multipart/form-data">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td width="11%" align="right" valign="middle" bgcolor="#CCCCCC"><strong>Id:</strong></td>
        <td colspan="2" align="left" valign="middle" bgcolor="#CCCCCC"><label for="id"></label>
          <input style="border:transparent; background:transparent" name="id" type="text" id="id" value="<?php if ($_GET['id']) echo $select['id']; else echo 'registro novo';?>" size="20" maxlength="20" readonly="readonly" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Empresa:</strong></td>
        <td colspan="2" align="left" valign="middle" bgcolor="#CCCCCC"><strong>
          <select name="empresa" id="empresa" style="width:200px; height: 20px;">
            <option value=""></option>
            <?php
            $select_convenio_empresa = mysql_query ("SELECT id, razao from convenio_empresa ORDER BY razao");
            if (mysql_num_rows ($select_convenio_empresa)) {
            while ($row_convenio_empresa = mysql_fetch_assoc($select_convenio_empresa)) {
            ?>
            <option value="<?php echo $row_convenio_empresa['id']?>"<?php if($select['empresa'] == $row_convenio_empresa['id']) echo "selected"?>><?php echo $row_convenio_empresa['razao']?></option>
            <?php }}?>
          </select>
        Matricula:
        <input name="matricula" type="text" id="matricula" value="<?php echo $select['matricula']?>" size="20" maxlength="auto" />
        Função:
        <input name="funcao" type="text" id="funcao" value="<?php echo $select['funcao']?>" size="20" maxlength="auto" />
        Setor:
        <input name="setor" type="text" id="setor" value="<?php echo $select['setor']?>" size="20" maxlength="auto" />
        </strong></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong> CPF:</strong></td>
        <td colspan="2" align="left" valign="middle" bgcolor="#CCCCCC"><input name="cpf" type="text" id="cpf" value="<?php echo $select['cpf']?>" size="20" maxlength="14" onBlur="validar(this)"/></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td width="11%" align="right" valign="middle" bgcolor="#CCCCCC"><strong>Identidade:</strong></td>
        <td colspan="2" align="left" valign="middle" bgcolor="#CCCCCC"><input name="identidade" type="text" id="identidade" value="<?php echo $select['identidade']?>" size="20" maxlength="auto" /> 
          Emissor:
          <input name="emissor" type="text" id="emissor" value="<?php echo $select['emissor']?>" size="20" maxlength="auto" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Nome:</strong></td>
        <td colspan="2" align="left" valign="middle" bgcolor="#CCCCCC"><input name="nome" type="text" id="nome" value="<?php echo $select['nome']?>" size="80" maxlength="auto" onfocus="this.value=razao.value"/> 
          Nascimento: 
          <input name="nascimento" type="text" id="nascimento" value="<?php echo date_data($select['nascimento'])?>" size="10" maxlength="auto" />
          Sexo:
          <input name="sexo" type="radio" value="F" <?php if($select['sexo'] == "F") echo "checked" ?>/>Feminino
          <input name="sexo" type="radio" value="M" <?php if($select['sexo'] == "M") echo "checked" ?>/>Masculino</td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>CEP:</strong></td>
        <td colspan="2" align="left" valign="middle" bgcolor="#CCCCCC"><input name="cep" type="text" id="cep" value="<?php echo $select['cep']?>" size="12" maxlength="12" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Endereço Completo:</strong></td>
        <td colspan="2" align="left" valign="middle" bgcolor="#CCCCCC"><input name="endereco" type="text" id="endereco" value="<?php echo $select['endereco']?>" size="100" maxlength="auto" />
          Número:
            <input name="numero" type="text" id="numero" value="<?php echo $select['numero']?>" size="10" maxlength="auto" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Complemento:</strong></td>
        <td width="68%" align="left" valign="middle" bgcolor="#CCCCCC"><input name="complemento" type="text" id="complemento" value="<?php echo $select['complemento']?>" size="60" maxlength="auto" /></td>
        <td width="21%" rowspan="6" align="center" valign="middle" bgcolor="#CCCCCC"><br/><?php if(file_exists("../../imagens/empregado/".$select['id'].'.jpg')){?><input name="tira_foto" type="checkbox" value="1" id="tira_foto">Excluir Foto atual.<br/><br/><img  rel="lightbox" src="<?php echo '../../imagens/empregado/'.$select['id'].'.jpg'?>" name="mostrafoto" width="150" height="150" id="mostrafoto" /><br/>
        <br/><?php }?><input name="imagem" type="file" accept="image/jpeg" id="imagem"/></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Bairro:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="bairro" type="text" id="bairro" value="<?php echo $select['bairro']?>" size="60" maxlength="auto" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>UF:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="uf" id="uf" style="width:50px; height: 20px;"	>
          <option value="">UF</option>
          <?php
            $select_uf = mysql_query ("SELECT DISTINCT cidade_uf FROM cidade ORDER BY cidade_uf");
            if (mysql_num_rows ($select_uf)) {
            while ($row_uf = mysql_fetch_assoc($select_uf)) {
            ?>
          <option value="<?php echo $row_uf['cidade_uf']?>"<?php if($select['uf'] == $row_uf['cidade_uf']) echo "selected"?>><?php echo $row_uf['cidade_uf']?></option>
          <?php }}?>
          </select>
          Cidade:
          <select name="cidade" id="cidade" style="width:353px; height: 20px;">
            <option value="">Cidade</option>
            <?php
            $select_cidade = mysql_query ("SELECT cidade_descricao FROM cidade WHERE cidade_uf = '".$select['uf']."' ORDER BY cidade_descricao");
            if (mysql_num_rows ($select_cidade)) {
            while ($row_cidade = mysql_fetch_assoc($select_cidade)) {
            ?>
            <option value="<?php echo $row_cidade['cidade_descricao']?>"<?php if($select['cidade'] == $row_cidade['cidade_descricao']) echo "selected"?>><?php echo $row_cidade['cidade_descricao']?></option>
            <?php }}?>
        </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Ceular:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="celular" type="text" id="celular" value="<?php echo $select['celular']?>" size="15" maxlength="15" />
          Fone:
        <input name="fone" type="text" id="fone" value="<?php echo $select['fone']?>" size="15" maxlength="15" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>E-mail:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="email" type="text" id="email" value="<?php echo $select['email']?>" size="60" maxlength="auto" /></td>
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
        <img src="../../imgs/bt_limpar.png" style="cursor:hand" onclick="limpa();"/>&nbsp;&nbsp;&nbsp;<a href="lista.php"><img src="../../imgs/bt_voltar.png" border="0" /></a></td>
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