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
	
	$estabelecimento = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));
		
	$usuario = @mysql_fetch_assoc(mysql_query("SELECT usuario_login, usuario_nome, usuario_ultimo_acesso FROM usuario WHERE usuario_login = '".$_SESSION['login']."'"));

	$data_ultimo_acesso = $usuario['usuario_ultimo_acesso'];
	$data= datetime_datatempo($data_ultimo_acesso);
	$data = str_replace ("-", "às", $data);	

	if ($_POST['id']){
	$select = mysql_query("SELECT * FROM particular_sus where id = '".$_POST['id']."' and sexo like '%".$_POST['sexo']."%' and classificacao like '%".$_POST['classificacao']."%' order by id");	
	}else if ($_POST['nome']){
	$select = mysql_query("SELECT * FROM particular_sus where nome like '%".$_POST['nome']."%' and sexo like '%".$_POST['sexo']."%' and classificacao like '%".$_POST['classificacao']."%' order by nome");	
	}else if ($_POST['cpf']){
	$select = mysql_query("SELECT * FROM particular_sus where cpf = '".$_POST['cpf']."' and sexo like '%".$_POST['sexo']."%' and classificacao like '%".$_POST['classificacao']."%' order by cpf");	
	}else{
	$select = mysql_query("SELECT * FROM particular_sus where id like '%".$_POST['id']."%' and nome like '%".$_POST['nome']."%' and cpf like '%".$_POST['cpf']."%' and sexo like '%".$_POST['sexo']."%' and classificacao like '%".$_POST['classificacao']."%' order by nome");	
	}
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from particular_sus")); // Quantidade de registros pra paginação
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
function exclui (produto_id){
	if(confirm ('Confirma a exclusão do registro: ' + produto_id + '?')) {
		location = 'processa.php?acao=exclusao&id=' + produto_id;
	}
}
function atualiza(){
 document.getElementById("frmlista").submit();	
}

function limpa() {
	document.getElementById("frmlista").reset();	
	document.getElementById("id").value = "";
	document.getElementById("nome").value = "";	
	document.getElementById("cpf").value = "";
	document.frmlista.sexo[0].checked = null; 
	document.frmlista.sexo[1].checked = null;
	document.frmlista.classificacao[0].checked = null; 
	document.frmlista.classificacao[1].checked = null;
	document.getElementById("frmlista").submit();	
}
</script>

