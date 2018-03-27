<?php
session_start();

include("../../includes/data.php");
// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
include("../../includes/conect.php");
include "../../includes/converte_valor.php";
include "../../includes/ajustadataehora.php";


	$pedido = @mysql_fetch_assoc(mysql_query("SELECT pedido.id, pedido.fornecedor, pedido.data_pedido, pedido.usuario_login, pedido.data, fornecedor.fornecedor_razaosocial FROM pedido inner join fornecedor on pedido.fornecedor = fornecedor.fornecedor_id where pedido.id = '".$_GET['pedido_id']."'"));	

	$lancamento = mysql_query ("SELECT historicopedido.produto, historicopedido.quantidade, historicopedido.valorunitario, historicopedido.valorcusto, produto.produto_descricao, produto.fabricante_id FROM historicopedido INNER JOIN produto ON historicopedido.produto = produto.produto_id where historicopedido.pedido ='".$_GET['pedido_id']."'");
	
	$qtde_pesquisado = mysql_num_rows($lancamento);

//VERIFICA SE RETORNOU ALGUMA LINHA
if(!mysql_num_rows($lancamento)) { echo "Não retornou registro(s)"; die; }

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
	  $this->Cell(0,4,'COTAÇÃO / PEDIDO DE COMPRAS',0,1,'C');
      $this->SetFont('Courier','',10);
	  $this->Cell(0,5,'Impresso em: '.date('d/m/Y H:i').' Por: '.$_SESSION['login'],0,0,'L');
	  $this->Cell(0,4,'Pagina: '.$this->PageNo().' de: {nb}',0,1,'R');  
	  //MONTA O CABEÇALHO DA TABELA
		/*$this->SetFont('Courier','B',8);
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
		$this->Cell(  0,  0, '', 1, 1);*/
   }

   //Método Footer que estiliza o rodapé da página
   function Footer() {

      //posicionamos o rodapé a 1cm do fim da página
      $this->SetY(285);
      
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
$pdf=new PDF('P', 'mm', 'A4');

//Inserimos a página
$pdf->AddPage();
$pdf->AliasNbPages(); // SELECIONA O NUMERO TOTAL DE PAGINAS, USADO NO RODAPE

//Aquí escribimos lo que deseamos mostrar...

$pdf->SetFont('Courier','B',10);
$pdf->Cell(  0, 8, 'DADOS DA COTAÇÃO / PEDIDO DE COMPRAS', 0, 1, 'C');		
$pdf->Cell(  0, 0, '', 1, 1);
$pdf->SetFont('Courier','',10);
$pdf->Cell(  0, 4, 'FORNECEDOR        : '.$pedido['fornecedor_razaosocial'], 0, 1, 'L');		
$pdf->Cell(  0, 4, 'NUMERO DOCUMENTO  : '.$pedido['id'], 0, 1, 'L');		
$pdf->Cell(  0, 4, 'DATA DO DOCUMENTO : '.date_data($pedido['data_pedido']), 0, 1, 'L');		
$pdf->Cell(  0, 4, 'RESPONSAVEL       : '.$pedido['usuario_login'], 0, 1, 'L');
$pdf->Cell(  0, 4, 'DATA DO LANCAMENTO: '.datetime_datatempo($pedido['data']), 0, 1, 'L');
$pdf->Cell(  0, 0, '', 1, 1);
$pdf->SetFont('Courier','B',10);
$pdf->Cell(  0, 8, 'ITENS DA COTAÇÃO / PEDIDO DE COMPRAS', 0, 1, 'C');		
$pdf->SetFont('Courier','B',8);
$pdf->Cell(  0, 0, '', 1, 1);
$pdf->Cell(151, 4, 'DESCRICAO', 0, 0, 'L');
$pdf->Cell( 20, 4, 'QTDE', 0, 0, 'R');
$pdf->Cell( 20, 4, 'UNITARIO', 0, 1, 'R');
if (mysql_num_rows ($lancamento)) {
  while ($row = mysql_fetch_assoc($lancamento)) {
	  	$fabricante = @mysql_fetch_assoc (mysql_query ("SELECT fabricante_id, fabricante_descricao FROM fabricante where fabricante_id='".$row['fabricante_id']."'"));
	    $soma_valor_total = $soma_valor_total + $row['valor_total'];
		
  	    $pdf->SetFont('Courier','',8);
		$pdf->Cell(151, 8, $row['produto_descricao'].' Fabr.: '.$fabricante['fabricante_descricao'], 0, 0, 'L');
		$pdf->Cell( 20, 8, number_format($row['quantidade'], 0, ',', '.'), 0, 0, 'R');
		$pdf->Cell( 20, 8, '__________', 0, 1, 'R');

  }
}
$pdf-> SetFont("Courier", 'B', 8);
$pdf-> Cell(191, 4, 'ITEN(S): '.number_format(($qtde_pesquisado), 0, ',', '.'),1,1,'L');
$pdf-> Cell(0, 15, '',0,1,'C');
$pdf-> Cell(0, 3, '_______________________________________',0,1,'C');
$pdf-> Cell(0, 3, 'SOLICITANTE',0,1,'C');


//$pdf-> Cell(95.5, 4, 'VALOR TOTAL DOCUMENTO R$ '.number_format(($soma_valor_total), 2, ',', '.'),1,1,'R');

mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("pedido.pdf", "I");
?> 
<script>window.open('pedido.pdf'); </script>