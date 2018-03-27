<?php 
session_start();

if($_SESSION['acesso_estoque']!="S"){
$resultado = "Sem Permissão de Acesso! Contacte o Administrador do Sistema.";
$url_de_destino = "../../index.php";
include "../../includes/redireciona.php";	
}

if(($_SESSION['login']) AND ($_SESSION['nivel'])){
	include "../../includes/codigobarras.php";
	include "../../includes/conect.php";
	include "../../includes/data.php";
	include "../../includes/ajustadataehora.php";
	
	$estabelecimento = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));
		
	$usuario = @mysql_fetch_assoc(mysql_query("SELECT usuario_login, usuario_nome, usuario_ultimo_acesso FROM usuario WHERE usuario_login = '".$_SESSION['login']."'"));

	$data_ultimo_acesso = $usuario['usuario_ultimo_acesso'];
	$data= datetime_datatempo($data_ultimo_acesso);
	$data = str_replace ("-", "às", $data);	
	
	if ($_POST['id']){
	$select = mysql_query("SELECT * FROM produto where produto_id = '".$_POST['id']."'and depto_id like '%".$_POST['depto']."%' and grupo_id like '%".$_POST['grupo']."%' and produto_descricao like '%".$_POST['descricao']."%' order by produto_id");	
	}else if ($_POST['depto']){
	$select = mysql_query("SELECT * FROM produto where depto_id = '".$_POST['depto']."' and grupo_id like '%".$_POST['grupo']."%' and produto_descricao like '%".$_POST['descricao']."%' order by produto_descricao");	
	}else if ($_POST['grupo']){
	$select = mysql_query("SELECT * FROM produto where grupo_id = '".$_POST['grupo']."' and depto_id like '%".$_POST['depto']."%' and produto_descricao like '%".$_POST['descricao']."%' order by produto_descricao");	
	}else{
	$select = mysql_query("SELECT * FROM produto where produto_id like '%".$_POST['id']."%' and depto_id like '%".$_POST['depto']."%' and grupo_id like '%".$_POST['grupo']."%' and produto_descricao like '%".$_POST['descricao']."%' order by produto_descricao");	
	}
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from produto")); // Quantidade de registros pra paginação
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link href="../../css/site.css" rel="stylesheet">

<title>SisHosp</title>

<script type="text/javascript">

     function updateBarcode() {
	var barcode = new bytescoutbarcode128();
        var value = document.getElementById("barcodeValue").value;
        barcode.valueSet(value);
        barcode.setMargins(5, 5, 5, 5);
        barcode.setBarWidth(2);
        var width = barcode.getMinWidth();
        barcode.setSize(width, 100);
        var barcodeImage = document.getElementById('barcodeImage');
   	barcodeImage.src = barcode.exportToBase64(width, 100, 0);
      }
 

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

<body>
<div id="main">
<table width="100%" align="center">
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:8px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><div align="right">
<?php echo 'Usuário: '.strtoupper($usuario['usuario_login']).' | ' .strtoupper($usuario['usuario_nome']).' | Seu Último Acesso Foi Em: '.$data?>
</div></td>
  </tr>
  <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:8px">
    <td align="center" valign="middle" bgcolor="#FFFFFF" scope="col"><strong>PRODUTOS</strong></td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:8px">
      <td width="25%" align="center" bgcolor="#00FFFF" scope="col"><strong>Código Barras</strong></td>
      <td width="30%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Id - Descrição</strong></td>
      <td width="10%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Departamento</strong></td>
      <td width="10%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Grupo</strong></td>
      <td width="5%" align="right"  bgcolor="#00FFFF" scope="col"><strong>Estoque Atual</strong></td>
      <td width="10%" align="right"  bgcolor="#00FFFF" scope="col"><strong>R$ Preço Atual</strong></td>
      <td width="10%" align="right"  bgcolor="#00FFFF" scope="col"><strong>R$ Custo Atual</strong></td>
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
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:8px">
      <td width="25%" align="center" valign="middle" bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><div style="width: auto; margin-bottom: auto"><?php echo ($row["produto_codigobarras"])?><br/><?php echo $row['produto_codigobarras']?></div></td>
      <td width="30%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['produto_id'].' - '.$row['produto_descricao'].'<br/>'.$row['produto_generico']?><br/></td>
      <td width="10%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $select_depto['depto_descricao']?></td>
      <td width="10%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $select_grupo['grupo_descricao']?></td>
      <td width="5%" align="right"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['produto_estoque']?></td>
      <td width="10%" align="right"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo number_format($row['produto_preco'], 2, ',', '.')?></td>
      <td width="10%" align="right"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo number_format($row['produto_custoatual'], 2, ',', '.')?></td>
    </tr>
  </table>
 
  <?php
  	$soma_produto_preco = $soma_produto_preco + $row['produto_preco'];
	$soma_produto_custoatual = $soma_produto_custoatual + $row['produto_custoatual'];
   }}?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:8px">
      <td align="center" valign="middle" bgcolor="#FFFFFF"><strong>Registro(s): <?php echo number_format(($qtde_pesquisado), 0, ',', '.').' de '.number_format(($qtde_registro), 0, ',', '.')?></strong></td>
      <td width="5%" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="5%" align="right" valign="middle" bgcolor="#FFFFFF"><strong><?php echo number_format($soma_produto_preco, 2, ',', '.')?></strong></td>
      <td width="5%" align="right" valign="middle" bgcolor="#FFFFFF"><strong><?php echo number_format($soma_produto_custoatual, 2, ',', '.')?></strong></td>
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