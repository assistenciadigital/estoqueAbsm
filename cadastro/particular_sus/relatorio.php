<?php
session_start();

// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
include("../../includes/conect.php");

if ($_GET['id']){
	$select = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from produto where produto_id = '".$_GET['id']."'");	
	}else if ($_GET['depto']){
	$select = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from produto where depto_id = '".$_GET['depto']."'");	
	}else if ($_GET['grupo']){
	$select = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from produto where grupo_id = '".$_GET['grupo']."'");	
	}else{
	$select = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from produto where produto_id like '%".$_GET['id']."%' and depto_id like '%".$_GET['depto']."%' and grupo_id like '%".$_GET['grupo']."%' and produto_descricao like '%".$_GET['descricao']."%' order by produto_descricao");	
}

	// Quantidade de registros pra paginação
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro   = mysql_num_rows(mysql_query("SELECT * from produto"));

//VERIFICA SE RETORNOU ALGUMA LINHA
if(!mysql_num_rows($select)) { echo "Não retornou registro(s)"; die; }

//fazemos a inclusão do arquivo com a classe FPDF
require('../../../fpdf/fpdf.php');

//criamos uma nova classe, que será uma extensão da classe FPDF
//para que possamos sobrescrever o método Header()
//com a formatação desejada
class PDF extends FPDF
{
   //Método Header que estiliza o cabeçalho da página
   function Header() {
      //Informa a fonte, seu estilo e seu tamanho     
      $this->SetFont('Arial','B',10);
      $this->Cell(0,5,'ESTOQUE FACIL - Versao: 01/2015',1,1,'C');
	  $this->Ln();
	  $this->SetFont('Courier','B',10);
	  $this->Cell(0,4,'RELACAO DE PRODUTOS',0,1,'C');
      $this->SetFont('Courier','',10);
	  $this->Cell(0,5,'Impresso em: '.date('d/m/Y H:i').' Por: '.$_SESSION['login'],0,0,'L');
	  $this->Cell(0,4,'Pagina: '.$this->PageNo().' de: {nb}',0,1,'R');  
	  //MONTA O CABEÇALHO DA TABELA
		$this->SetFont('Courier','B',8);
		$this->SetFont("Courier", 'B', 8);
		$this->Cell(10,  4, "ID", 1, 0, 'C');
		$this->Cell(153, 4, "NOME GENERICO | DESCRICAO", 1, 0, 'L');
		$this->Cell(7,  4, "UND", 1, 0, 'L');
		$this->Cell(10,  4, "PESO", 1, 0, 'R');
		$this->Cell(17,  4, "ESTOQUE", 1, 0, 'R');
		$this->Cell(25,  4, "PRECO ATUAL R$", 1, 0, 'R');
		$this->Cell(25,  4, "CUSTO ATUAL R$", 1, 0, 'R');
		$this->Cell(15,  4, "MINIMO", 1, 0, 'R');
		$this->Cell(15,  4, "MAXIMO", 1, 1, 'R');
   }

   //Método Footer que estiliza o rodapé da página
   function Footer() {

      //posicionamos o rodapé a 1cm do fim da página
      $this->SetY(190);
      
      //Informamos a fonte, seu estilo e seu tamanho
      $this->SetFont('Courier','I',8);

      //Informamos o tamanho do box que vai receber o conteúdo do rodapé
      //e inserimos o número da página através da função PageNo()
      //além de informar se terá borda e o alinhamento do texto
  	  $this->Cell(0,0,'',1,1,'C');
	  $this->Cell(0,2,'Assistencia Digital - Telefone: 55(65)9606-2605 - Todos os direitos reservados.',0,1,'C');
	  $this->Cell(0,2,'Website: http://www.assistenciadigital.info | E-mail: alex@assistenciadigital.info',0,0,'C');
   }
}
//Criamos o objeto da classe PDF
$pdf=new PDF('L', 'mm', 'A4');

//Inserimos a página
$pdf->AddPage();
$pdf->AliasNbPages(); // SELECIONA O NUMERO TOTAL DE PAGINAS, USADO NO RODAPE

//Aquí escribimos lo que deseamos mostrar...

