function validacampo (valida_campo) {
	
if (frmcadastro.codigobarras.value == '') {
		alert ('É negado nulo, favor informar Codigo de Barras!');
		frmcadastro.codigobarras.focus();
		return false;
	}
if (frmcadastro.descricao.value == '') {
		alert ('É negado nulo, favor informar Descrição!');
		frmcadastro.descricao.focus();
		return false;
	}
if (frmcadastro.generico.value == '') {
		alert ('É negado nulo, favor informar Generico!');
		frmcadastro.generico.focus();
		return false;
	}
if (frmcadastro.forma.value == '') {
		alert ('É negado nulo, favor selecione Forma!');
		frmcadastro.forma.focus();
		return false;
	}
if (frmcadastro.grupo.value == '') {
		alert ('É negado nulo, favor selecione Grupo!');
		frmcadastro.grupo.focus();
		return false;
	}
if (frmcadastro.depto.value == '') {
		alert ('É negado nulo, favor selecione Departamento!');
		frmcadastro.depto.focus();
		return false;
	}
if (frmcadastro.unidade.value == '') {
		alert ('É negado nulo, favor informar Unidade!');
		frmcadastro.unidade.focus();
		return false;
	}
if (frmcadastro.peso.value == '') {
		alert ('É negado nulo, favor informar Peso/Medida!');
		frmcadastro.peso.focus();
		return false;
	}
if (frmcadastro.precoatual.value == '') {
		alert ('É negado nulo, favor informar Preço Atual!');
		frmcadastro.precoatual.focus();
		return false;
	}
if (frmcadastro.custoatual.value == '') {
		alert ('É negado nulo, favor informar Custo Atual!');
		frmcadastro.custoatual.focus();
		return false;
	}
if (frmcadastro.estoqueminimo.value == '') {
		alert ('É negado nulo, favor informar Estoque Minimo!');
		frmcadastro.estoqueminimo.focus();
		return false;
	}
if (frmcadastro.estoquemaximo.value == '') {
		alert ('É negado nulo, favor informar Estoque Maximo!');
		frmcadastro.estoquemaximo.focus();
		return false;
	}
if (frmcadastro.lote.value == '') {
		alert ('É negado nulo, favor informar Lote!');
		frmcadastro.lote.focus();
		return false;
	}		
if (frmcadastro.fabricacao.value == '') {
		alert ('É negado nulo, favor informar Data de Fabricação!');
		frmcadastro.fabricacao.focus();
		return false;
	}		
if (frmcadastro.validade.value == '') {
		alert ('É negado nulo, favor informar Data de Validade!');
		frmcadastro.validade.focus();
		return false;
	}		
if (frmcadastro.fabricante.value == '') {
		alert ('É negado nulo, favor selecionar Fabricante!');
		frmcadastro.fabricante.focus();
		return false;
	}		
		else return true;
	
}