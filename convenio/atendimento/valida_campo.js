function validacampo (valida_campo) {

if (frmcadastro.data_atende.value == '') {
		alert ('É negado nulo, favor informe a Data do Atendimento!');
		frmcadastro.data_atende.focus();
		return false;
	}

	
if (frmcadastro.hora_atende.value == '') {
		alert ('É negado nulo, favor informe a Hora do Atendimento!');
		frmcadastro.hora_atende.focus();
		return false;
	}
	
if (frmcadastro.status.value == '') {
		alert ('É negado nulo, favor Selecione o Status do Atendimento!');
		frmcadastro.status.focus();
		return false;
	}
	
	else return true;
	
}