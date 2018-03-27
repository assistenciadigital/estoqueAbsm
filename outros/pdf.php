<?php
// AJUSTA DATA DE HORA PADRAO MT
include "../../includes/ajustadataehora.php";
$data = date('d/m/Y');
$hora = date('H:i');

//CONFIGURAÇÕES DO BD MYSQL
include("../../includes/conect.php");

//fazemos a inclusão do arquivo com a classe FPDF
require('../../fpdf/fpdf.php');

//criamos uma nova classe, que será uma extensão da classe FPDF
//para que possamos sobrescrever o método Header()
//com a formatação desejada
class PDF extends FPDF
{
   //Método Header que estiliza o cabeçalho da página
   function Header() {
      //insere e posiciona a imagem/logomarca
      //$this->Image('logo.png',10,8,33);

      //Informa a fonte, seu estilo e seu tamanho     
      $this->SetFont('Arial','B',11);

      //Informa o tamanho do box que receberá o cabeçalho
      //o texto que ele conterá, suas bordas e o alinhaento do texto
      $this->Cell(0,5,'CONTROLE DE ESTOQUE FACIL - Versao: 01/2015',1,1,'C');
   }

   //Método Footer que estiliza o rodapé da página
   function Footer() {

      //posicionamos o rodapé a 1cm do fim da página
      $this->SetY(-15);
      
      //Informamos a fonte, seu estilo e seu tamanho
      $this->SetFont('Courier','I',8);

      //Informamos o tamanho do box que vai receber o conteúdo do rodapé
      //e inserimos o número da página através da função PageNo()
      //além de informar se terá borda e o alinhamento do texto
  	  $this->Cell(0,0,'',1,1,'C');
	  $this->Cell(0,4,'Assistencia Digital - Telefone: 55(65)9606-2605 - Todos os direitos reservados.',0,1,'C');
	  $this->Cell(0,4,'Website: http://www.assistenciadigital.info | E-mail: alex@assistenciadigital.info',0,1,'C');
      $this->Cell(0,4,'Pagina: '.$this->PageNo().' de: '.$this->page,0,0,'C');
   }

}

//Criamos o objeto da classe PDF
$pdf=new PDF();

//Inserimos a página
$pdf->AddPage();

//apontamos a fonte que será utilizada no texto
$pdf->SetFont('Courier','',8);

//Aquí escribimos lo que deseamos mostrar...
$pdf->Cell(40,10,'texto a ser exibido');


//limpamos o cache
ob_start ();
ob_clean();

//geramos a página
$pdf->Output();

?>