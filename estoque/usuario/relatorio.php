<?php
session_start();

// AJUSTA DATA DE HORA PADRAO MT
date_default_timezone_set('America/Cuiaba');

//CONFIGURAÇÕES DO BD MYSQL
include("../../includes/conect.php");
include("../../includes/data.php");

//CONECTA COM O MYSQL
if ($_GET['login']){
	$select = mysql_query("SELECT usuario_login as usuario, usuario_senha, usuario_nome, usuario_nascimento, usuario_sexo, usuario_nivel, usuario_email, usuario_celular, usuario_telefone, usuario_ativo, usuario_msg, usuario_excluido, usuario_novo_acesso, usuario_admin, usuario_adminpainel, usuario_cadastro, usuario_convenio, usuario_compras, usuario_estoque, usuario_financeiro, usuario_hospital, usuario_painel, usuario_ultimo_acesso, usuario_logado, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from usuario where usuario_login like '%".$_GET['login']."%'");	
	}else{	
$select = mysql_query("select usuario_login as usuario, usuario_senha, usuario_nome, usuario_nascimento, usuario_sexo, usuario_nivel, usuario_email, usuario_celular, usuario_telefone, usuario_ativo, usuario_msg, usuario_excluido, usuario_novo_acesso, usuario_admin, usuario_adminpainel, usuario_cadastro, usuario_convenio, usuario_compras, usuario_estoque, usuario_financeiro, usuario_hospital, usuario_painel, usuario_ultimo_acesso, usuario_logado, date_format(`data`,'%d/%m/%Y %H:%i') as `data_formatada` from usuario where usuario_login like '%".$_GET['login']."%' and  usuario_nome like '%".$_GET['nome']."%' order by usuario_nome");
}

	// Quantidade de registros pra paginação
	$qtde_pesquisado = mysql_num_rows($select);
	$qtde_registro   = mysql_num_rows(mysql_query("SELECT * from usuario"));

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
	  $this->Cell(0,4,'RELACAO DE USUARIOS',0,1,'C');
      $this->SetFont('Courier','',10);
	  $this->Cell(0,5,'Impresso em: '.date('d/m/Y H:i').' Por: '.$_SESSION['login'],0,0,'L');
	  $this->Cell(0,4,'Pagina: '.$this->PageNo().' de: {nb}',0,1,'R');  
	  //MONTA O CABEÇALHO DA TABELA
		$this->SetFont('Courier','B',8);
		$this->Cell(4, 4, "A", 1, 0, 'C');
		$this->Cell(4, 4, "M", 1, 0, 'C');
		$this->Cell(4, 4, "E", 1, 0, 'C');
		$this->Cell(52, 4, "LOGIN", 1, 0, 'L');
		$this->Cell(96, 4, "NOME COMPLETO", 1, 0, 'L');
		$this->Cell(4, 4, "1", 1, 0, 'C');
		$this->Cell(4, 4, "2", 1, 0, 'C');
		$this->Cell(4, 4, "3", 1, 0, 'C');
		$this->Cell(4, 4, "4", 1, 0, 'C');
		$this->Cell(4, 4, "5", 1, 0, 'C');
		$this->Cell(4, 4, "6", 1, 0, 'C');
		$this->Cell(4, 4, "7", 1, 0, 'C');
		$this->Cell(4, 4, "8", 1, 0, 'C');
		$this->Cell(4, 4, "9", 1, 0, 'C');
		$this->Cell(29, 4, "ULTIMO ACESSO", 1, 0, 'C');
		$this->Cell(29, 4, "ULTIMA ALTERACAO", 1, 0, 'C');
		$this->Cell(23, 4, "USUARIO", 1, 1, 'L');
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

$pdf->SetFont('Courier','',8);
if (mysql_num_rows ($select)) {
  while ($row = mysql_fetch_assoc($select)) {
		$pdf->Cell(4, 4,  $row['usuario_ativo'], 1, 0, 'C');
		$pdf->Cell(4, 4,  $row['usuario_msg'], 1, 0, 'C');
		$pdf->Cell(4, 4,  $row['usuario_excluido'], 1, 0, 'C');
		$pdf->SetFont('Courier','B',8);
		$pdf->Cell(52, 4, $row['usuario'].' ', 1, 0, 'L');
		$pdf->SetFont('Courier','',8);
		$pdf->Cell(96, 4, $row['usuario_nome'], 1, 0, 'L');
		$pdf->Cell(4, 4, $row['usuario_admin'], 1, 0, 'C');
		$pdf->Cell(4, 4, $row['usuario_adminpainel'], 1, 0, 'C');
		$pdf->Cell(4, 4, $row['usuario_cadastro'], 1, 0, 'C');
		$pdf->Cell(4, 4, $row['usuario_compras'], 1, 0, 'C');
		$pdf->Cell(4, 4, $row['usuario_convenio'], 1, 0, 'C');
		$pdf->Cell(4, 4, $row['usuario_estoque'], 1, 0, 'C');
		$pdf->Cell(4, 4, $row['usuario_financeiro'], 1, 0, 'C');
		$pdf->Cell(4, 4, $row['usuario_hospital'], 1, 0, 'C');
		$pdf->Cell(4, 4, $row['usuario_painel'], 1, 0, 'C');
		$pdf->Cell(29, 4, datetime_datatempo($row['usuario_ultimo_acesso']), 1, 0, 'L');
		$pdf->Cell(29, 4, $row['data_formatada'], 1, 0, 'L');
		$pdf->Cell(23, 4, $row['usuario_logado'], 1, 1, 'L');
  }
}
if ($_GET['login']){
	$login = $_GET['login'];
}else{
	$login = "*";
}

if ($_GET['nome']){
	$nome = $_GET['nome'];
}else{
	$nome = "*";
}

$pdf-> SetFont("Courier", 'B', 8);
$pdf-> Cell(0,4, number_format(($qtde_pesquisado), 0, ',', '.').' de '.number_format(($qtde_registro), 0, ',', '.')." Registro(s) [Login: ".$login." Nome Contendo: ".$nome."]",1,1,'C');

$pdf->SetFont("Courier", '', 8);
$pdf-> Cell(0,4,'LEGENDAS:',0,1);
$pdf-> Cell(0,4,'A -> ATIVO?           ( S => SIM  |  N => NAO )',0,1);
$pdf-> Cell(0,4,'M -> RECEBE MENSAGEM? ( S => SIM  |  N => NAO )',0,1);
$pdf-> Cell(0,4,'E -> EXCLUIDO?        ( S => SIM  |  N => NAO )',0,1);
$pdf-> Cell(0,4,'* -> TODOS(AS)',0,1);
$pdf-> Cell(0,4,'ACESSOS: 1=>ADMIN 2=>ADMIN PAINEL 3=>CADASTRO 4=>COMPRAS 5=>CONVENIO 6=>ESTOQUE 7=>FINANCEIRO 8=>HOSPITAL 8=>PAINEL',0,0);


mysql_close();
//limpamos o cache
ob_start ();
ob_clean();

$pdf->Output("usuarios.pdf", "I");
?> 
<script>window.open('usuarios.pdf'); </script>