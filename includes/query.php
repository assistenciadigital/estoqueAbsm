<?php

$menu = @$_GET["menu"];

switch ($menu)
{
case "cor":
include "../tabelas/cor/lista.php";
break;  
}
?>