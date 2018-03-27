<?php
		// requer a variavel $url_de_destino. Opcional: $target
		if (!$target) $target = "_self";
		if (!$url_de_destino) $url_de_destino = '../../index.php';
		if ($resultado) echo "<script language='JavaScript'>alert('$resultado');</script>";
		echo "<form name=\"retorna\" method=\"post\" action=\"$url_de_destino\" target=\"$target\">\n\t";
		
		while(list($nome_campo, $valor) = each($_POST)) {
			if (($valor != NULL) AND ($valor != '')) echo "<input name=\"$nome_campo\" type=\"hidden\" value=\"$valor\">\n\t";
			
		}
			
		echo "</form>\n";
		echo "<script language=\"JavaScript\">document.retorna.submit();</script>";
?>

