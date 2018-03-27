<?php
session_start();

include("../../includes/data.php");
// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
include("../../includes/conect.php");
include "../../includes/converte_valor.php";
include "../../includes/ajustadataehora.php";


	$movimento = @mysql_fetch_assoc(mysql_query("SELECT movimento.movimento_id, movimento.movimento_origem, movimento.numero_documento, movimento.data_documento, movimento.valor_documento, movimento.usuario_login, movimento.data, fornecedor.fornecedor_razaosocial FROM movimento inner join fornecedor on movimento.movimento_origem = fornecedor.fornecedor_id where movimento.movimento_id = '".$_GET['movimento_id']."'"));	

	$lancamento = mysql_query ("SELECT historicomovimento.produto_id, historicomovimento.historicomovimento_quantidade, historicomovimento.historicomovimento_valorunitario, historicomovimento.historicomovimento_valorcusto, historicomovimento.historicomovimento_valortotal, (historicomovimento.historicomovimento_quantidade * historicomovimento.historicomovimento_valorcusto) as valor_total, produto.produto_descricao FROM historicomovimento INNER JOIN produto ON historicomovimento.produto_id = produto.produto_id where movimento_id ='".$_GET['movimento_id']."'");
	
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
	  $this->Cell(0,4,'MOVIMENTACAO DE PRODUTOS - ENTRADA POR NOTA FISCAL',0,1,'C');
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
$pdf->Cell(  0, 8, 'DADOS DA NOTA FISCAL', 0, 1, 'C');		
$pdf->Cell(  0, 0, '', 1, 1);
$pdf->SetFont('Courier','',10);
$pdf->Cell(  0, 4, 'FORNECEDOR           : '.$movimento['fornecedor_razaosocial'], 0, 1, 'L');		
$pdf->Cell(  0, 4, 'NUMERO DOCUMENTO / NF: '.$movimento['numero_documento'], 0, 1, 'L');		
$pdf->Cell(  0, 4, 'DATA DO DOCUMENTO    : '.date_data($movimento['data_documento']), 0, 1, 'L');		
$pdf->Cell(  0, 4, 'VALOR DO DOCUMENTO R$: '.number_format($movimento['valor_documento'], 2, ',', '.'), 0, 1, 'L');		
$pdf->Cell(  0, 4, 'RESPONSAVEL          : '.$movimento['usuario_login'], 0, 1, 'L');
$pdf->Cell(  0, 4, 'DATA DO LANCAMENTO   : '.datetime_datatempo($movimento['data']), 0, 1, 'L');
$pdf->Cell(  0, 0, '', 1, 1);
$pdf->SetFont('Courier','B',10);
$pdf->Cell(  0, 8, 'ITENS DA NOTA FISCAL - PRODUTOS', 0, 1, 'C');		
$pdf->SetFont('Courier','B',8);
$pdf->Cell(  0, 0, '', 1, 1);
$pdf->Cell( 15, 4, 'ID', 0, 0, 'C');
$pdf->Cell(116, 4, 'DESCRICAO', 0, 0, 'L');
$pdf->Cell( 20, 4, 'QTDE', 0, 0, 'R');
$pdf->Cell( 20, 4, 'UNITARIO', 0, 0, 'R');
$pdf->Cell( 20, 4, 'TOTAL', 0, 1, 'R');
if (mysql_num_rows ($lancamento)) {
  while ($row = mysql_fetch_assoc($lancamento)) {
	    $soma_valor_total = $soma_valor_total + $row['valor_total'];
		
  	    $pdf->SetFont('Courier','',8);
		$pdf->Cell( 15, 4, $row['produto_id'], 0, 0, 'C');
		$pdf->Cell(116, 4, $row['produto_descricao'], 0, 0, 'L');
		$pdf->Cell( 20, 4, number_format($row['historicomovimento_quantidade'], 0, ',', '.'), 0, 0, 'R');
		$pdf->Cell( 20, 4, number_format($row['historicomovimento_valorcusto'], 3, ',', '.'), 0, 0, 'R');
		$pdf->Cell( 20, 4, number_format($row['valor_total'], 2, ',', '.'), 0, 1, 'R');
  }
}
$pdf-> SetFont("Courier", 'B', 8);
$pdf-> Cell(95.5, 4, 'ITEN(S): '.number_format(($qtde_pesquisado), 0, ',', '.'),1,0,'L');
$pdf-> Cell(95.5, 4, 'VALOR TOTAL DOCUMENTO R$ '.number_format(($soma_valor_total), 2, ',', '.'),1,1,'R');

mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("entrada_por_nf.pdf", "I");
?> 
<script>window.open('entrada_por_nf.pdf'); </script>