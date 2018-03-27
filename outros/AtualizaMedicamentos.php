<?php 

session_start();
if(($_SESSION['login']) AND ($_SESSION['nivel'])){
	include "../includes/conect.php";
	include "../includes/data.php";
	include "../includes/ajustadataehora.php";
	
	$estabelecimento = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));
		
	$usuario = @mysql_fetch_assoc(mysql_query("SELECT usuario_login, usuario_nome, usuario_ultimo_acesso FROM usuario WHERE usuario_login = '".$_SESSION['login']."'"));

	$data_ultimo_acesso = $usuario['usuario_ultimo_acesso'];
	$data= datetime_datatempo($data_ultimo_acesso);
	$data = str_replace ("-", "às", $data);	

	$select = mysql_query("SELECT * FROM medicamento order by medicamento_descricao");	
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from medicamento")); // Quantidade de registros pra paginação
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SisHosp</title>

<script language="javascript">
function exclui (medicamento_id){
	if(confirm ('Confirma a exclusão do registro: ' + medicamento_id + '?')) {
		location = 'processa.php?acao=exclusao&id=' + medicamento_id;
	}
}
</script>

<link href="../css/site.css" rel="stylesheet">
</head>

<body>
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

<form onLoad="frmlista.pesquisar.focus()" action="lista.php" name="frmlista" method="post" enctype="multipart/form-data">
</form>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">    
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" bgcolor="#00FFFF" scope="col"><strong>Id</strong></td>
      <td width="35%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Descrição</strong></td>
      <td width="15%" align="ritgh"  bgcolor="#00FFFF" scope="col"><strong>Fabricante</strong></td>
      <td width="15%" align="left"  bgcolor="#00FFFF" scope="col"><strong>Concentração</strong></td>
      <td width="15%" align="left"  bgcolor="#00FFFF" scope="col"><strong>Forma</strong></td>
    </tr>
  </table>
  
  <?php 
  if (mysql_num_rows ($select)) {
	  while ($row = mysql_fetch_assoc($select)) {
  	        $i++;
    		if ($i%2) $cor = '#F0F0F0';
				else $cor = '#FFFFFF';
$select_depto=@mysql_fetch_assoc(mysql_query("SELECT depto_descricao FROM departamento WHERE depto_id='".$row['depto_id']."'"));
$select_grupo=@mysql_fetch_assoc(mysql_query("SELECT grupo_descricao FROM grupo WHERE grupo_id='".$row['grupo_id']."'"));
$select_unidade=@mysql_fetch_assoc(mysql_query("SELECT unidade_descricao FROM unidade WHERE unidade_id='".$row['unidade_id']."'"));
if ()
mysql_query("UPDATE medicamento SET fabricante_id='".."' where medicamento_id='".$_POST['id']."'");


  ?>
  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px">
      <td width="10%" align="center" bgcolor="<?php echo $cor?>" scope="col" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'" onMouseOver="this.style.backgroundColor='#00FFFF'"><?php echo $row['medicamento_id']?></td>
      <td width="35%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['medicamento_descricao'].'<br>'.$row['medicamento_generico']?></td>
      <td width="15%" align="rigth"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['fabricante_id']?></td>
      <td width="15%" align="left"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['medicamento_concentracao']?></td>
      <td width="15%" align="left"  bgcolor="<?php echo $cor?>" scope="col" onMouseOver="this.style.backgroundColor='#00FFFF'" onMouseOut="this.style.backgroundColor='<?php echo $cor?>'"><?php echo $row['forma_id']?></td>
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
<script src="../js/menu.js"></script>
</body>
</html>
<?php } else{
	$url_de_destino = "../login.php";
	include "../includes/redireciona.php";
}
?>