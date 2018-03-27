<?php
function soma_date_data($data, $dias){
	$d = explode ("-", $data);
	$sec = mktime(0, 0, 0, $d[1], $d[2], $d[0]);
	
	$total = $sec + (86400*$dias);
	//return $total;
	return date("d/m/Y", $total);
}

function soma_data_date($data, $dias){
	$d = explode ("/", $data);
	$sec = mktime(0, 0, 0, $d[1], $d[2], $d[1]);
	
	$total = $sec + (86400*$dias);
	//return $total;
	return date("Y-m-d", $total);
}
?> 
