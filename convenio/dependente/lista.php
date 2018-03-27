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
		
	$dependente = mysql_query("SELECT * FROM convenio_dependente where titular = '".$_GET['titular']."' order by nome");	
	
	$pega_titular = $_GET['titular'];
	
	$qtde_pesquisado = mysql_num_rows($dependente);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from convenio_dependente")); // Quantidade de registros pra paginação	
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

function exclui(id){
	if(confirm ('Confirma a exclusão do registro: ' + id + '?')) {
		location = 'processa.php?acao=exclusao&id=' + id;
	}
}

function atualiza(){
 	document.getElementById("frmlista").submit();	
}

function limpa() {
	document.getElementById("frmlista").reset();	
	document.getElementById("nome").value = "";	
	document.getElementById("frmlista").submit();	
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

<body onLoad="frmlista.pesquisar.focus()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" scope="col"><div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF" align="left"><?php echo 'Usuário: '.strtoupper($usuario['usuario_login']).' | ' .strtoupper($usuario['usuario_nome']).' | Seu Último Acesso Foi Em: '.$data?></div></td>
    <td width="50%" scope="col"><div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF" align="right"><?php echo date('d/m/Y')?></div>
<div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF" align="right" id="clock"></div></td>
  </tr>
</table>
<nav id="nav01"></nav>
<div id="main">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="100%" colspan="2" align="center" scope="col"><div align="midle" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">CONVENIOS - DEPENDENTE</div></th>
  </tr>
  <tr>
    <th colspan="2" align="center" scope="col">&nbsp;</th>
  </tr>
  </table>
<form action="lista.php" name="frmlista" id="frmlista" method="post" enctype="multipart/form-data">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">    
    <tr bgcolor="#CCCCCC" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="left" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col"><strong>
        <?php if(($_SESSION['nivel'])<=2){ ?>
        <a href="cadastra.php?titular=<?php echo $_GET['titular']?>"><input type="button" name="novo" id="novo" value="Novo Registro..." style="cursor:pointer"/></a>
        <?php } ?>
      </strong></td>
      <th width="70%" align="center" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col"><?php $select_titular = @mysql_fetch_assoc(mysql_query("SELECT id, nome FROM convenio_empregado WHERE id = '".$_GET['titular']."'"));
	  echo 'Titular: '.$select_titular[	'id'].' - '.$select_titular['nome']?></th>
      <th width="10%" align="center" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col"><strong><a href="../empregado/lista.php">
        <input type="button" name="voltar" id="voltar" value="Voltar..." style="cursor:pointer"/>
      </a></strong></th>
      <td width="10%" align="right" valign="middle" bgcolor="#CCCCCC" style="cursor:pointer" scope="col"<?php if(($_SESSION['nivel'])<=2){ ?> <?php } ?>><strong>
        <?php if(($_SESSION['nivel'])<=2){ ?>
         <a href="relatorio.php?titular=<?php echo $_GET['titular']?>" target="_blank"><input type="button" name="impressao" id="impressao" value="Impressão..." style="cursor:pointer"/></a>
        <?php } ?>
      </strong></td>
      </tr>
  </table>
</form>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="5%" align="center" bgcolor="#00FFFF" scope="col"><strong>Id</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Documento</strong></td>
      <td width="40%" align="left"  bgcolor="#00FFFF" scope="col"><strong>Dependente</strong></td>
      <td width="5%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Sexo</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Nascimento</strong></td>
      <td width="20%" align="left"  bgcolor="#00FFFF" scope="col"><strong>Parentesco</strong></td>
      <td width="10%" align="center"  bgcolor="#00FFFF" scope="col"><strong>Opções</strong></td>
    </tr>
  </table>
  
  <?php 
  //<div style="color:#009; width:100%; height:303; overflow: auto; vertical-align: left;">   
  if (mysql_num_rows ($dependente)) {
	  while ($row = mysql_fetch_assoc($dependente)) {
		  	$titular = @mysql_fetch_assoc(mysql_query("SELECT id, nome FROM convenio_empregado WHERE id = '".$row['id']."'"));
  	        $i++;
    		if ($i%2) $cor = '#F0F0F0';
				else $cor = '#FFFFFF';
  ?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['id']?></td>
      <td width="10%" align="center"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['cpf']?><br/><?php echo $row['identidade']?>&nbsp;<?php echo $row['emissor']?></td>
      <td width="40%" align="left" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo '<strong>'.$row['nome'].'</strong>'?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['sexo']?><br/></td>
      <td width="10%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo date_data($row['nascimento'])?></td>
      <td width="20%" align="left" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['parentesco']?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" style="cursor:pointer" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php if(($_SESSION['nivel'])<=2){ ?>
        <a href="cadastra.php?titular=<?php echo $row['titular']?>&id=<?php echo $row['id']?>"><img src="../../imgs/editar.png" width="20" height="20" /></a>
      <?php } ?></td>
      <td width="5%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'" style="cursor:pointer"><?php if(($_SESSION['nivel'])<=2){ ?>
        <a href="javascript: exclui (<?php echo $row['id']?>)"> <img src="../../imgs/excluir.png" width="20" height="20" /></a>
      <?php } ?></td>
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