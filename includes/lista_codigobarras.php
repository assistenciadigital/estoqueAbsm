<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

include "conect.php";

header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$sql = "SELECT produto_codigobarras FROM produto ORDER BY produto_codigobarras";
$qr = mysql_query($sql) or die(mysql_error());

   while($ln = mysql_fetch_assoc($qr)){
   echo 'value="'.$ln['produto_codigobarras'].'">';
}
?>
</head>
</html>