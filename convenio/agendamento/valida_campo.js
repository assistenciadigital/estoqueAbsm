function validacampo (valida_campo) {

if (frmcadastro.numero_guia.value == '') {
		alert ('É negado nulo, favor informe Numero da Guia de Encaminhamento!');
		frmcadastro.numero_guia.focus();
		return false;
	}

if (frmcadastro.data_guia.value == '') {
		alert ('É negado nulo, favor informe a Data da Guia de Encaminhamento!');
		frmcadastro.data_guia.focus();
		return false;
	}

	
if (frmcadastro.titular.value == '') {
		alert ('É negado nulo, favor selecione Titular!');
		frmcadastro.titular.focus();
		return false;
	}
	
if (frmcadastro.autorizador.value == '') {
		alert ('É negado nulo, favor informar Autorizador!');
		frmcadastro.autorizador.focus();
		return false;
	}
	
if (frmcadastro.tipo[0].checked == false && frmcadastro.tipo[1].checked == false){
		alert ('Por Favor selecione o Tipo da Especialidade!');
		return false;
	}		
					
if (frmcadastro.especialidade.value == '') {
		alert ('É negado nulo, favor selecione Especialidade!');
		frmcadastro.especialidade.focus();
		return false;
	}

	else return true;
	
}