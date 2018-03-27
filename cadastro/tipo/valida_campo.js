function validacampo (valida_campo) {
	
if (frmcadastro.descricao.value == '') {
		alert ('É negado nulo, favor informar Descrição!');
		frmcadastro.descricao.focus();
		return false;
	}
if (frmcadastro.classificacao.value == '') {
		alert ('É negado nulo, favor selecionar Classificação!');
		frmcadastro.classificacao.focus();
		return false;
	}
	else return true;
	
}