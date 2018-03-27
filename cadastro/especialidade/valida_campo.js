function validacampo (valida_campo) {

if (frmcadastro.tipo.value == '') {
		alert ('É negado nulo, favor selecione Iipo!');
		frmcadastro.tipo.focus();
		return false;
	}	
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
	else return true;
	
}