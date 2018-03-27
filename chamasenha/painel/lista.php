<?php
session_start();
if(($_SESSION['login']) AND($_SESSION['nivel'])){
	
	include "../../painel/funcoesPHP/conexao.php";
	include "../../painel/funcoesPHP/data.php";
		
	//######### INICIO Paginação
	$numreg = 9999; // Quantos registros por página vai ser mostrado
	if (!isset($pg)) {
	$pg = 0;
	}

	$inicial = @$_GET['pg'] * $numreg;
	$registro_por_pagina = $inicial + $numreg;
		
	//######### FIM dados Paginação
	
	// Faz o Select pegando o registro inicial até a quantidade de registros para página
	//$sql = mysql_query("select * from convenio_empresa order by razao LIMIT $inicial, $numreg");
	
	// Serve para contar quantos registros você tem na seua tabela para fazer a paginação
	$sql_conta=mysql_query("SELECT * FROM chamasenha order by id Desc");
	$quantreg=mysql_num_rows($sql_conta); // Quantidade de registros pra paginação
	$consulta=mysql_query("SELECT * FROM chamasenha order by id Desc LIMIT $inicial, $numreg");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../painel/css/estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../painel/funcoesJS/jquery-1.3.1.min.js"></script>
<script type="text/javascript" src="../../painel/funcoesJS/funcoes.js"></script>

<script language="javascript">
function excluir (id){
	if(confirm ('Você tem certeza de que deseja excluir este registro? '+ id)) {
		location = 'processando.php?acao=deleta&id='+id;
	}
}
// Fim Evento
</script>
</head>
<body onLoad="reajusta()">

<div class="conteudo" align="center" height:"500px">

<table width="800" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr align="center" valign="top">
    <th height="400" scope="col">

  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="10%" align="center" valign="middle" bgcolor="#4DBDCB"><img src="icon.png"></td>
      <td width="90%" align="left" bgcolor="#4DBDCB"><h2 class="txtBranco"><strong>GERENCIAMENTO PAINEL DE ATENDIMENTO</strong></h2></td>
    </tr>
    <tr>
      <td width="10%">&nbsp;</td>
      <td width="90%">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" bgcolor="#F0F0F0"><img src="../../painel/imgs/i_novo.png"></td>
      <td onClick="location='cadastro.php'" onMouseOut="this.style.backgroundColor='#F0F0F0'" onMouseOver="this.style.backgroundColor='#E0E0E0'" style="cursor:hand" colspan="2" valign="middle" bgcolor="#F0F0F0" class="tip-tip" alt="Aqui você cadastrar os usuários que terão acesso ao seu Painel!"><h6><strong>Cadastrar Novo</strong></h6></td>
    </tr>
  </table>
   <br/>
  <div style="color:#009; width:820px; height: 286px; overflow: auto; vertical-align: left;">     
    <?php
    if (@mysql_num_rows($consulta)){
        while ($c = mysql_fetch_assoc ($consulta)){
	        $i++;
    	    if ($i%2) $cor = '#F0F0F0';
			else $cor = '#FFFFFF';
    ?>       
    <table width="800" border="0" cellspacing="1" cellpadding="1" bgcolor="#E0E0E0">
    <tr>
    <td width="10%" height="30" align="center" valign="middle" bgcolor="<?php echo $cor?>"><h2><?php echo $c['id'];?>&nbsp;</h2>  
    </td>
    <td width="29%" bgcolor="<?php echo $cor?>"><h2><?php echo $c['setor'].' Sl: '.$c['sala']?></h2><h4><?php echo $c['profissional']?></h4></td>
    <td width="12%" align="center" bgcolor="<?php echo $cor?>" ><h4><?php echo datetime_datatempo($c['gravado'])?></h4></td>
    <td width="12%" align="center" bgcolor="<?php echo $cor?>" ><h4><?php echo datetime_datatempo($c['chamado'])?></h4></td>
    <td width="12%" align="center" bgcolor="<?php echo $cor?>" ><h4><?php echo datetime_datatempo($c['finalizado'])?></h4></td>
    <td width="15%" bgcolor="<?php echo $cor?>" ><h4><?php echo $c['status']?></h4></td>
    <td width="5%" align="center" valign="middle" bgcolor="<?php echo $cor?>"><a href="finaliza.php?id=<?php echo $c['id']?>" class="tip-tip" alt="Clique aqui para Alterar o usuário: <?php echo $c['nome'] ?>"><img name="bt_editar" src="../../painel/imgs/i_desaprovar.png" width="30" height="30" border="0" alt=""></a></td>
    <td width="5%" align="center" valign="middle" bgcolor="<?php echo $cor?>"><a href="chama.php?id=<?php echo $c['id']?>" class="tip-tip" alt="Clique aqui para Alterar o usuário: <?php echo $c['nome'] ?>"><img name="bt_editar" src="../../painel/imgs/chamasenha.jpg" width="29" height="29" border="0" alt=""></a></td>
    </tr>
</table>  
  <?php }}?>
  </div>
    </th>
  </tr>
</table>

    <table width="800px" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="70%" align="left"  valign="middle"><h6><strong><?php //include("../../painel/funcoesPHP/paginacao.php")?></strong></h6></td>
          <td width="30%" align="right" valign="middle"><h6><strong><?php if ($registro_por_pagina > $quantreg){$registro_por_pagina = $quantreg;}else{$registro_por_pagina=$registro_por_pagina;}print 'Registro(s) ['.$quantreg.']'?></strong></h6></td>
         </tr>
                   <tr>
          <td width="100%" colspan="2" align="right" class="voltar"><img src="../../painel/imgs/space.png" width="10" height="25"><a href="../../abertura.php"><img src="../../painel/imgs/bt_voltar.png" border="0"></a><img src="../../painel/imgs/space.png" width="10" height="25"><img src="../../painel/imgs/spaceT.png" width="20" height="25"></td>
        </tr>
    </table>
</div>
</body>
</html>
<?php }
else {
	$url_de_destino = "../../expira.php";
	$target = "_parent";
	include "../../painel/funcoesPHP/redireciona.php";
}
?>