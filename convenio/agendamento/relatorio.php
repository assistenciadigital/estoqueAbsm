<?php
session_start();

// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
include("../../includes/conect.php");
include("../../includes/data.php");

//CONECTA COM O MYSQL

$select = mysql_query("select convenio_empregado.nome as titular, convenio_empregado.nascimento as nascimento_titular, convenio_empregado.sexo as sexo_titular, convenio_dependente.nome as dependente, convenio_dependente.nascimento as nascimento_dependente, convenio_dependente.sexo as sexo_dependente, convenio_encaminha.id, convenio_encaminha.numero_guia, convenio_encaminha.data_guia, convenio_encaminha.tipo_especialidade, especialidade.especialidade_descricao as especialidade, profissional_nome as profissional, convenio_encaminha.descricao, convenio_encaminha.data_agenda, convenio_encaminha.hora_agenda, convenio_encaminha.data_atende, convenio_encaminha.hora_atende, status.status_descricao as status, convenio_encaminha.usuario_atende, convenio_encaminha.observacao, convenio_encaminha.data, convenio_encaminha.usuario_login from (((((convenio_encaminha INNER JOIN convenio_empregado ON convenio_empregado.id = convenio_encaminha.titular) LEFT JOIN convenio_dependente ON convenio_dependente.id = convenio_encaminha.dependente) LEFT JOIN profissional ON  profissional.profissional_id = convenio_encaminha.profissional) LEFT JOIN especialidade ON  especialidade.especialidade_id = convenio_encaminha.especialidade) LEFT JOIN status ON  status.status_id = convenio_encaminha.status_atende) where convenio_encaminha.titular = '".$_GET['titular']."' order by convenio_encaminha.data_guia");

	// Quantidade de registros pra paginação
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro   = mysql_num_rows(mysql_query("SELECT * from convenio_encaminha"));
	
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
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'J';//ALINHAMENTO
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
	  $this->Cell(0,4,'RELACAO DE CONVENIO - ENCAMINHAMENTO / AGENDAMENTO / ATENDIMENTO',0,1,'C');
      $this->SetFont('Courier','',10);
	  $this->Cell(0,5,'Impresso em: '.date('d/m/Y H:i').' Por: '.$_SESSION['login'],0,0,'L');
	  $this->Cell(0,4,'Pagina: '.$this->PageNo().' de: {nb}',0,1,'R');  
      $this->SetFont('Courier','',10);
	  //MONTA O CABEÇALHO DA TABELA
		$this->SetFont('Courier','B',8);
		$this->Cell( 10, 4, "ID", 1, 0, 'L');
		$this->Cell( 26, 4, "CONTROLE", 1, 0, 'L');
		$this->Cell( 20, 4, "AGENDAMENTO", 1, 0, 'L');
		$this->Cell(60, 4, "TITULAR / DEPENDENTE", 1, 0, 'L');
		$this->Cell(114, 4, "ESPECIALIDADE", 1, 0, 'L');
		$this->Cell(30, 4, "DATA", 1, 0, 'L');
		$this->Cell(17, 4, "USUARIO", 1, 1, 'L');
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
$pdf->SetFont('Courier','',8);

//Aquí escribimos lo que deseamos mostrar...
$pdf->SetWidths(array(10, 26, 20, 60, 114, 30, 17));
srand(microtime()*1000000);

$pdf->SetFont('Courier','',8);

if (mysql_num_rows ($select)) {
  while ($row = mysql_fetch_assoc($select)) {

 	   $idadetitular = explode ("-", $row['nascimento_titular']);
	   $idadetitular = (date(Y)) - ($idadetitular[0]);
       
	   $idadedependente = explode ("-", $row['nascimento_dependente']);
	   $idadedependente = (date(Y)) - ($idadedependente[0]);

	  $pdf->Row(array($row['id'], $row['numero_guia']."\n".date_data($row['data_guia']), date_data($row['data_agenda']).$row['hora_agenda']."\n".date_data($row['data_atende']).$row['hora_atende'], $row['titular']."\n".date_data($row['nascimento_titular']).' Idade: ('.$idadetitular.') Sexo: '.$row['sexo_titular']."\n".$row['dependente']."\n".date_data($row['nascimento_dependente']).' Idade: ('.$idadedependente.') Sexo: '.$row['sexo_dependente'], $row['profissional']."\n".$row['tipo_especialidade'].': '.$row['especialidade']." (".$row['descricao'].') '."\n".$row['observacao']."\n".$row['status'].' Por: '.$row['usuario_atende'], datetime_datatempo($row['data']), $row['usuario_login']));
	  }
}

$pdf-> SetFont("Courier", 'B', 8);
$pdf-> Cell(0,4, number_format(($qtde_pesquisado), 0, ',', '.')." Registro(s)",1,0,'C');

mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("relacao_agendamento.pdf", "I");
?> 
<script>window.open('relacao_agendamento.pdf'); </script>