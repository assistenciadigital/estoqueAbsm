function validacampo (valida_campo) {
	
if (frmcadastro.descricao.value == '') {
		alert ('É negado nulo, favor informar Descrição!');
		frmcadastro.descricao.focus();
		return false;
	}

	else return true;
	
}