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
	$data = datetime_datatempo($data_ultimo_acesso);
	$data = str_replace ("-", "às", $data);	
				 		
	if ($_GET['id']) $titulo = "ALTERANDO SENHA USUARIO";
				else $titulo = "CADASTRANDO SENHA USUARIO";
		
	$select = @mysql_fetch_assoc(mysql_query("SELECT usuario_login as usuario, usuario_senha, usuario_nome, usuario_nascimento, usuario_sexo, usuario_nivel, usuario_email, usuario_celular, usuario_telefone, usuario_ativo, usuario_msg, usuario_excluido, usuario_novo_acesso, usuario_ultimo_acesso as ultimo_acesso, usuario_logado, data FROM usuario where usuario_login = '".$_GET['id']."'"));

	$dt_ultimo_acesso = $select['ultimo_acesso'];
	$dt = datetime_datatempo($dt_ultimo_acesso);
	$dt = str_replace ("-", "às", $dt);	
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

<script type="text/javascript">
<!-- Fim do JavaScript que validará os campos obrigatórios! -->
// Inicio Máscaras//
$(document).ready(function(){
	$("input[name='nascimento']").mask('99/99/9999');
	$("input[name='telefone']").mask('(99) 9999-9999');
	$("input[name='celular']").mask('(99) 9999-9999');
	
}) // Fim Máscaras

</script>

<body onLoad="frmcadastro.senha.focus()">

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
  <form onsubmit="return validacampo(this)" action="processa.php<?php if ($_GET['id']) echo "?acao=senha"?>" name="frmcadastro" method="post">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td width="20%" align="right" valign="middle" bgcolor="#CCCCCC"><strong>Login:</strong></td>
        <td width="80%" align="left" valign="middle" bgcolor="#CCCCCC"><label for="id"></label>
          <input name="id" type="text" id="id" value="<?php if ($_GET['id']) echo  $select['usuario']; else echo 'registro novo';?>" size="20" maxlength="20" readonly="readonly" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td width="20%" align="right" valign="middle" bgcolor="#CCCCCC"><strong> Nome Completo:</strong></td>
        <td width="80%" align="left" valign="middle" bgcolor="#CCCCCC"><input name="nome" type="text" id="nome" disabled="disabled" value="<?php echo $select['usuario_nome']?>" size="60" maxlength="60" readonly="readonly" /></td>
        </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Data Nascimento:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC">
          <input name="nascimento" type="text" id="nascimento" disabled="disabled" value="<?php echo date_data($select['usuario_nascimento'])?>" size="20" maxlength="20" readonly="readonly" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Sexo</strong>:&nbsp;<input name="sexo" type="radio" disabled="disabled" class="form" id="sexo" <?php if ($_GET['acao'] == 'alterarsenha'){?>disabled="disabled"<?php }?> value="F" <?php if($select['usuario_sexo'] == "F") echo "checked" ?>><strong>Feminino</strong>&nbsp;
      <input name="sexo" type="radio" disabled="disabled" class="form" id="sexo" value="M" <?php if ($_GET['acao'] == 'alterarsenha'){?>disabled="disabled"<?php }?>value="M" <?php if($select['usuario_sexo'] == "M") echo "checked" ?>/><strong>Masculino</strong></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>E-mail:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="email" type="text" id="email" disabled="disabled" value="<?php echo $select['usuario_email']?>" size="60" maxlength="60" readonly="readonly" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Telefone:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="telefone" type="text" id="telefone" disabled="disabled" value="<?php echo $select['usuario_telefone']?>" size="60" maxlength="60" readonly="readonly" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Celular:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="celular" type="text" id="celular" disabled="disabled" value="<?php echo $select['usuario_celular']?>" size="60" maxlength="60" readonly="readonly" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Informe a Senha:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input name="senha" type="password" id="senha" value="<?php //echo $select['usuario_senha']?>" size="20" maxlength="20" />
        Confirme a Senha:
        <input name="confirma" type="password" id="confirma" value="<?php //echo $select['usuario_senha']?>" size="20" maxlength="20" /></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Nivel de Acesso:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><select name="nivel" class="form" id="nivel" disabled="disabled" style="width:290px; height:25px"<?php if ($_GET['acao'] == 'alterarsenha'){?>disabled="disabled"<?php }?>>
          <option value="">Nível</option>
          <?php if ($_SESSION['nivel'] >=1){ ?>
          <option value="6" <?php if ($select['usuario_nivel'] == 6) echo "selected" ?>>6-Farmácia</option>
          <?php } ?>
          <?php if ($_SESSION['nivel'] >=1){ ?>
          <option value="5" <?php if ($select['usuario_nivel'] == 5) echo "selected" ?>>5-Laboratório</option>
          <?php } ?>
          <?php if ($_SESSION['nivel'] >=1){ ?>
          <option value="4" <?php if ($select['usuario_nivel'] == 4) echo "selected" ?>>4-Odontologia</option>
          <?php } ?>
          <?php if ($_SESSION['nivel'] >=1){ ?>
          <option value="3" <?php if ($select['usuario_nivel'] == 3) echo "selected" ?>>3-Atendimento</option>
          <?php } ?>
          <?php if ($_SESSION['nivel'] >=1){ ?>
          <option value="2" <?php if ($select['usuario_nivel'] == 2) echo "selected" ?>>2-Administrativo</option>
          <?php } ?>
          <?php if ($_SESSION['nivel'] >=1){ ?>
          <option value="1" <?php if ($select['usuario_nivel'] == 1) echo "selected" ?>>1-Administrador do Sistema</option>
          <?php } ?>
        </select></td>
      </tr>
      <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
        <td align="right" valign="middle" bgcolor="#CCCCCC"><strong>Data do Último Acesso:</strong></td>
        <td align="left" valign="middle" bgcolor="#CCCCCC"><input style="border:transparent; background:transparent" name="ultimoacesso" type="text" id="ultimoacesso" disabled="disabled" value="<?php echo $dt?>" size="60" maxlength="60" readonly="readonly" /></td>
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