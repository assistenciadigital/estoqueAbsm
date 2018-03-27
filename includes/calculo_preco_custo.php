<?php

$preco = 0;
$percentual = 0;
$custo = 0;

if(isset($_GET['precoatual']) and isset($_GET['percentualcustoatual'])){
	 
	$preco      = $_GET['precoatual'];
	$percentual = $_GET['percentualcustoatual'];
	$custo      = (($preco * $percentual / 100) + $preco);
	
}
// Mostra o resultado
if (empty($custo)) {
	echo 'Voc&ecirc; deve preencher ao menos um  dos campos.';
} else {
echo 'Voc&ecirc; deve plantar 
<b>'. number_format(ceil($soma/1.33), 0, ',', '.') .'</b> &aacute;rvores.';
}
?>