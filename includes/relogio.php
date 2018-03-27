<?php 

session_start();
if(($_SESSION['login']) AND ($_SESSION['nivel'])){
	include "conect.php";
	
	// AJUSTA DATA DE HORA PADRAO MT
	date_default_timezone_set('America/Cuiaba');
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sem título</title>
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

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" scope="col"><div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF" align="left"><?php echo 'Usuário: '.strtoupper($usuario['usuario_login']).' | ' .strtoupper($usuario['usuario_nome']).' | Seu Último Acesso Foi Em: '.$data?></div></td>
    <td width="50%" scope="col"><div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF" align="right"><?php echo date('d/m/Y')?></div>
<div style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF" align="right" id="clock"></div></td>
  </tr>
</table>
</body>
</html>
<?php }else{
	   $url_de_destino = "login.php";
	   include "redireciona.php";}
?>