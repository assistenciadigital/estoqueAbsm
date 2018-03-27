<?php
session_start();

// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
require('codeean13.php');
include("../../../includes/conect.php");

if ($_GET['id']){
	$sql = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from produto where produto_id = '".$_GET['id']."' order by produto_id");	
	}else if ($_GET['depto']){
	$sql = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from produto where depto_id like '%".$_GET['depto']."%' and grupo_id like '%".$_GET['grupo']."%' and produto_descricao like '%".$_GET['descricao']."%' order by produto_descricao");	
	}else if ($_GET['grupo']){
	$sql = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from produto where depto_id like '%".$_GET['depto']."%' and grupo_id like '%".$_GET['grupo']."%' and produto_descricao like '%".$_GET['descricao']."%' order by produto_descricao");	
	}else{
	$sql = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from produto where produto_id like '%".$_GET['id']."%' and depto_id like '%".$_GET['depto']."%' and grupo_id like '%".$_GET['grupo']."%' and produto_descricao like '%".$_GET['descricao']."%' order by produto_descricao");	
}

	// Quantidade de registros pra paginação
	$qtde_pesquisado = mysql_num_rows($sql);
	$qtde_registro   = mysql_num_rows(mysql_query("SELECT * from produto"));
	
	$row =   mysql_num_rows($sql);
	
	//VERIFICA SE RETORNOU ALGUMA LINHA
	if(!mysql_num_rows($sql)) { echo "Não retornou registro(s)"; die; }	 
	
	//NUMERO DE RESULTADOS POR PÁGINA						 
	$por_pagina  =  16;	 // padrao 60
	
	//CALCULA QUANTAS PÁGINAS VÃO SER NECESSÁRIAS
	$paginas   =  ceil($row/$por_pagina);   
	
	$pdf=new PDF();
	
	//INICIALIZA AS VARIÁVEIS
	$linha_atual =  0;
	$inicio	     =  0;
	
	//PÁGINAS
	for($x=1; $x<=$paginas; $x++) {
	   //VERIFICA
	   $inicio	  =  $linha_atual;
	   $fim		  =  $linha_atual + $por_pagina;
	   if($fim > $row) $fim = $row;
	   
	   $pdf->Open();					
	   $pdf->AddPage();	
	   
	   $top  = 5;
	   $left = 5;		 
	  
	   //EXIBE OS REGISTROS	  
	   for($i=$inicio; $i<$fim; $i++) {
		   
		   $pdf->EAN13($left,$top,mysql_result($sql, $i, "produto_codigobarras"));
		   $pdf->SetFont('Courier','B',5);
		   $pdf->Text($left,$top+15,'L:');
		   $pdf->Text($left+2,$top+15,mysql_result($sql, $i, "produto_lote"));
		   $pdf->Text($left+13,$top+15,'F:');
		   $pdf->Text($left+15,$top+15,mysql_result($sql, $i, "produto_fabricacao"));
		   $pdf->Text($left+23,$top+15,'V:');
		   $pdf->Text($left+25,$top+15,mysql_result($sql, $i, "produto_validade"));
		  
		   $top  = $top + 18; // padrao + 25
								
		   $linha_atual++;
		   
	   }
	}
				  
mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("codigo_barras.pdf", "I");
?> 
<script>window.open('codigo_barras.pdf'); </script>