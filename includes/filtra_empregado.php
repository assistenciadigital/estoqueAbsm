﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

include "conect.php";
include "data.php";

header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$sql = "SELECT id, numero_guia, data_guia FROM convenio_encaminha WHERE empregado= '".$_POST['empregado']."' ORDER BY numero_guia ASC";
$qr = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($qr) == 0){
   echo  '<option value="">'.htmlentities('Selecione Titular').'</option>';
   
}else{
   echo '<option value="">Guia  - Data</option>';
   while($ln = mysql_fetch_assoc($qr)){
   echo '<option value="'.$ln['id'].'">'.$ln['numero_guia'].' - '.date_data($ln['data_guia']).'</option>';
   }
}
?>
</head>
</html>