<?php
include("includes/trataerro.php");
include("includes/ajustadataehora.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/login.css" rel="stylesheet">
<title>Logar no Sistema</title>

</head>

<body onLoad="formlogin.login.focus()">

<div align="center" id="main">
  <h1>Logar no Sistema</h1> 
  <h2>Tela de Autenticação</h2>
  <div align="center" width="450px">
  <form action="includes/acesso.php" method="post" name="formlogin" id="formlogin">
  <table border="0" align="center" cellpadding="10" cellspacing="10" style="background: #99FFCC; border-color:#000000;" name="menu">
    <tr><td rowspan="2" align="center" valign="middle"><img src="imgs/login.gif" alt="Login" /></td>
     <td align="right"><label for="erro"><b>Usuário:</b></label></td>
     <td><input name="login" id="user" size="15" maxlength="15" value="" tabindex="1" /></td></tr>
     <tr><td align="right"><label for="password"><b>Senha:</b></label></td>
     <td><input name="password" id="password" type="password" size="15" maxlength="15" tabindex="2" /></td></tr>
     <tr><td colspan="4" align="center"><input name="erro" disabled="disabled" id="erro" style="border-color: transparent; background: transparent; font:'Courier New', Courier, monospace; color: #FF0000" tabindex="1" value="<?php echo $_GET['erro']?>" size="40" maxlength="40" /></td></tr>
     <tr><td colspan="4" align="center"><input type="submit" value="Entrar" tabindex="4" style="height:25; width:30"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="Redefinir" type="reset" tabindex="4" value="Limpar" style="height:25; width:30"/></td></tr>
  </table>
  </form>
</div>
  <footer id="foot01"></footer>
</div>
<script src="js/script.js"></script>
</body>
</html>