$pdf->SetFont('Courier','',8);
if (mysql_num_rows ($select)) {
  while ($row = mysql_fetch_assoc($select)) {
    $depto = @mysql_fetch_assoc (mysql_query ("SELECT depto_id, depto_descricao FROM departamento where depto_id ='".$row['depto_id']."'"));
	
	$grupo = @mysql_fetch_assoc (mysql_query ("SELECT grupo_id, grupo_descricao FROM grupo where grupo_id='".$row['grupo_id']."'"));
	
	$forma = @mysql_fetch_assoc (mysql_query ("SELECT forma_id, forma_descricao FROM forma where forma_id='".$row['forma_id']."'"));
	
	$unidade = @mysql_fetch_assoc (mysql_query ("SELECT unidade_id, unidade_descricao FROM unidade where unidade_id='".$row['unidade_id']."'"));
	
	$fabricante = @mysql_fetch_assoc (mysql_query ("SELECT fabricante_id, fabricante_descricao FROM fabricante where fabricante_id='".$row['fabricante_id']."'"));
	

    if (trim(ltrim(rtrim($row['produto_generico']))) !=  trim(ltrim(rtrim($row['produto_descricao'])))){$erro = 'DESCRICAO<>GENERICO';}

	$pdf->SetFont('Courier','B',8);
	$pdf->Cell( 10, 4, $row['produto_id'], 1, 0, 'C');
	$pdf->Cell(267, 4, $row['produto_descricao'].' '.$erro, 1, 1, 'L');
	$pdf->SetFont('Courier','',8);
	$pdf->Cell(163, 4, $row['produto_generico'], 1, 0, 'L');
	$pdf->Cell(  7, 4, $unidade['unidade_descricao'], 1, 0, 'L');
	$pdf->Cell( 10, 4, number_format($row['produto_peso'], 0, ',', '.'), 1, 0, 'R');
	$pdf->Cell( 17, 4, number_format($row['produto_estoque'], 0, ',', '.'), 1, 0, 'R');
	$pdf->Cell( 25, 4, number_format($row['produto_preco'], 2, ',', '.'), 1, 0, 'R');
	$pdf->Cell( 25, 4, number_format($row['produto_custoatual'], 2, ',', '.'), 1, 0, 'R');
	$pdf->Cell( 15, 4, number_format($row['produto_estoqueminimo'], 0, ',', '.'), 1, 0, 'R');
	$pdf->Cell( 15, 4, number_format($row['produto_estoquemaximo'], 0, ',', '.'), 1, 1, 'R');
	$pdf->Cell(0, 4, $grupo['grupo_descricao'].' | '.$forma['forma_descricao'].' | '.$depto['depto_descricao'], 1,0, 'L');
	$pdf->Cell(0, 4, $row['produto_codigobarras'].' L: '.$row['produto_lote'].' F: '.$row['produto_fabricacao'].' V: '.$row['produto_validade'].' '.$fabricante['fabricante_descricao'],0,1, 'R');
	$soma_produto_preco = $soma_produto_preco + $row['produto_preco'];
	$soma_produto_custoatual = $soma_produto_custoatual + $row['produto_custoatual'];
  }
}

if ($_GET['id']){
	$id = $_GET['id'];
}else{
	$id = "*";
}

if ($_GET['depto']){
	$depto = @mysql_fetch_assoc (mysql_query ("SELECT depto_descricao FROM departamento where depto_id ='".$_GET['depto']."'"));
	$depto = $depto['depto_descricao'];
}else{
	$depto = "*";
}

if ($_GET['grupo']){
	$grupo = @mysql_fetch_assoc (mysql_query ("SELECT grupo_descricao FROM grupo where grupo_id='".$_GET['grupo']."'"));
	$grupo = $grupo['grupo_descricao'];
}else{
	$grupo = "*";
}

if ($_GET['descricao']){
	$descricao = $_GET['descricao'];
}else{
	$descricao = "*";
}

$pdf-> SetFont("Courier", 'B', 8);
$pdf-> Cell(163,4, number_format(($qtde_pesquisado), 0, ',', '.').' de '.number_format(($qtde_registro), 0, ',', '.')." Registro(s) [ID:".$id." Depto:".$depto." Grupo:".$grupo." Descricao: ".$descricao."]",1,0,'C');
$pdf-> Cell(34,4,"PRECO ATUAL R$ ->",1,0,'R');
$pdf-> Cell(25,4,number_format(($soma_produto_preco), 2, ',', '.'),1,0,'R');
$pdf-> Cell(25,4,number_format(($soma_produto_custoatual), 2, ',', '.'),1,0,'R');
$pdf-> Cell(30,4,"<- PRECO CUSTO R$",1,1,'L');
$pdf->SetFont("Courier", '', 8);
$pdf-> Cell(0,4,'LEGENDAS:',0,1);
$pdf-> Cell(0,4,'* -> TODOS(AS)',0,1);

mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("particular_sus.pdf", "I");
?> 
<script>window.open('particular_sus.pdf'); </script>