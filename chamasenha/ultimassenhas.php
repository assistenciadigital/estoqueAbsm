<?php
	date_default_timezone_set("America/Cuiaba");
	setlocale(LC_ALL, 'pt_BR');	

	function datetime_datatempo ($e) {
		$p = explode (" ", $e);
		$d = explode ("-", $p[0]);
		$s = "$d[2]/$d[1]|";
		$s .= substr ($p[1], 0, 5);
		if ($e) return $s;
	}
	
	include ("../painel/funcoesPHP/conexao.php");
	
	$consulta = mysql_query("SELECT * FROM chamasenha where status = 'Chamado' order by tipo Desc, id Desc limit 1, 6");
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <th width="15%" align="left" valign="middle" style="font:'Arial Black', Gadget, sans-serif; color: #FF0000; font-size:18px"><strong>Senha</strong></th>
            <th width="40%" align="left" valign="middle" style="font:'Arial Black', Gadget, sans-serif; color: #FF0000; font-size:18px"><strong>Setor</strong></th>
            <th width="20%" align="left" valign="middle" style="font:'Arial Black', Gadget, sans-serif; color: #FF0000; font-size:18px"><strong>Sala</strong></th>
            <th width="25%" align="left" valign="middle" style="font:'Arial Black', Gadget, sans-serif; color: #FF0000; font-size:18px"><strong>Data|Hora</strong></th>
          </tr>
</table>
<?php
	if (@mysql_num_rows($consulta)){
		while ($c = mysql_fetch_assoc ($consulta)){
			$i++;
			if ($i%2) $cor = '#F0F0F0';
				else $cor = '#FFFFFF';
?>  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
          <tr>
             <th width="15%" bgcolor="<?php echo $cor?>" align="left" valign="middle" style="font:'Arial Black', Gadget, sans-serif; color: #FF0000; font-size:18px"><strong><?php echo $c['tipo'];?><?php echo $c['id'];?></strong></th>
             <th width="40%" bgcolor="<?php echo $cor?>" align="left" valign="middle" style="font:'Arial Black', Gadget, sans-serif; color: #FF0000; font-size:18px"><strong><?php echo $c['setor'];?></strong></th>
             <th width="20%" bgcolor="<?php echo $cor?>" align="left" valign="middle" style="font:'Arial Black', Gadget, sans-serif; color: #FF0000; font-size:18px"><strong><?php echo $c['sala'];?></strong></th>
             <th width="25%" bgcolor="<?php echo $cor?>" align="left" valign="middle" style="font:'Arial Black', Gadget, sans-serif; color: #FF0000; font-size:18px"><strong><?php echo datetime_datatempo($c['chamado']);?></strong></th>
          </tr>
</table>
<?php }}?>