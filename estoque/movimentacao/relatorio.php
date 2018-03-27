<?php
session_start();

include("../../includes/data.php");
// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
include("../../includes/conect.php");

	if ($_GET['datainicio'] and $_GET['datafim']){	
	
	$datainicio = data_date($_GET['datainicio']);
	$datafim    = data_date($_GET['datafim']);
    $datainicio = $datainicio.' 00:00:00';
	$datafim    = $datafim.' 23:59:59';

	$select = mysql_query("SELECT unidade.unidade_descricao as unidade_descricao, historicomovimento.local_id as local_id, localestoque.local_descricao as local_descricao, historicomovimento.historicomovimento_id as historicomovimento_id, historicomovimento.historicomovimento_data as historicomovimento_data, historicomovimento.produto_id as produto_id, historicomovimento_quantidade as historicomovimento_quantidade, historicomovimento_estoque_anterior, historicomovimento.tipomovimento_tipo as tipo, produto_descricao as produto_descricao, produto_peso as peso, produto_estoque as estoque, produto.produto_estoqueminimo, produto.produto_estoquemaximo, produto.produto_preco, produto.produto_custoatual FROM ((((historicomovimento INNER JOIN produto ON historicomovimento.produto_id = produto.produto_id) INNER JOIN localestoque ON localestoque.local_id = historicomovimento.local_id) INNER JOIN unidade ON unidade.unidade_id = produto.unidade_id) INNER JOIN tipomovimento ON tipomovimento.tipomovimento_id = historicomovimento.tipomovimento_id) where historicomovimento_data between '".$datainicio."%' and '".$datafim."%' order by historicomovimento_data ASC");	
	}else if ($_GET['localestoque']){
	$select = mysql_query("SELECT unidade.unidade_descricao as unidade_descricao, historicomovimento.local_id as local_id, localestoque.local_descricao as local_descricao, historicomovimento.historicomovimento_id as historicomovimento_id, historicomovimento.historicomovimento_data as historicomovimento_data, historicomovimento.produto_id as produto_id, historicomovimento_quantidade as historicomovimento_quantidade, historicomovimento_estoque_anterior, historicomovimento.tipomovimento_tipo as tipo, produto_descricao as produto_descricao, produto_peso as peso, produto_estoque as estoque, produto.produto_estoqueminimo, produto.produto_estoquemaximo, produto.produto_preco, produto.produto_custoatual FROM ((((historicomovimento INNER JOIN produto ON historicomovimento.produto_id = produto.produto_id) INNER JOIN localestoque ON localestoque.local_id = historicomovimento.local_id) INNER JOIN unidade ON unidade.unidade_id = produto.unidade_id) INNER JOIN tipomovimento ON tipomovimento.tipomovimento_id = historicomovimento.tipomovimento_id) where historicomovimento.local_id = '".$_GET['localestoque']."' order by historicomovimento_data ASC");	
	}else if ($_GET['id']){
	$select = mysql_query("SELECT unidade.unidade_descricao as unidade_descricao, historicomovimento.local_id as local_id, localestoque.local_descricao as local_descricao, historicomovimento.historicomovimento_id as historicomovimento_id, historicomovimento.historicomovimento_data as historicomovimento_data, historicomovimento.produto_id as produto_id, historicomovimento_quantidade as historicomovimento_quantidade, historicomovimento_estoque_anterior, historicomovimento.tipomovimento_tipo as tipo, produto_descricao as produto_descricao, produto_peso as peso, produto_estoque as estoque, produto.produto_estoqueminimo, produto.produto_estoquemaximo, produto.produto_preco, produto.produto_custoatual FROM ((((historicomovimento INNER JOIN produto ON historicomovimento.produto_id = produto.produto_id) INNER JOIN localestoque ON localestoque.local_id = historicomovimento.local_id) INNER JOIN unidade ON unidade.unidade_id = produto.unidade_id) INNER JOIN tipomovimento ON tipomovimento.tipomovimento_id = historicomovimento.tipomovimento_id) where historicomovimento.historicomovimento_id = '".$_GET['id']."' order by historicomovimento_data ASC");	
	}else{
	$select = mysql_query("SELECT unidade.unidade_descricao as unidade_descricao, historicomovimento.local_id as local_id, localestoque.local_descricao as local_descricao, historicomovimento.historicomovimento_id as historicomovimento_id, historicomovimento.historicomovimento_data as historicomovimento_data, historicomovimento.produto_id as produto_id, historicomovimento_quantidade as historicomovimento_quantidade, historicomovimento_estoque_anterior, historicomovimento.tipomovimento_tipo as tipo, produto_descricao as produto_descricao, produto_peso as peso, produto_estoque as estoque, produto.produto_estoqueminimo, produto.produto_estoquemaximo, produto.produto_preco, produto.produto_custoatual FROM ((((historicomovimento INNER JOIN produto ON historicomovimento.produto_id = produto.produto_id) INNER JOIN localestoque ON localestoque.local_id = historicomovimento.local_id) INNER JOIN unidade ON unidade.unidade_id = produto.unidade_id) INNER JOIN tipomovimento ON tipomovimento.tipomovimento_id = historicomovimento.tipomovimento_id) order by historicomovimento.historicomovimento_data ASC");
	}

	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from historicomovimento")); // Quantidade de registros pra paginação

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
	  $this->Cell(0,4,'MOVIMENTACAO DE PRODUTOS',0,1,'C');
      $this->SetFont('Courier','',10);
	  $this->Cell(0,5,'Impresso em: '.date('d/m/Y H:i').' Por: '.$_SESSION['login'],0,0,'L');
	  $this->Cell(0,4,'Pagina: '.$this->PageNo().' de: {nb}',0,1,'R');  
	  //MONTA O CABEÇALHO DA TABELA
		$this->SetFont('Courier','B',8);
		$this->Cell( 29,  4, "DATA", 0, 0, 'C');
		$this->Cell( 10,  4, "ID", 0, 0, 'C');
		$this->Cell(  5,  4, "TIPO", 0, 0, 'C');
		$this->Cell( 20,  4, "QTDE", 0, 0, 'R');
		$this->Cell( 20,  4, "ESTQ ANT", 0, 0, 'R');
		$this->Cell( 28,  4, "PRECO ATUAL R$", 0, 0, 'R');
		$this->Cell( 28,  4, "CUSTO ATUAL R$", 0, 0, 'R');
		$this->Cell( 28,  4, "CUSTO MEDIO R$", 0, 0, 'R');
		$this->Cell( 20,  4, "ESTQ MINIMO", 0, 0, 'R');
		$this->Cell( 20,  4, "ESTQ MAXIMO", 0, 0, 'R');
		$this->Cell( 20,  4, "AC ENT QTD", 0, 0, 'R');
		$this->Cell( 20,  4, "AC SAI QTD", 0, 0, 'R');
		$this->Cell( 0,  4, "LOCAL ESTQ", 0, 1, 'R');
		$this->Cell(  0,  0, '', 1, 1);
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

		$pdf->Cell( 29, 4, datetime_datatempo($row['historicomovimento_data']), 0, 0, 'C');		
		$pdf->Cell( 10, 4, $row['historicomovimento_id'], 0, 0, 'C');
		$pdf->Cell(  5, 4, $row['tipo'], 0, 0, 'C');
		if ($row['tipo']=='E'){
		$pdf->Cell( 20, 4, number_format($row['historicomovimento_quantidade'], 0, ',', '.'), 0, 0, 'R');
		}else{
		$pdf->Cell( 20, 4, number_format($row['historicomovimento_quantidade']*-1, 0, ',', '.'), 0, 0, 'R');
		} 	    
		$pdf->Cell( 20, 4, number_format($row['historicomovimento_estoque_anterior'], 0, ',', '.'), 0, 0, 'R');
		$pdf->Cell( 28, 4, number_format($row['produto_preco'], 2, ',', '.'), 0, 0, 'R');
		$pdf->Cell( 28, 4, number_format($row['produto_custoatual'], 2, ',', '.'), 0, 0, 'R');
		$pdf->Cell( 28, 4, number_format($row['produto_customedio'], 2, ',', '.'), 0, 0, 'R');
		$pdf->Cell( 20, 4, number_format($row['produto_estoqueminimo'], 0, ',', '.'), 0, 0, 'R');
		$pdf->Cell( 20, 4, number_format($row['produto_estoquemaximo'], 0, ',', '.'), 0, 0, 'R');
	  	$pdf->Cell( 20, 4, number_format($row['produto_acumulado_entrada_qtde'], 0, ',', '.'), 0, 0, 'R');
		$pdf->Cell( 21, 4, number_format($row['produto_acumulado_saida_qtde'], 0, ',', '.'), 0, 1, 'R');
	  	$pdf->SetFont('Courier','B',8);
		$pdf->Cell(  0, 4, $row['produto_id'].' - '.$row['produto_descricao'].' '.$row['unidade_descricao'].' '.number_format($row['produto_peso'], 0, ',', '.'), 0, 0, 'L');
		$pdf->Cell(0, 4, $row['local_descricao'], 0, 1, 'R');
		$pdf->SetFont('Courier','',8);		
		$pdf->Cell(  0, 0, '', 1, 1);
  }
}

if ($_GET['datainicio'] and $_GET['datafim']){
	$data = ($_GET['datainicio']).' 00:00:00  e '.($_GET['datafim']).' 23:59:59';
}else{
	$data = "*";
}	

if ($_GET['id']){
	$id = $_GET['id'];
}else{
	$id = "*";
}
	
if ($_GET['localestoque']){
	$local = @mysql_fetch_assoc (mysql_query ("SELECT local_descricao FROM localestoque where local_id ='".$_GET['localestoque']."'"));
	$local = $local['local_descricao'];
}else{
	$local = "*";
}

$pdf-> SetFont("Courier", 'B', 8);
$pdf-> Cell(0,4, number_format(($qtde_pesquisado), 0, ',', '.').' de '.number_format(($qtde_registro), 0, ',', '.')." Registro(s) [ID: ".$id." Entre: ".$data." Local: ".$local."]",1,1,'C');
$pdf->SetFont("Courier", '', 8);
$pdf-> Cell(0,4,'LEGENDAS:',0,1);
$pdf-> Cell(0,4,'* -> TODOS(AS)',0,1);

mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("movimentacao.pdf", "I");
?> 
<script>window.open('movimentacao.pdf'); </script>