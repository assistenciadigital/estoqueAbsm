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

	$select = mysql_query("SELECT * FROM medicamento where medicamento_descricao like '%".$_POST['pesquisar']."%' order by medicamento_descricao");	
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from medicamento")); // Quantidade de registros pra paginação
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
function exclui (medicamento_id){
	if(confirm ('Confirma a exclusão do registro: ' + medicamento_id + '?')) {
		location = 'processa.php?acao=exclusao&id=' + medicamento_id;
	}
}
</script>

<script type="text/javascript">
//auto complete
$(document).ready(function() {
// Captura o retorno do retornaCliente.php
    $.getJSON('../../includes/autocomplete_retorna_medicamento.php', function(data){
    var dados = [];
    // Armazena na array capturando somente o nome do EC
    $(data).each(function(key, value) {
        dados.push(value.medicamento_descricao);
    });
    // Chamo o Auto complete do JQuery ui setando o id do input, array com os dados e o mínimo de caracteres para disparar o AutoComplete
    $('#pesquisar').autocomplete({ source: dados, minLength: 2});
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
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><strong>MEDICAMENTOS</strong></td>
  </tr>
</table>

<form action="lista.php" name="frmlista" method="post" enctype="multipart/form-data">
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" valign="middle" bgcolor="#999999" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#999999'" <?php if(($_SESSION['nivel'])<=2){ ?>onClick="location='cadastra.php'"<?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?><img src="../../imgs/novo.png" width="20" height="20" /><?php } ?></td>
      <td width="70%" align="left" valign="middle" bgcolor="#999999" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#999999'">Filtrar  Descrição:&nbsp;
          <strong>
          <input name="pesquisar" id="pesquisar" type="text" style="alignment-adjust:middle" value="<?php echo $_POST['pesquisar']?>" size="60" maxlength="auto" />
          &nbsp;<input name="Pesquisa" type="submit" id="Pesquisa" style="cursor:pointer" value="Pesquisar"/>
        </strong></td>
      <td width="10%" align="center" valign="middle" bgcolor="#999999" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#999999'"><strong><a href="../../index.php"><img src="../../imgs/bt_voltar.png" height="20" border="0" /></a></strong></td>
      <td width="10%" align="center" valign="middle" bgcolor="#999999" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#999999'"<?php if(($_SESSION['nivel'])<=2){ ?>onClick="location='relatorio.php'"<?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?><img src="../../imgs/ico-printer.png" width="20" height="20" /><?php } ?></td>
      </tr>
  </table>
</form>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" bgcolor="#00FFFF" scope="col"><strong>Id</strong></td>
      <td width="35%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Descrição</strong></td>
      <td width="15%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Fabricante</strong></td>
      <td width="15%" align="left"  bgcolor="#00FFFF" scope="col"><strong>Concentração</strong></td>
      <td width="10%" align="left"  bgcolor="#00FFFF" scope="col"><strong>Forma</strong></td>
      <td width="15%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Opções</strong></td>
    </tr>
  </table>
    
  <?php 
  //<div style="color:#009; width:100%; height:303; overflow: auto; vertical-align: left;"> 
  if (mysql_num_rows ($select)) {
	  while ($row = mysql_fetch_assoc($select)) {
  	        $i++;
    		if ($i%2) $cor = '#F0F0F0';
				else $cor = '#FFFFFF';
$select_depto=@mysql_fetch_assoc(mysql_query("SELECT depto_descricao FROM departamento WHERE depto_id='".$row['depto_id']."'"));
$select_grupo=@mysql_fetch_assoc(mysql_query("SELECT grupo_descricao FROM grupo WHERE grupo_id='".$row['grupo_id']."'"));
$select_unidade=@mysql_fetch_assoc(mysql_query("SELECT unidade_descricao FROM unidade WHERE unidade_id='".$row['unidade_id']."'"));
  ?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['medicamento_id']?></td>
      <td width="35%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['medicamento_descricao'].'<br>'.$row['medicamento_generico']?></td>
      <td width="15%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['fabricante_id']?></td>
      <td width="15%" align="left"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['medicamento_concentracao']?></td>
      <td width="10%" align="left"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['forma_id']?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><a href="exportar.php?id=<?php echo $row['medicamento_id']?>"><img src="../../imgs/exportar.png" alt="" width="29" height="29" /></a></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?><a href="cadastra.php?id=<?php echo $row['medicamento_id']?>"><img src="../../imgs/editar.png" width="20" height="20" /></a><?php } ?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?><a href="javascript: exclui (<?php echo $row['medicamento_id']?>)"> <img src="../../imgs/excluir.png" width="20" height="20" /></a><?php } ?></td>
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