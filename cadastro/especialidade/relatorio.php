<?php
session_start();

// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
include("../../includes/conect.php");

//CONECTA COM O MYSQL
if ($_GET['tipo'] and $_GET['id']){
$select = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from especialidade where especialidade_tipo = '".$_GET['tipo']."' and  especialidade_id = '".$_GET['id']."'order by especialidade_descricao");
}else{
$select = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from especialidade where especialidade_id like '%".$_GET['id']."%' and especialidade_tipo like '%".$_GET['tipo']."%' and especialidade_descricao like '%".$_GET['descricao']."%' order by especialidade_tipo, especialidade_descricao");
}

	// Quantidade de registros pra paginação
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro   = mysql_num_rows(mysql_query("SELECT * from especialidade"));

//VERIFICA SE RETORNOU ALGUMA LINHA
if(!mysql_num_rows($select)) { echo "Não retornou registro(s)"; die; }

//fazemos a inclusão do arquivo com a classe FPDF
require('../../../fpdf/fpdf.php');

//criamos uma nova classe, que será uma extensão da classe FPDF
//para que possamos sobrescrever o método Header()
//com a formatação desejada
class PDF extends FPDF
{
	
// aqui comeca as colunas alinhadas
var $widths;
var $aligns;

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'J'; //ALINHAMENTO
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);//ALTURA DA LINHA
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
   //Método Header que estiliza o cabeçalho da página
   function Header() {
      //Informa a fonte, seu estilo e seu tamanho     
      $this->SetFont('Arial','B',10);
      $this->Cell(0,5,'ESTOQUE FACIL - Versao: 01/2015',1,1,'C');
	  $this->Ln();
	  $this->SetFont('Courier','B',10);
	  $this->Cell(0,4,'RELACAO DE AREA MEDICA',0,1,'C');
      $this->SetFont('Courier','',10);
	  $this->Cell(0,5,'Impresso em: '.date('d/m/Y H:i').' Por: '.$_SESSION['login'],0,0,'L');
	  $this->Cell(0,4,'Pagina: '.$this->PageNo().' de: {nb}',0,1,'R');  
	  //MONTA O CABEÇALHO DA TABELA
		$this->SetFont('Courier','B',8);
		$this->Cell(0,0,'',1,1,'C');
		$this->Cell( 14, 4, "ID", 1, 0, 'L');
		$this->Cell( 23, 4, "TIPO", 1, 0, 'L');
		$this->Cell( 50, 4, "DESCRICAO", 1, 0, 'L');
		$this->Cell(135, 4, "DETALHE", 1, 0, 'L');
		$this->Cell(30, 4, "DATA", 1, 0, 'L');
		$this->Cell(25, 4, "USUARIO", 1, 1, 'L');
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

//apontamos a fonte que será utilizada no texto
//Aquí escribimos lo que deseamos mostrar...
$pdf->SetWidths(array(14, 23, 50, 135, 30, 25));
srand(microtime()*1000000);

$pdf->SetFont('Courier','',8);

if (mysql_num_rows ($select)) {
  while ($row = mysql_fetch_assoc($select)) {
	  $pdf->Row(array($row['especialidade_id'], $row['especialidade_tipo'], $row['especialidade_descricao'], $row['especialidade_detalhe'], $row['data_formatada'], $row['usuario_login']));
	  }
}

if ($_GET['tipo']){
	$tipo = $_GET['tipo'];
}else{
	$tipo = "*";
}

if ($_GET['id']){
	$id = $_GET['id'];
}else{
	$id = "*";
}

if ($_GET['descricao']){
	$descricao = $_GET['descricao'];
}else{
	$descricao = "*";
}

$pdf-> SetFont("Courier", 'B', 8);
$pdf-> Cell(0,4, number_format(($qtde_pesquisado), 0, ',', '.').' de '.number_format(($qtde_registro), 0, ',', '.')." Registro(s) [Tipo: ".$tipo." ID: ".$id." Descricao Contendo: ".$descricao."]",1,1,'C');
$pdf->SetFont("Courier", '', 8);
$pdf-> Cell(0,4,'LEGENDAS:',0,1);
$pdf-> Cell(0,4,'* -> TODOS(AS)',0,1);


mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("area_medica.pdf", "I");
?> 
<script>window.open('area_medica.pdf'); </script>