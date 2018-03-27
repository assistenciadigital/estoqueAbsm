<?php

session_start();

// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
include("../../../includes/conect.php");

	$select = mysql_query("select codigobarras from teste_codigobarras");	

//VERIFICA SE RETORNOU ALGUMA LINHA
if(!mysql_num_rows($select)) { echo "Não retornou registro(s)"; die; }

require('ean13.php');

$pdf=new PDF_EAN13();
$pdf->AddPage();

if (mysql_num_rows ($select)) {
  while ($row = mysql_fetch_assoc($select)) {		  
		$pdf->EAN13(80,40,$row['codigobarras']);
  }
}

mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("codigo_de_barras_ean-13.pdf", "I");

?> 

<script>window.open('codigo_de_barras_ean-13.pdf'); </script>