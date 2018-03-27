function validacampo (valida_campo) {
	
if (frmcadastro.empresa.value == '') {
		alert ('É negado nulo, favor selecione Empresa!');
		frmcadastro.empresa.focus();
		return false;
	}
	
/* if (frmcadastro.matricula.value == '') {
		alert ('É negado nulo, favor informar Matricula!');
		frmcadastro.matricula.focus();
		return false;
	}

if (frmcadastro.funcao.value == '') {
		alert ('É negado nulo, favor informar Funcao!');
		frmcadastro.funcao.focus();
		return false;
	}

if (frmcadastro.setor.value == '') {
		alert ('É negado nulo, favor informar Setor!');
		frmcadastro.setor.focus();
		return false;
	} 

if (frmcadastro.cpf.value == '') {
		alert ('É negado nulo, favor informar CPF!');
		frmcadastro.cpf.focus();
		return false;
	}

if (frmcadastro.identidade.value == '') {
		alert ('É negado nulo, favor informar Identidade!');
		frmcadastro.identidade.focus();
		return false;
	}

if (frmcadastro.emissor.value == '') {
		alert ('É negado nulo, favor informar emissor!');
		frmcadastro.emissor.focus();
		return false;
	}	*/

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
					
/*if (frmcadastro.cep.value == '') {
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

if (frmcadastro.celular.value == '') {
		alert ('É negado nulo, favor informar Celular!');
		frmcadastro.celular.focus();
		return false;
	}
	
if (frmcadastro.fone.value == '') {
		alert ('É negado nulo, favor informar Telefone!');
		frmcadastro.fone.focus();
		return false;
	}

if (frmcadastro.email.value.search("@") == -1 || frmcadastro.email.value.search("[.*]") == -1) {
		alert ('E-mail nao preenchido ou incorreto!');
		frmcadastro.email.focus();
		return false;
	}

if (frmcadastro.foto.value == '') {
		alert ('É negado nulo, favor selecione Imagem/Foto!');
		frmcadastro.foto.focus();
		return false;
	} */
	
	else return true;
	
}