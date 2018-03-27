function validacampo (valida_campo) {
	
if (frmcadastro.fornecedor.value == '') {
		alert ('É negado nulo, favor Selecione Fornecedor!');
		frmcadastro.fornecedor.focus();
		return false;
	}		
if (frmcadastro.datapedido.value == '') {
		alert ('É negado nulo, favor Informar Data do Pedido!');
		frmcadastro.datapedido.focus();
		return false;
	}
		
		else return true;
	
}