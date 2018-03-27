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
							 		
	if ($_GET['id']) $titulo = "ALTERANDO TIPO MOVIMENTO";
				else $titulo = "CADASTRANDO TIPO MOVIMENTO";
		
	$select = @mysql_fetch_assoc(mysql_query("SELECT * FROM tipomovimento where tipomovimento_id = '".$_GET['id']."'"));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<script type="text/javascript" src="valida_campo.js"></script>
<script type="text/javascript" src="../../js/funcoes.js"></script>
<script type="text/javascript" src="../../js/mascaras.js"></script>
<script type="text/javascript" src="../../js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput.min.js"></script>

</head>

<body onLoad="frmcadastro.descricao.focus()">

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
  <form onsubmit="return validacampo(this)" action="processa.php<?php if ($_GET['id']) echo "?acao=alteracao"?>" name="frmcadastro" method="post">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td width="20%" align="right" valign="middle" bgcolor="#CCCCCC"><strong>Id:</strong></td>
        <td width="80%" align="left" valign="middle" bgcolor="#CCCCCC"><label for="id"></label>
          <input style="border:transparent; background:transparent" name="id" type="text" id="id" value="<?php if ($_GET['id']) echo $select['tipomovimento_id']; else echo 'registro novo';?>" size="20" maxlength="20" readonly="readonly" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td width="20%" align="right" valign="middle" bgcolor="#CCCCCC"><strong> Descrição:</strong></td>
        <td width="80%" align="left" valign="middle" bgcolor="#CCCCCC"><input name="descricao" type="text" id="descricao" value="<?php echo $select['tipomovimento_descricao']?>" size="60" maxlength="60" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Tipo:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="tipo" type="radio" value="E" checked="checked" <?php if($select['tipomovimento_tipo'] == "E") echo "checked" ?>>&nbsp;Entrada&nbsp;<input name="tipo" type="radio" value="S" <?php if($select['tipomovimento_tipo'] == "S") echo "checked" ?>/>&nbsp;Saída</td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Estorno?</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="estorno" type="radio" value="S" <?php if($select['tipomovimento_estorno'] == "S") echo "checked" ?>>
          &nbsp;Sim&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="estorno" type="radio" value="N" checked="checked" <?php if($select['tipomovimento_estorno'] == "N") echo "checked" ?>/>
        &nbsp;Não</td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Afeta Custo Médio?</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="customedio" type="radio" value="S" checked="checked" <?php if($select['tipomovimento_customedio'] == "S") echo "checked" ?>>
          &nbsp;Sim&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="customedio" type="radio" value="N" <?php if($select['tipomovimento_customedio'] == "N") echo "checked" ?>/>
        &nbsp;Não</td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Afeta Custo Atual?</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="custoatual" type="radio" value="S" checked="checked" <?php if($select['tipomovimento_custoatual'] == "S") echo "checked" ?>>
          &nbsp;Sim&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="custoatual" type="radio" value="N" <?php if($select['tipomovimento_custoatual'] == "N") echo "checked" ?>/>
        &nbsp;Não</td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"></td>
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