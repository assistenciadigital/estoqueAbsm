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
	
	date_default_timezone_set("America/Cuiaba");
	
	$estabelecimento = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));
		
	$usuario = @mysql_fetch_assoc(mysql_query("SELECT usuario_login, usuario_nome, usuario_ultimo_acesso FROM usuario WHERE usuario_login = '".$_SESSION['login']."'"));

	$data_ultimo_acesso = $usuario['usuario_ultimo_acesso'];
	$data= datetime_datatempo($data_ultimo_acesso);
	$data = str_replace ("-", "às", $data);	
						 		
	if ($_GET['id']) $titulo = "ALTERANDO CONVENIO - ENCAMINHAMENTO / AGENDAMENTO / ATENDIMENTO";
				else $titulo = "CADASTRANDO CONVENIO - ENCAMINHAMENTO / AGENDAMENTO / ATENDIMENTO";
		
	$select = @mysql_fetch_assoc(mysql_query("SELECT * FROM convenio_encaminha where id = '".$_GET['id']."'"));

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
	$("input[name='data_guia']").mask('99/99/9999');
	$("input[name='data_agenda']").mask('99/99/9999');
	$("input[name='hora_agenda']").mask('99:99:99');
	$("input[name='data_atende']").mask('99/99/9999');
	$("input[name='hora_atende']").mask('99:99:99');
