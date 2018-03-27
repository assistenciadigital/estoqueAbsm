<?php 
include "conect.php"; 
//include "data.php";

$estabelecimento = @mysql_fetch_assoc (mysql_query ("SELECT fantasia, razao FROM estabelecimento"));
		
$usuario         = @mysql_fetch_assoc(mysql_query("SELECT usuario_login, usuario_nome, usuario_ultimo_acesso FROM usuario WHERE usuario_login = '".$_SESSION['login']."'"));

$data_ultimo_acesso = $usuario['usuario_ultimo_acesso'];
$data= datetime_datatempo($data_ultimo_acesso);
$data = str_replace ("-", "às", $data);
?>

<div align="right" style="color:#FFFFFF">
<?php echo 'Usuário: '.strtoupper($usuario['usuario_login']).' | ' .strtoupper($usuario['usuario_nome']).' | Seu Último Acesso Foi Em: '.$usuario_ultimo_acesso?>
</div>