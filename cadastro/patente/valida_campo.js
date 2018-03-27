function validacampo (valida_campo) {
	
if (frmcadastro.descricao.value == '') {
		alert ('É negado nulo, favor informar Descrição!');
		frmcadastro.descricao.focus();
		return false;
	}
if (frmcadastro.detalhe.value == '') {
		alert ('É negado nulo, favor informar Detalhe!');
		frmcadastro.detalhe.focus();
		return false;
	}
if (frmcadastro.ordem.value == '') {
		alert ('É negado nulo, favor informar Ordem!');
		frmcadastro.ordem.focus();
		return false;
	}	
	else return true;
	
}