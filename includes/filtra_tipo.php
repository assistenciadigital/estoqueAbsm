﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

include "conect.php";

header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$sql = "SELECT id, nome from auxiliar where classificacao = '".$_POST['tipo']."' ORDER BY nome ASC";
$qr = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($qr) == 0){
   echo  '<option value="0">'.htmlentities('Selecione Tipo').'</option>';   
}else{
   echo '<option value=""></option>';
   while($ln = mysql_fetch_assoc($qr)){
   echo '<option value="'.$ln['nome'].'">'.$ln['nome'].'</option>';
   }
}
?>
</head>
</html>