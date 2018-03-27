function validacampo (valida_campo) {
	
if (frmcadastro.inscricao.value == '') {
		alert ('É negado nulo, favor informar Inscrição CPF ou CNPJ!');
		frmcadastro.inscricao.focus();
		return false;
	}
	
if (frmcadastro.razao.value == '') {
		alert ('É negado nulo, favor informar Razao Social!');
		frmcadastro.razao.focus();
		return false;
	}

if (frmcadastro.fantasia.value == '') {
		alert ('É negado nulo, favor informar Nome Fantasia!');
		frmcadastro.fantasia.focus();
		return false;
	}

if (frmcadastro.cep.value == '') {
		alert ('É negado nulo, favor informar CEP!');
		frmcadastro.cep.focus();
		return false;
	}

if (frmcadastro.endereco.value == '') {
		alert ('É negado nulo, favor informar Endereço Completo!');
		frmcadastro.endereco.focus();
		return false;
	}
	
if (frmcadastro.numero.value == '') {
		alert ('É negado nulo, favor informar Numero!');
		frmcadastro.numero.focus();
		return false;
	}

if (frmcadastro.bairro.value == '') {
		alert ('É negado nulo, favor informar Bairro!');
		frmcadastro.bairro.focus();
		return false;
	}

if (frmcadastro.uf.value == '') {
		alert ('É negado nulo, favor selecione UF!');
		frmcadastro.uf.focus();
		return false;
	}


if (frmcadastro.cidade.value == '') {
		alert ('É negado nulo, favor selecione Cidade!');
		frmcadastro.cidade.focus();
		return false;
	}

if (frmcadastro.fone.value == '') {
		alert ('É negado nulo, favor informar Telefone!');
		frmcadastro.fone.focus();
		return false;
	}

/*if (frmcadastro.email.value.search("@") == -1 || frmcadastro.email.value.search("[.*]") == -1) {
		alert ('E-mail nao preenchido ou incorreto!');
		frmcadastro.email.focus();
		return false;
	}*/

	else return true;
	
}