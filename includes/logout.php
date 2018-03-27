<?php
session_start();
session_destroy();
session_unset();
$url_de_destino = '../index.php';
include "redireciona.php";
?>