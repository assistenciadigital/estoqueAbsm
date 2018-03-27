function validacampo (valida_campo) {
	
if (formulario.nome.value == '') {
		alert ('Por Favor informe o Nome!');
		formulario.nome.focus();
		return false;
	}
	
if (formulario.ordem.value == '') {
		alert ('Por Favor informe o Ordem de Classificacao!');
		formulario.ordem.focus();
		return false;
	}

	else return true;
	
}