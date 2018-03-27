<?php
include("trataerro.php");
include "ajustadataehora.php";
if (($_POST['login']) AND ($_POST['password'])) {
   include "conect.php";
   $select = (mysql_query("SELECT usuario_login, usuario_nome, usuario_nivel, usuario_admin, usuario_adminpainel, usuario_cadastro, usuario_convenio, usuario_compras, usuario_estoque, usuario_financeiro, usuario_hospital, usuario_painel FROM usuario WHERE usuario_login='".$_POST['login']."' AND usuario_senha='".md5($_POST['password'])."'"));	
   if (mysql_num_rows ($select)) {
	  session_start();
	  $_SESSION['login'] = mysql_result ($select, 0, 'usuario_login');
	  $_SESSION['nome']  = mysql_result ($select, 0, 'usuario_nome');
	  $_SESSION['nivel'] = mysql_result ($select, 0, 'usuario_nivel');

	  //ACESSOS
	  $_SESSION['acesso_admin'] 		= mysql_result ($select, 0, 'usuario_admin');
	  $_SESSION['acesso_adminpainel'] 	= mysql_result ($select, 0, 'usuario_adminpainel');
	  $_SESSION['acesso_cadastro'] 		= mysql_result ($select, 0, 'usuario_cadastro');
	  $_SESSION['acesso_convenio'] 		= mysql_result ($select, 0, 'usuario_convenio');
	  $_SESSION['acesso_compras'] 		= mysql_result ($select, 0, 'usuario_compras');
	  $_SESSION['acesso_estoque'] 		= mysql_result ($select, 0, 'usuario_estoque');
	  $_SESSION['acesso_financeiro'] 	= mysql_result ($select, 0, 'usuario_financeiro');
	  $_SESSION['acesso_hospital'] 		= mysql_result ($select, 0, 'usuario_hospital');
	  $_SESSION['acesso_painel'] 		= mysql_result ($select, 0, 'usuario_painel');	  
	  //ACESSOS
	  
	  $url_de_destino = '../index.php';
      @mysql_query("UPDATE usuario SET usuario_ultimo_acesso = usuario_novo_acesso, usuario_novo_acesso = '".date('Y-m-d H:i:s')."' WHERE usuario_login='".$_POST['login']."'");	
	} else {
	  $erro = urlencode ("usuario e/ou senha incorretos!");
	  $url_de_destino = "../login.php?erro=$erro";
	}
    } else {
	$erro = urlencode ("Informar um usuario e/ou senha!");
	$url_de_destino = "../login.php?erro=$erro";
    }
include "redireciona.php";
?>