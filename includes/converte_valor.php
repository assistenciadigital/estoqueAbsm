<?php 

function formata_numero($numero,$loop,$insert,$tipo = "geral") {
	if ($tipo == "geral") {
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "valor") {
		/*
		retira as virgulas
		formata o numero
		preenche com zeros
		*/
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "convenio") {
		while(strlen($numero)<$loop){
			$numero = $numero . $insert;
		}
	}
	return $numero;
}

// aqui converte o valor do browser para o banco de dados
function moeda($get_valor) {
		 $source  = array('.', ','); 
		 $replace = array('', '.');
		 $valor   = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
		 return $valor; //retorna o valor formatado para gravar no banco
	 }
?>

