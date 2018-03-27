function validacampo (valida_campo) {
	
if (frmcadastro.destino.value == '') {
		alert ('É negado nulo, favor Selecione Destino!');
		frmcadastro.destino.focus();
		return false;
	}	
if (frmcadastro.fornecedor.value == '') {
		alert ('É negado nulo, favor Selecione Fornecedor!');
		frmcadastro.fornecedor.focus();
		return false;
	}		
if (frmcadastro.documento.value == '') {
		alert ('É negado nulo, favor Informar o Nº do Documento!');
		frmcadastro.documento.focus();
		return false;
	}		
if (frmcadastro.datadocumento.value == '') {
		alert ('É negado nulo, favor Informar Data do Documento!');
		frmcadastro.datadocumento.focus();
		return false;
	}
	
if (frmcadastro.valordocumento.value == '') {
		alert ('É negado nulo, favor Informar Valor do Documento!');
		frmcadastro.valordocumento.focus();
		return false;
	}	
	
		else return true;
	
}