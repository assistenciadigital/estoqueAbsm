<?php
date_default_timezone_set("America/Cuiaba");
setlocale(LC_ALL, 'pt_BR');
?>

<script type="text/javascript">

var hoje       = new Date();// crio um objeto date do javascript
var diasemana  = hoje.getDay();
var dia 	   = hoje.getDate();
var mes        = hoje.getMonth();
var ano        = hoje.getFullYear();

var semana=new Array(6);
semana[0]='Excelente Domingo!';
semana[1]='Ótima Segunda-Feira!';
semana[2]='Ótima Terca-Feira!';
semana[3]='Ótima Quarta-Feira!';
semana[4]='Ótima Quinta-Feira!';
semana[5]='Ótima Sexta-Feira!';
semana[6]='Excelente Sabado!';

mes = mes + 1;

if (dia <= 9) dia = "0" + dia;
if (mes <= 9) mes = "0" + mes;
if (ano <= 19) ano = "19" + ano;

var digital = new Date(); // crio um objeto date do javascript
//digital.setHours(15,59,56); // caso não queira testar com o php, comente a linha abaixo e descomente essa
digital.setHours(<?php echo date("H,i,s"); ?>); // seto a hora usando a hora do servidor
<!--
function clock() {
var hora 	= digital.getHours();
var minuto 	= digital.getMinutes();
var segundo = digital.getSeconds();

digital.setSeconds(segundo + 1); // aqui que faz a mágica

	if(hora < 5)
	{
	   saudacao = ("Boa Noite,");
	}
	else
	if(hora < 8)
	{
	   saudacao = ("Bom Dia,");
	}
	else
	if(hora < 12)
	{
	   saudacao = ("Bom Dia,");
	}
	else
	if(hora < 18)
	{
	   saudacao = ("Boa Tarde,");
	}
	else
	{
	   saudacao = ("Boa Noite,");
	}

// acrescento zero
if (hora <= 9) hora = "0" + hora;
if (minuto <= 9) minuto = "0" + minuto;
if (segundo <= 9) segundo = "0" + segundo;

dispTime = saudacao + " " + semana[diasemana] + " " + dia + "/" + mes + "/" + ano + " " + hora + ":" + minuto + ":" + segundo;
document.getElementById("clock").innerHTML = dispTime; // coloquei este div apenas para exemplo
setTimeout("clock()", 1000); // chamo a função a cada 1 segundo

}
window.onload = clock;
//-->

</script>