<script type="text/javascript">
//auto complete
$(document).ready(function() {
// Captura o retorno do retornaCliente.php
    $.getJSON('../../includes/autocomplete_retorna_particular_sus.php', function(data){
    var dados = [];
    // Armazena na array capturando somente o nome do EC
    $(data).each(function(key, value) {
        dados.push(value.nome);
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
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><strong>PARTICULAR / SUS</strong></td>
  </tr>
</table>

<form action="lista.php" name="frmlista" id="frmlista" method="post" enctype="multipart/form-data">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <th width="10%" align="left" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'" <?php if(($_SESSION['nivel'])<=2){ ?>onClick="location='cadastra.php'"<?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?>
        <strong>
        <input type="button" name="novo" id="novo" value="Novo Registro..." style="cursor:pointer"/>
        </strong>        <?php } ?></th>
      <th align="center" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'"> ID:
<select name="id" id="id" style="width:100px; height: 20px;" onchange="atualiza();">
            <option value=""></option>
            <?php
            $select_particular_sus = mysql_query ("SELECT id, nome from particular_sus ORDER BY id");
            if (mysql_num_rows ($select_particular_sus)) {
            while ($row_particular_sus = mysql_fetch_assoc($select_particular_sus)) {
            ?>
            <option value="<?php echo $row_particular_sus['id']?>"<?php if($_POST['id'] == $row_particular_sus['id']) echo "selected"?>><?php echo $row_particular_sus['id'].' - '.$row_particular_sus['nome']?></option>
            <?php }}?>
          </select>
Nome:&nbsp;
          <strong>
          <input name="nome" id="nome" type="text" style="alignment-adjust:middle" value="<?php echo $_POST['nome']?>" size="30" maxlength="auto" />
          &nbsp;CPF:&nbsp;
          <input name="cpf" id="cpf" type="text" style="alignment-adjust:middle" value="<?php echo $_POST['cpf']?>" size="15" maxlength="auto" />
          <input name="pesquisar" type="submit" id="pesquisar" style="cursor:pointer" value="Pesquisar..."/>
          <input type="button" name="limpar" id="limpar" value="Limpar..." onclick="limpa()"/>
          </strong></th>
      <th width="10%" align="right" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#CCCCCC'" onMouseOut="this.style.backgroundColor='#CCCCCC'"<?php if(($_SESSION['nivel'])<=2){ ?><?php } ?>><?php if(($_SESSION['nivel'])<=2){ ?>
        <strong><a href="relatorio.php?id=<?php echo $_POST['id']?>&depto=<?php echo $_POST['depto']?>&grupo=<?php echo $_POST['grupo']?>&descricao=<?php echo $_POST['descricao']?>" target="_blank">
          <input type="button" name="impressao" id="impressao" value="Impressão..." style="cursor:pointer"/>
        </a></strong>        <?php } ?></th>
      </tr>
  </table>

  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" bgcolor="#00FFFF" scope="col"><strong>Id</strong></td>
      <td width="45%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Nome</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>CPF</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Data Nascimento</strong></td>
      <td width="5%" align="center"  bgcolor="#00FFFF" scope="col"><p><strong>Sexo:<br/>M<input type="radio" name="sexo" id="sexo" value="M" onchange="atualiza()" <?php echo ($_POST['sexo'] == "M") ? "checked" : null; ?>/>F<input type="radio" name="sexo" id="sexo" value="F" onchange="atualiza()" <?php echo ($_POST['sexo'] == "F") ? "checked" : null; ?>/>
      </strong></p></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Classificação:<br/>Particular<input type="radio" name="classificacao" id="classificacao" value="2" onchange="atualiza()" <?php echo ($_POST['classificacao'] == "2") ? "checked" : null; ?>/>SUS<input type="radio" name="classificacao" id="classificacao" value="1" onchange="atualiza()" <?php echo ($_POST['classificacao'] == "1") ? "checked" : null; ?>/>
      </strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Opções</strong></td>
    </tr>
  </table>
  </form>
  <?php 
  //<div style="color:#009; width:100%; height:303; overflow: auto; vertical-align: left;">   
  if (mysql_num_rows ($select)) {
	  while ($row = mysql_fetch_assoc($select)) {
  	        $i++;
    		if ($i%2) $cor = '#F0F0F0';
				else $cor = '#FFFFFF';
$select_classificacao=@mysql_fetch_assoc(mysql_query("SELECT classificacao_descricao FROM classificacao WHERE classificacao_id='".$row['classificacao']."'"));
  ?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['id']?></td>
      <td width="45%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['nome']?></td>
      <td width="10%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['cpf']?></td>
      <td width="10%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo date_data($row['datanascimento'])?></td>
      <td width="5%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['sexo']?></td>
      <td width="10%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['classificacao'].'-'.$select_classificacao['classificacao_descricao']?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?><a href="cadastra.php?id=<?php echo $row['id']?>"><img src="../../imgs/editar.png" width="20" height="20" /></a><?php } ?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?><a href="javascript: exclui (<?php echo $row['id']?>)"> <img src="../../imgs/excluir.png" width="20" height="20" /></a><?php } ?></td>
    </tr>
  </table>
  
  <?php
  	$soma_produto_preco = $soma_produto_preco + $row['produto_preco'];
	$soma_produto_custoatual = $soma_produto_custoatual + $row['produto_custoatual'];
   }}?>
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