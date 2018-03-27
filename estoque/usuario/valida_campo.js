function validacampo (valida_campo) {
	
	if (frmcadastro.id.value == '' || frmcadastro.id.value =='registro novo') {
		alert ('Por Favor informe o Login!');
		frmcadastro.id.value='';
		frmcadastro.id.focus();
		return false;
	}

	if (frmcadastro.nome.value == '') {
		alert ('Por Favor informe o Nome!');
		frmcadastro.nome.focus();
		return false;
	}	

	if (frmcadastro.nascimento.value == ''){
		alert ('Por Favor informe a Data de Nascimento!');
		frmcadastro.nascimento.focus();
		return false;
	}	

	if (frmcadastro.sexo[0].checked == false && frmcadastro.sexo[1].checked == false){
		alert ('Por Favor Selecione o Sexo!');
		return false;
	}		
	
	if (frmcadastro.email.value.search("@") == -1 || frmcadastro.email.value.search("[.*]") == -1) {
		alert ('E-mail nao preenchido ou incorreto!');
		frmcadastro.email.focus();
		return false;
	}
		
	if (frmcadastro.telefone.value == '') {
		alert ('Por Favor informe o Telefone!');
		frmcadastro.telefone.focus();
		return false;
	}	
	
	if (frmcadastro.celular.value == '') {
		alert ('Por Favor informe o Celular!');
		frmcadastro.celular.focus();
		return false;
	}	
	
	if (frmcadastro.senha.value == '') {
		alert ('Por favor informe a Senha!');
		frmcadastro.senha.focus();
		return false;
	}
	
	if (frmcadastro.confirma.value == '') {
		alert ('Por favor confirme a senha!');
		frmcadastro.confirma.focus();
		return false;
	}
	
	if (frmcadastro.senha.value != frmcadastro.confirma.value){
		alert ('A Comfirmação da Senha não confere com a Senha!');
		frmcadastro.confirma.focus();
		return false;
	}
	
	if (frmcadastro.admin[0].checked == false && frmcadastro.admin[1].checked == false){
		alert ('Por Favor Selecione a Permissão de Acesso ao Menu Administrador!');
		return false;
	}		
	
	if (frmcadastro.adminpainel[0].checked == false && frmcadastro.adminpainel[1].checked == false){
		alert ('Por Favor Selecione a Permissão de Acesso ao Menu Administrador do Painel!');
		return false;
	}	
	
	if (frmcadastro.cadastro[0].checked == false && frmcadastro.cadastro[1].checked == false){
		alert ('Por Favor Selecione a Permissão de Acesso ao Menu Cadastro!');
		return false;
	}	
	
	if (frmcadastro.convenio[0].checked == false && frmcadastro.convenio[1].checked == false){
		alert ('Por Favor Selecione a Permissão de Acesso ao Menu Convenio!');
		return false;
	}	
	
	if (frmcadastro.estoque[0].checked == false && frmcadastro.estoque[1].checked == false){
		alert ('Por Favor Selecione a Permissão de Acesso ao Menu Estoque!');
		return false;
	}	
	
	if (frmcadastro.painel[0].checked == false && frmcadastro.painel[1].checked == false){
		alert ('Por Favor Selecione a Permissão de Acesso ao Menu Painel!');
		return false;
	}						
	
	if (frmcadastro.nivel.value == '') {
		alert ('Por favor informe o Nivel!');
		frmcadastro.nivel.focus();
		return false;
	}

	else return true;
	
}