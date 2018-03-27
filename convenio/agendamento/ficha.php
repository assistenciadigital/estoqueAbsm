<?php
session_start();

// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
include("../../includes/conect.php");
include("../../includes/data.php");

//CONECTA COM O MYSQL

$select = mysql_query("select convenio_empregado.nome as titular, convenio_empregado.nascimento as nascimento_titular, convenio_empregado.sexo as sexo_titular, convenio_empregado.empresa as empresa, convenio_dependente.nome as dependente, convenio_dependente.nascimento as nascimento_dependente, convenio_dependente.sexo as sexo_dependente, convenio_encaminha.id as id_encaminha, convenio_encaminha.numero_guia as numero_guia, convenio_encaminha.data_guia as data_guia, convenio_encaminha.data_atende as data_atende, convenio_encaminha.hora_atende as hora_atende, status.status_descricao as status, convenio_encaminha.usuario_atende as usuario_atende, convenio_encaminha.tipo_especialidade as tipo_especialidade, especialidade.especialidade_descricao as especialidade, profissional_nome as profissional, profissional_conselho as conselho, profissional_endereco as endereco, profissional_bairro as bairro, profissional_cidade as cidade, profissional_uf as uf, profissional_cep as cep, profissional_telefone as telefone, profissional_celular as celular, convenio_encaminha.descricao as descricao, convenio_encaminha.data_agenda as data_agenda, convenio_encaminha.hora_agenda as hora_agenda, convenio_encaminha.observacao as observacao, destino_descricao as destino, convenio_encaminha.data as data, convenio_encaminha.usuario_login as usuario_login from ((((((convenio_encaminha INNER JOIN convenio_empregado ON convenio_empregado.id = convenio_encaminha.titular) LEFT JOIN convenio_dependente ON convenio_dependente.id = convenio_encaminha.dependente) LEFT JOIN profissional ON  profissional.profissional_id = convenio_encaminha.profissional) LEFT JOIN especialidade ON  especialidade.especialidade_id = convenio_encaminha.especialidade) LEFT JOIN destino ON  destino.destino_id = convenio_encaminha.destino) LEFT JOIN status ON  status.status_id = convenio_encaminha.status_atende) where convenio_encaminha.id = '".$_GET['id']."' order by convenio_encaminha.data_guia");

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
      $this->Cell(0,5,'PROTOCOLO DE ATENDIMENTO',1,1,'C');
	  $this->Ln();
	  $this->SetFont('Courier','B',10);
	  $this->Cell(0,4,'RELACAO DE CONVENIO - ENCAMINHAMENTO / AGENDAMENTO / ATENDIMENTO',0,1,'C');
      $this->SetFont('Courier','',10);
	  $this->Cell(0,5,'Impresso em: '.date('d/m/Y H:i').' Por: '.$_SESSION['login'],0,0,'L');
	  $this->Cell(0,4,'Pagina: '.$this->PageNo().' de: {nb}',0,1,'R');  
      $this->SetFont('Courier','',10);
	  //MONTA O CABEÇALHO DA TABELA
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

//apontamos a fonte que será utilizada no texto
$pdf->SetFont('Courier','',8);

//Aquí escribimos lo que deseamos mostrar...
$pdf->SetWidths(array(20, 26, 20, 60, 114, 30, 17));
srand(microtime()*1000000);

$pdf->SetFont('Courier','',8);

