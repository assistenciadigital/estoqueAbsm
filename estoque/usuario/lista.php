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
	
	if ($_POST['login']){
	$select = mysql_query("SELECT usuario_login as usuario, usuario_nome as nome, usuario_nascimento, usuario_sexo, usuario_nivel, usuario_email, usuario_celular, usuario_telefone, usuario_ativo, usuario_msg, usuario_excluido, usuario_novo_acesso, usuario_ultimo_acesso as dt_ultimo_acesso, usuario_logado, data FROM usuario where usuario_login like '%".$_POST['login']."%'");	
	}else{	
	$select = mysql_query("SELECT usuario_login as usuario, usuario_nome as nome, usuario_nascimento, usuario_sexo, usuario_nivel, usuario_email, usuario_celular, usuario_telefone, usuario_ativo, usuario_msg, usuario_excluido, usuario_novo_acesso, usuario_ultimo_acesso as dt_ultimo_acesso, usuario_logado, data FROM usuario where usuario_login like '%".$_POST['login']."%' and usuario_nome like '%".$_POST['nome']."%' order by usuario_nome");	
	}
	
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from usuario")); // Quantidade de registros pra paginação
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
function exclui (usuario){
	if(confirm ('Confirma a exclusão do registro: ' + usuario + '?')) {
		location = 'processa.php?acao=exclusao&id=' + usuario;
	}
}

function atualiza(){
 document.getElementById("frmlista").submit();	
}

function limpa() {
	document.getElementById("frmlista").reset();	
	document.getElementById("login").value = "";	
	document.getElementById("nome").value = "";
	document.getElementById("frmlista").submit();	
}

</script>

<script type="text/javascript">
//auto complete
$(document).ready(function() {
// Captura o retorno do retornaCliente.php
    $.getJSON('../../includes/autocomplete_retorna_usuario.php', function(data){
    var dados = [];
    // Armazena na array capturando somente o nome do EC
    $(data).each(function(key, value) {
        dados.push(value.usuariologin);
    });
    // Chamo o Auto complete do JQuery ui setando o id do input, array com os dados e o mínimo de caracteres para disparar o AutoComplete
    $('#login').autocomplete({ source: dados, minLength: 1});
    });
	
// Captura o retorno do retornaCliente.php
    $.getJSON('../../includes/autocomplete_retorna_usuario.php', function(data){
    var dados = [];
    // Armazena na array capturando somente o nome do EC
    $(data).each(function(key, value) {
        dados.push(value.usuarionome);
    });
    // Chamo o Auto complete do JQuery ui setando o id do input, array com os dados e o mínimo de caracteres para disparar o AutoComplete
    $('#nome').autocomplete({ source: dados, minLength: 1});
    });	
});
</script>

</head>

<body onLoad="frmlista.pesquisar.focus()">
<nav id="nav01"></nav>
<div id="main">
<table width="100%" align="center">
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><div align="right">
<?php echo 'Usuário: '.strtoupper($usuario['usuario_login']).' | ' .strtoupper($usuario['usuario_nome']).' | Seu Último Acesso Foi Em: '.$data?>
</div></td>
  </tr>
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><strong>USUARIOS</strong></td>
  </tr>
</table>

<form action="lista.php" name="frmlista" id="frmlista" method="post" enctype="multipart/form-data">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <th width="10%" align="left" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'" <?php if(($_SESSION['nivel'])<=2){ ?>onClick="location='cadastra.php'"<?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?>
        <strong>
        <input type="button" name="novo" id="novo" value="Novo Registro..." style="cursor:pointer"/>
        </strong>        <?php } ?></th>
      <th align="center" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'">Filtrar  Login:&nbsp;
          <strong>
          <input name="login" id="login" type="text" style="alignment-adjust:middle" value="<?php echo $_POST['login']?>" size="20" maxlength="auto" />&nbsp;Filtrar Nome:
          <input name="nome" id="nome" type="text" style="alignment-adjust:middle" value="<?php echo $_POST['nome']?>" size="60" maxlength="auto" />
          <input name="pesquisar" type="submit" id="pesquisar" style="cursor:pointer" value="Pesquisar..."/>
          <input type="button" name="limpar" id="limpar" value="Limpar..." onclick="limpa()"/>
          </strong></th>
      <th width="10%" align="right" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'"<?php if(($_SESSION['nivel'])<=2){ ?><?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?><a href="relatorio.php?login=<?php echo $_POST['login']?>&nome=<?php echo $_POST['nome']?>" target="_blank"><input type="button" name="impressao" id="impressao" value="Impressão..." style="cursor:pointer"/></a><?php } ?></th>
      </tr>
  </table>
</form>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="left" valign="middle" bgcolor="#00FFFF" scope="col"><strong>Login</strong></td>
      <td width="60%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Nome</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Último Acesso</strong></td>
      <td width="5%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Senha</strong></td>
      <td width="5%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Acesso</strong></td>
      <td width="5%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Editar</strong></td>
      <td width="5%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Excluído</strong></td>
    </tr>
  </table>
  
  <?php 
  //<div style="color:#009; width:100%; height:303; overflow: auto; vertical-align: left;">   
  if (mysql_num_rows ($select)) {
	  while ($row = mysql_fetch_assoc($select)) {
  	        $i++;
    		if ($i%2) $cor = '#F0F0F0';
				else $cor = '#FFFFFF';
  ?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="left" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['usuario']?></td>
      <td width="60%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['nome']?></td>
      <td width="10%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo datetime_datatempo($row['dt_ultimo_acesso'])?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if ($row['usuario_excluido']=='N'){if(($_SESSION['nivel'])<=2){ ?><a href="senha.php?id=<?php echo $row['usuario']?>"><img src="../../imgs/i_senha.png" alt="" width="20" height="20" border="0"></a><?php }} ?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if ($row['usuario_excluido']=='N'){if(($_SESSION['nivel'])<=2){if($row['usuario_ativo']=='S'){ ?><a href="processa.php?acao=status_on<?php echo '&id='.$row['usuario']?>"><img src="../../imgs/i_aprovar.png" width="20" height="20"/>Sim</a><?php } else {?><a href="processa.php?acao=status_off<?php echo '&id='.$row['usuario']?>"><img src="../../imgs/i_desaprovar.png" alt="" width="20" height="20" />Não</a><?php }} }?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if ($row['usuario_excluido']=='N'){if(($_SESSION['nivel'])<=2){ ?><a href="cadastra.php?id=<?php echo $row['usuario']?>"><img src="../../imgs/editar.png" width="20" height="20" /></a><?php }} ?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ if($row['usuario_excluido']=='S'){ ?><a href="processa.php?acao=excluido_on<?php echo '&id='.$row['usuario']?>"><img src="../../imgs/i_bloqueado.png" width="20" height="20"/>Sim</a><?php } else {?><a href="processa.php?acao=excluido_off<?php echo '&id='.$row['usuario']?>"><img src="../../imgs/i_desbloqueado.png" alt="" width="20" height="20" />Não</a><?php }} ?></td>
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