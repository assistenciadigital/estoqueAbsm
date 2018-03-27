<?php
session_start();
if(($_SESSION['login']) AND ($_SESSION['nivel'])){
if(($_SESSION['nivel'])<=2){

// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
include("../../includes/conect.php");

//CONECTA COM O MYSQL
if($_GET['uf'] and $_GET['cidade']){
$select = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from cidade where cidade_uf = '".$_GET['uf']."' and cidade_descricao like '%".$_GET['cidade']."%' order by cidade_uf, cidade_descricao");
}else{
$select = mysql_query("select *, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from cidade where cidade_uf like '%".$_GET['uf']."%' and cidade_descricao like '%".$_GET['cidade']."%' order by cidade_uf, cidade_descricao");
}
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro = mysql_num_rows(mysql_query("SELECT * from cidade")); // Quantidade de registros pra paginação
}
	else $resultado = "Você não tem permissão imprimir. Contacte o Administrador do Sistema!";	

	$url_de_destino = "../../index.php";
	include "../../includes/redireciona.php";
}
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
	  $this->Cell(0,4,'RELACAO DE CIDADES'.$filtra_uf,0,1,'C');
	  $this->Cell(0,4, $filtra_uf,0,1,'C');
      $this->SetFont('Courier','',10);
	  $this->Cell(0,5,'Impresso em: '.date('d/m/Y H:i').' Por: '.$_SESSION['login'],0,0,'L');
	  $this->Cell(0,4,'Pagina: '.$this->PageNo().' de: {nb}',0,1,'R');	  
	  //MONTA O CABEÇALHO DA TABELA
		$this->SetFont('Courier','B',8);
		$this->Cell(15, 4, "ID", 1, 0, 'C');
		$this->Cell(115, 4, "DESCRICAO", 1, 0, 'L');
		$this->Cell(6, 4, "UF", 1, 0, 'L');
		$this->Cell(29, 4, "DATA", 1, 0, 'C');
		$this->Cell(25, 4, "USUARIO", 1, 1, 'L');
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

$pdf->SetFont('Courier','',8);
if (mysql_num_rows ($select)) {
  while ($row = mysql_fetch_assoc($select)) {
		$pdf->Cell(15, 4,$row['cidade_id'], 1, 0, 'C');
		$pdf->Cell(115, 4, $row['cidade_descricao'], 1, 0, 'L');
		$pdf->Cell(6, 4,$row['cidade_uf'], 1, 0, 'L');
		$pdf->Cell(29, 4,$row['data_formatada'], 1, 0, 'L');
		$pdf->Cell(25, 4, $row['usuario_login'], 1, 1, 'L');
  }
}

if ($_GET['uf']){
	$uf = $_GET['uf'];
}else{
	$uf = "*";
}

if ($_GET['cidade']){
	$cidade = $_GET['cidade'];
}else{
	$cidade = "*";
}

$pdf-> SetFont("Courier", 'B', 8);
$pdf-> Cell(0,4, number_format(($qtde_pesquisado), 0, ',', '.').' de '.number_format(($qtde_registro), 0, ',', '.')." Registro(s) Listado(s) para UF: ".$uf." e Cidade Contendo: ".$cidade,1,1,'C');
$pdf->SetFont("Courier", '', 8);
$pdf-> Cell(0,4,'LEGENDAS:',0,1);
$pdf-> Cell(0,4,'* -> TODOS(AS)',0,1);

mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("cidades.pdf", "I");
?> 
<script>window.open('cidades.pdf'); </script>