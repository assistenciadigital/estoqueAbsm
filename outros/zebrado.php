<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="content-language" content="pt-br" />
<title>tabela zebrada</title>
<style type="text/css">
.cor-sim{
background-color: #F0F0F0; 
}

.cor-nao{
background-color: #FFFFFF; 
}

.cor-sim:hover,.cor-nao:hover{
background-color:#00FFFF; 
}

</style>
</head> 
<body>

<table border='0' cellspacing='0' width='50%'>

<?php

error_reporting(false);
// usei loop com FOR, mas pode ser um loop com resultado 
// de uma consulta ao banco de dados
for($c=0;$c<30;$c++){

// variavel para contar as linhas
$linha++;

// o segredo é essa linha, ela verifica se a linha é PAR ou IMPAR 
// e define a variavel com a classe CSS correspondente
$cor=($linha%2)?"cor-sim":"cor-nao"; 

// agora é so mostrar o resultado
echo "<tr class=\"$cor\"><td>linha $c</td></tr>\n ";

}// fim do loop
?>
</table> 

</body>
</html>