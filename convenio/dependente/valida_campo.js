function validacampo (valida_campo) {
	
if (frmcadastro.titular.value == '') {
		alert ('É negado nulo, favor selecione Titular!');
		frmcadastro.titular.focus();
		return false;
	}
	
if (frmcadastro.nome.value == '') {
		alert ('É negado nulo, favor informar Nome!');
		frmcadastro.nome.focus();
		return false;
	}

if (frmcadastro.nascimento.value == '') {
		alert ('É negado nulo, favor informar Nascimento!');
		frmcadastro.nascimento.focus();
		return false;
	}	
	
if (frmcadastro.sexo[0].checked == false && frmcadastro.sexo[1].checked == false){
		alert ('Por Favor selecione o Sexo!');
		return false;
	}		
					
if (frmcadastro.parentesco.value == '') {
		alert ('É negado nulo, favor selecione Parentesco!');
		frmcadastro.parentesco.focus();
		return false;
	}

	else return true;
	
}