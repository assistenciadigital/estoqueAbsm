<?php
session_start();
if(($_SESSION['login']) AND ($_SESSION['nivel'])){
	include ("../painel/funcoesPHP/conexao.php");
	include("../painel/funcoesPHP/ajustadataehora.php");
	include("../painel/funcoesPHP/saudacao.php");
	
	$inf = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));		
	$senha = @mysql_fetch_assoc (mysql_query ("SELECT id, tipo, setor, sala FROM chamasenha where status = 'Chamado' order by tipo Desc, id Desc limit 1"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PAINEL DE ATENDIMENTO</title>

<link rel="stylesheet" href="../painel/css_menu/style.css" />

<style type="text/css">
body,td,th {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
}
body {
	background-image: url(../painel/imgs/fundo.jpg);
	margin-top: 0px;
	margin-bottom: 0px;
	margin-left: 2px;
	margin-right: 2px;
}
</style>

<!-- Attach our CSS -->
<link rel="stylesheet" href="orbit/orbit-1.2.3.css">
	  	
<!-- Attach necessary JS -->
<script type="text/javascript" src="orbit/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="orbit/jquery.orbit-1.2.3.min.js"></script>	
		
			<!--[if IE]>
			     <style type="text/css">
			         .timer { display: none !important; }
			         div.caption { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000);zoom: 1; }
			    </style>
			<![endif]-->
		
		<!-- Run the plugin -->

<script type="text/javascript">
			$(window).load(function() {
				$('#featured').orbit();
			});
</script>

<script>
var tempoSegundos = 15;
var urlPesquisa = "actionControllers";

function Temporizar (){
  url = "";
  url = location.search.split(urlPesquisa);
  if(url = urlPesquisa){
 window.location.href = "../chamasenha/painel.php"
  }
  else{
    var numForm = document.getElementsByTagName("form");
    for(x=0; x < numForm.length; x++){
   document.forms[x].submit();
    }
 location.reload(true)
  }
}
function Inicializar (){
  setInterval (Temporizar, 1000*tempoSegundos);
}
window.onload = Inicializar;
</script>

</head>

<body> 
<div align="center">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<th width="80%" align="left"  valign="middle" scope="col">
<font color="#330099" size="5" face="Arial Black, Gadget, sans-serif"><?php echo $inf['razao']?></font></th>
<th width="20%" align="right" valign="middle" scope="col">
<font color="#330099" size="6" face="Arial Black, Gadget, sans-serif"><?php $dataHora = date("d/m/Y H:i");
echo $dataHora;?></font></th>
</tr>
</table>
<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="60%" align="left" valign="top" scope="col">
    <div id="featured"> 
         <img src="orbit/imagens/002.jpg"/>
         <img src="orbit/imagens/003.jpg"/>
         <img src="orbit/imagens/004.jpg"/>
         <img src="orbit/imagens/005.jpg"/>
         <img src="orbit/imagens/006.jpg"/>
         <img src="orbit/imagens/007.jpg"/>
         <img src="orbit/imagens/008.jpg"/>
         <img src="orbit/imagens/009.jpg"/>
         <img src="orbit/imagens/010.jpg"/>
         <img src="orbit/imagens/011.jpg"/>
         <img src="orbit/imagens/012.jpg"/>
         <img src="orbit/imagens/013.jpg"/>
     </div></td>
    <th width="40%" align="center" valign="top" scope="col">
    			<font color="#0000CC" size="+6" face="Arial Black, Gadget, sans-serif"><?php echo $senha['tipo'].''.$senha['id'];?></font>
                <br/><font color="#0000CC" size="+1" face="Arial Black, Gadget, sans-serif"><?php if ($senha['tipo'] == "N") echo "SENHA Normal "; else if ($senha['tipo'] == "P") echo "Senha Preferêncial "; echo $senha['tipo'].$senha['id']?></font>
                <br/><font color="#0000CC" size="+6" face="Arial Black, Gadget, sans-serif"><?php echo $senha['setor'];?></font>
                <br/><font color="#0000CC" size="+1" face="Arial Black, Gadget, sans-serif">SETOR</font>
                <br/><font color="#0000CC" size="+6" face="Arial Black, Gadget, sans-serif"><?php echo $senha['sala'];?></font>
                <br/><font color="#0000CC" size="+1" face="Arial Black, Gadget, sans-serif">SALA</font>
                <?php include("ultimassenhas.php")?></th>
  </tr>
  <tr>
    <th align="center" valign="middle" scope="col"><font color="#0000CC" size="+6" face="Arial Black, Gadget, sans-serif"><?php echo 'PAINEL DE ATENDIMENTO<br/>Senha: '.$senha['tipo'].''.$senha['id'].' | '.$senha['setor'].' | Sala:'.$senha['sala'];?></font></th>
    <th align="center" valign="middle" scope="col"><img src="../painel/imgs/fila_preferencial.png" width="351" height="96" /></th>
  </tr>
</table>
</div>
</body>
</html>
<?php } 
else{
	$url_de_destino = "login.php";
	include "painel/funcoesPHP/redireciona.php";
}
?>