if (mysql_num_rows ($select)) {
  while ($row = mysql_fetch_assoc($select)) {
		$empresa = @mysql_fetch_assoc(mysql_query("SELECT inscricao, razao FROM convenio_empresa WHERE id = '".$row['empresa']."'"));

 	   $idadetitular = explode ("-", $row['nascimento_titular']);
	   $idadetitular = (date(Y)) - ($idadetitular[0]);
       
	   $idadedependente = explode ("-", $row['nascimento_dependente']);
	   $idadedependente = (date(Y)) - ($idadedependente[0]);

	   $pdf-> ln(4);
	   
	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(0,4, 'PACIENTE',1,1,'C');
	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'PROTOCOLO:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $row['id_encaminha'],1,1,'L');
	   

	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'NOME:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);

  	   if($row['dependente']) {
	   $pdf-> Cell(0,4, $row['dependente'],1,1,'L');
	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'DT NASCIMENTO:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, date_data($row['nascimento_dependente']).' Idade: ('.$idadedependente.') Sexo: '.$row['sexo_dependente'],1,1,'L');		   
	   }else{
   	   $pdf-> Cell(0,4, $row['titular'],1,1,'L');
	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'DT NASCIMENTO:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, date_data($row['nascimento_titular']).' Idade: ('.$idadetitular.') Sexo: '.$row['sexo_titular'],1,1,'L');		   
	   }
	
	   $pdf-> ln(4);
		
	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(0,4, 'AGENDAMENTO (local, profissional, especialidade, endereco, data e hora	)',1,1,'C');

	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'LOCAL:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $row['destino'],1,1,'L');

	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'PROFISSIONAL:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $row['profissional'].' '.$row['conselho'].' | '.$row['tipo_especialidade'],1,1,'L');

	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'ESPECIALIDADE:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $row['especialidade']." (".$row['descricao'].') ',1,1,'L');

	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'ENDERECO:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $row['endereco'].' ' .$row['bairro'].' '.$row['cidade'].' '.$row['uf'].' Telefone:'.$row['telefone'].' '.$row['celular'],1,1,'L');
	   
	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'DATA e HORA:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, date_data($row['data_agenda']).' às '.$row['hora_agenda'],1,1,'L');	   

	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'DESCRICAO:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $row['descricao'],1,1,'L');
	   
	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'OBSERVACAO:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $row['observacao'],1,1,'L');
	   
	   $pdf-> ln(4);
		
	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(0,4, 'TITULAR',1,1,'C');
	   $pdf-> Cell(25,4, 'NOME:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $row['titular'],1,1,'L');
	   
	   $pdf-> SetFont("Courier", 'B', 8);	   
	   $pdf-> Cell(25,4, 'EMPRESA:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $empresa['razao'],1,1,'L');

   	   if($row['dependente']) {
	   $pdf-> ln(4);
	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(0,4, 'DEPENDENTE',1,1,'C');
   	   $pdf-> Cell(25,4, 'NOME:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $row['dependente'],1,1,'L');
	   

	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(25,4, 'DT NASCIMENTO:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, date_data($row['nascimento_dependente']).' Idade: ('.$idadedependente.') Sexo: '.$row['sexo_dependente'],1,1,'L');
	   }
	
	   $pdf-> ln(4);
   	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(0,4, 'RESPONSAVEL PELO AGENDAMENTO',1,1,'C');
	   $pdf-> Cell(25,4, 'USUARIO:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4, $row['usuario_login'].' em: '.datetime_datatempo($row['data']),1,1,'L');
	
       if($row['data_atende']) {
	   $pdf-> ln(4);
	   $pdf-> SetFont("Courier", 'B', 8);
	   $pdf-> Cell(0,4, 'CONCLUSAO DO ATENDIMENTO',1,1,'C');
   	   $pdf-> Cell(25,4, 'DATA e HORA:',1,0,'R');
	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4,  date_data($row['data_atende']).' às '.$row['hora_atende'],1,1,'L');
	   $pdf-> SetFont("Courier", 'B', 8);
   	   $pdf-> Cell(25,4, 'SITUACAO:',1,0,'R');
   	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4,  $row['status'],1,1,'L');	   
	   $pdf-> SetFont("Courier", 'B', 8);
   	   $pdf-> Cell(25,4, 'ATENDENTE:',1,0,'R');
   	   $pdf-> SetFont("Courier", '', 8);
	   $pdf-> Cell(0,4,  $row['usuario_atende'],1,1,'L');		   
	   }	   
	  }
}

mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("ficha.pdf", "I");
?> 
<script>window.open('ficha.pdf'); </script>