// Fim Máscaras

   $("input[name=tipo]").change(function(){
		$("select[name=especialidade]").html('<option value="">Carregando Especialidade</option>');
        $.post("../../includes/filtra_tipo_especialidade.php",
          {tipo:$(this).val()},
		  function(valor){
           $("select[name=especialidade]").html(valor);
          }
         )
	})
	
   $("input[name=tipo]").change(function(){
		$("select[name=profissional]").html('<option value="">Carregando Especialidade</option>');
        $.post("../../includes/filtra_profissional.php",
          {tipo:$(this).val()},
		  function(valor){
           $("select[name=profissional]").html(valor);
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

<body onLoad="frmcadastro.numero_guia.focus()">

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
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Número Gua de Encaminhamento:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="numero_guia" type="text" id="numero_guia" value="<?php if ($select['numero_guia']) echo $select['numero_guia']; else echo date(dmYHis);?>" size="20" maxlength="20" onfocus="this.value=razao.value"/></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Data da Guia de Encaminhamento:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="data_guia" type="text" id="data_guia" value="<?php if ($select['data_guia']) echo date_data($select['data_guia']); else echo date(dmY);?>" size="10" maxlength="auto" /></td>
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
      <?php if (mysql_num_rows(mysql_query("SELECT id, nome from convenio_dependente where titular = '".$_GET['titular']."'")) > 0){ ?>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Dependente: </strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><strong>
          <select name="dependente" id="dependente">
            <option value="">Dependente</option>
            <?php
            $select_convenio_dependente = mysql_query ("SELECT id, nome from convenio_dependente where titular = '".$_GET['titular']."' ORDER BY nome");
            if (mysql_num_rows ($select_convenio_dependente)) {
            while ($row_convenio_dependente = mysql_fetch_assoc($select_convenio_dependente)) {
            ?>
            <option value="<?php echo $row_convenio_dependente['id']?>"<?php if($select['dependente'] == $row_convenio_dependente['id']) echo "selected"?>><?php echo $row_convenio_dependente['nome']?></option>
            <?php }}?>
          </select>
        </strong></td>
      </tr><?php }?>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Tipo Atendimento:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><strong>
          <input name="tipo" type="radio" value="MEDICA" <?php if($select['tipo_especialidade'] == "MEDICA") echo "checked" ?>/>
          Médica
          <input name="tipo" type="radio" value="ODONTOLOGICA" <?php if($select['tipo_especialidade'] == "ODONTOLOGICA") echo "checked" ?>/>
          Odontológica</strong></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Especialidade:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="especialidade" id="especialidade">
          <option value="">Especialidade</option>
          <?php
            $select_especialidade = mysql_query ("SELECT especialidade_id, especialidade_descricao FROM especialidade where especialidade_tipo = '".$select['tipo_especialidade']."' ORDER BY especialidade_descricao");
            if (mysql_num_rows ($select_especialidade)) {
            while ($row_especialidade = mysql_fetch_assoc($select_especialidade)) {
            ?>
          <option value="<?php echo $row_especialidade['especialidade_id']?>"<?php if($select['especialidade'] == $row_especialidade['especialidade_id']) echo "selected"?>><?php echo $row_especialidade['especialidade_descricao']?></option>
          <?php }}?>
        </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Descrição:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="descricao" type="text" id="descricao" value="<?php echo $select['descricao']?>" size="80" maxlength="80" onfocus="this.value=razao.value"/></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Profissional: </strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="profissional" id="profissional">
          <option value="">Profissional</option>
          <?php
            $select_profissional = mysql_query ("SELECT profissional_id, profissional_nome FROM profissional where profissional_area = '".$select['tipo_especialidade']."' ORDER BY profissional_nome");
            if (mysql_num_rows ($select_profissional)) {
            while ($row_profissional = mysql_fetch_assoc($select_profissional)) {
            ?>
          <option value="<?php echo $row_profissional['profissional_id']?>"<?php if($select['profissional'] == $row_profissional['profissional_id']) echo "selected"?>><?php echo $row_profissional['profissional_nome']?></option>
          <?php }}?>
        </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Origem:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="origem" id="origem">
          <option value="">Origem</option>
          <?php
            $select_origem = mysql_query ("SELECT origem_id, origem_descricao FROM origem ORDER BY origem_descricao");
            if (mysql_num_rows ($select_origem)) {
            while ($row_origem = mysql_fetch_assoc($select_origem)) {
            ?>
          <option value="<?php echo $row_origem['origem_id']?>"<?php if($select['origem'] == $row_origem['origem_id']) echo "selected"?>><?php echo $row_origem['origem_descricao']?></option>
          <?php }}?>
        </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Destino:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="destino" id="destino">
          <option value="">Destino</option>
          <?php
            $select_destino = mysql_query ("SELECT destino_id, destino_descricao FROM destino ORDER BY destino_descricao");
            if (mysql_num_rows ($select_destino)) {
            while ($row_destino = mysql_fetch_assoc($select_destino)) {
            ?>
          <option value="<?php echo $row_destino['destino_id']?>"<?php if($select['destino'] == $row_destino['destino_id']) echo "selected"?>><?php echo $row_destino['destino_descricao']?></option>
          <?php }}?>
        </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Motivo:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="motivo" id="motivo">
          <option value="">Motivo</option>
          <?php
            $select_motivo = mysql_query ("SELECT motivo_id, motivo_descricao FROM motivo ORDER BY motivo_descricao");
            if (mysql_num_rows ($select_motivo)) {
            while ($row_motivo = mysql_fetch_assoc($select_motivo)) {
            ?>
          <option value="<?php echo $row_motivo['motivo_id']?>"<?php if($select['motivo'] == $row_motivo['motivo_id']) echo "selected"?>><?php echo $row_motivo['motivo_descricao']?></option>
          <?php }}?>
        </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Data do Agendamento:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="data_agenda" type="text" id="data_agenda" value="<?php if ($select['data_agenda']) echo date_data($select['data_agenda']); else echo date(dmY);?>" size="10" maxlength="auto" />
          <strong>Hora do Agendamento</strong>:
          <input name="hora_agenda" type="text" id="hora_agenda" value="<?php if ($select['hora_agenda']) echo date_data($select['hora_agenda']); else echo date(His);?>" size="10" maxlength="auto" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Observação:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="observacao" type="text" id="observacao" autocomplete="off" onfocus="this.value=razao.value" value="<?php echo $select['observacao']?>" size="80" maxlength="80"/></td>
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