document.getElementById("foot01").innerHTML =
"<br/>&copy;  " + new Date().getFullYear() + " Alex Bueno - Telefone: 55 (65) 9606-2605 - E-mail: <a href='mailto:alex@assistenciadigital.info'>alex@assistenciadigital.info</a>. Todos os direitos reservados. Acesse: <a href='http://www.assistenciadigital.info' target='_blank'>http://www.assistenciadigital.info</a>";

document.getElementById("nav01").innerHTML =
"<ul id='menu'>" +
"<li><a href='index.php'>Início</a></li>" +

"<li><a href=''>Administrador</a>" +
	         	"<ul>" +				  			  
				  "<li><a href='estoque/usuario/lista.php'            target='_self'>Usuários do Sistema</a></li>" +
	       		"</ul>" +
"</li>" +	

"<li><a href=''>Cadastro</a>" +
				"<ul>" +
				  "<li><a href='cadastro/assistencia/lista.php'       target='_self'>Assistência</a></li>" +	
				  "<li><a href='cadastro/classificacao/lista.php'     target='_self'>Classificação</a></li>" +
				  "<li><a href='cadastro/destino/lista.php'           target='_self'>Destino</a></li>" +
				  "<li><a href='cadastro/motivo/lista.php'            target='_self'>Motivo</a></li>" +
				  "<li><a href='cadastro/origem/lista.php'            target='_self'>Origem</a></li>" +
 				  "<li><a href='cadastro/status/lista.php'            target='_self'>Status</a></li>" +
				  "<li><a href='cadastro/tipo/lista.php'              target='_self'>Tipo</a></li>" +
				  "<li></li>" +
			      "<li><a href='cadastro/socioefetivo/lista.php'      target='_self'>Sócio Efetivo</a></li>" +
                  "<li><a href='cadastro/sociocontribuinte/lista.php' target='_self'>Sócio Contribuinte</a></li>" +
                  "<li><a href='cadastro/particular_sus/lista.php'    target='_self'>Particular / SUS</a></li>" +
 			    "</ul>" +
"</li>" +

"<li><a href=''>Convênio</a>" +
 				"<ul>" +
 				  "<li><a href='convenio/empresa/lista.php'        target='_self'>Empresa</a></li>" +
                  "<li><a href='convenio/empregado/lista.php'      target='_self'>Empregado</a></li>" +
				"</ul>" +
"</li>" +

"<li><a href=''>Estoque</a>" +
	         	"<ul>" +
				  "<li><a href='estoque/cliente/lista.php'       target='_self'>Cliente</a></li>" +	
				  "<li><a href='estoque/departamento/lista.php'  target='_self'>Departamento</a></li>" +
				  "<li><a href='estoque/empresa/lista.php'       target='_self'>Empresa</a></li>" +
				  "<li><a href='estoque/fabricante/lista.php'    target='_self'>Fabricante</a></li>" +
				  "<li><a href='estoque/forma/lista.php'         target='_self'>Forma</a></li>" +
				  "<li><a href='estoque/fornecedor/lista.php'    target='_self'>Fornecedor</a></li>" +	
                  "<li><a href='estoque/grupo/lista.php'         target='_self'>Grupo</a></li>" +
				  "<li><a href='estoque/localestoque/lista.php'  target='_self'>Local Estoque</a></li>" +
                  "<li><a href='estoque/medicamento/lista.php'   target='_self'>Medicamento</a></li>" +
				  "<li><a href='estoque/produto/lista.php'       target='_self'>Produto</a></li>" +
                  "<li><a href='estoque/tipomovimento/lista.php' target='_self'>Tipo Movimento</a></li>" +
                  "<li><a href='estoque/unidademedida/lista.php' target='_self'>Unidade Medida</a></li>" +	
				  "<li></li>" +
				  "<li><a href='estoque/movimentacao/lista.php'  target='_self'>Movimenta Estoque</a></li>" +
 	       		  "</ul>" +
"</li>" +

"<li><a href=''>Compras</a>" +
	         	"<ul>" +
				  "<li><a href='compras/cotacao/lista.php'       target='_self'>Cotacao</a></li>" +	
 	       		  "</ul>" +
"</li>" +

"<li><a href=''>Financeiro</a>" +
 				"<ul>" +
 				  "<li><a href='cadastro/formadepagamento/lista.php'  target='_self'>Forma de Pagto</a></li>" +
				"</ul>" +
"</li>" +

"<li><a href=''>Hospital</a>" +
 				"<ul>" +
 				  "<li><a href='cadastro/acomodacao/lista.php'        target='_self'>Acomodacao</a></li>" +				  
				  "<li><a href='cadastro/especialidade/lista.php'     target='_self'>Especialidade</a></li>" +
				  "<li><a href='cadastro/profissional/lista.php'      target='_self'>Profissional</a></li>" +
				"</ul>" +
"</li>" +

"<li><a href=''>Painel Chama Senha</a>" +
	         	"<ul>" +
				  "<li><a href='chamasenha/painel/lista.php' target='_self'>Gerenciar Painel</a></li>" +
				  "<li><a href='chamasenha/painel.php'       target='_self'>Exibir Painel</a></li>" +
	       		"</ul>" +
"</li>" +

"<li><a href=''>Tabelas</a>" +
				"<ul>" +
  				  "<li><a href='estoque/cidade/lista.php'             target='_self'>Cidade</a></li>" +	
				  "<li><a href='cadastro/cor_raca/lista.php'          target='_self'>Cor / Raça</a></li>" +
				  "<li><a href='cadastro/escolaridade/lista.php'      target='_self'>Escolaridade</a></li>" +
				  "<li><a href='cadastro/estado_civil/lista.php'      target='_self'>Estado Civil</a></li>" +			  
				  "<li><a href='cadastro/instituicao/lista.php'       target='_self'>Instituição</a></li>" +			  
				  "<li><a href='cadastro/ocupacao/lista.php'          target='_self'>Ocupação</a></li>" +
				  "<li><a href='cadastro/parentesco/lista.php'        target='_self'>Parentesco</a></li>" +
				  "<li><a href='cadastro/patente/lista.php'           target='_self'>Patente</a></li>" +				  
				  "<li><a href='cadastro/profissao/lista.php'         target='_self'>Profissão</a></li>" +				  
 			    "</ul>" +
"</li>" +		

"<li><a href='includes/logout.php'>Saír</a></li>" +
"</ul>";