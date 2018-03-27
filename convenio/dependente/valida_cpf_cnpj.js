// a função principal de validação
function validar(obj) { // recebe um objeto
	var s = (obj.value).replace(/\D/g,'');
	var tam=(s).length; // removendo os caracteres não numéricos
	if (!(tam==11 || tam==14)){ // validando o tamanho
		alert("'"+s+"Tamanho CPF ou CNPJ invalido!" ); // tamanho inválido
		return false;
	}
	
// se for CPF
	if (tam==11 ){
		if (!validaCPF(s)){ // chama a função que valida o CPF
			alert("'"+s+"' CPF invalido!" ); // se quiser mostrar o erro
			obj.select();  // se quiser selecionar o campo em questão
			return false;
		}
		//alert("'"+s+"' É um CPF válido!" ); // se quiser mostrar que validou		
		obj.value=maskCPF(s);	// se validou o CPF mascaramos corretamente
		return true;
	}
	
// se for CNPJ			
	if (tam==14){
		if(!validaCNPJ(s)){ // chama a função que valida o CNPJ
			alert("'"+s+"' CNPJ invalido!" ); // se quiser mostrar o erro
			obj.select();	// se quiser selecionar o campo enviado
			return false;			
		}
		//alert("'"+s+"' É um CNPJ válido!" ); // se quiser mostrar que validou				
		obj.value=maskCNPJ(s);	// se validou o CNPJ mascaramos corretamente
		return true;
	}
}
// fim da funcao validar()

// função que valida CPF
// O algorítimo de validação de CPF é baseado em cálculos
// para o dígito verificador (os dois últimos)
// Não entrarei em detalhes de como funciona
function validaCPF(s) {
	var c = s.substr(0,9);
	var dv = s.substr(9,2);
	var d1 = 0;
	for (var i=0; i<9; i++) {
		d1 += c.charAt(i)*(10-i);
 	}
	if (d1 == 0) return false;
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(0) != d1){
		return false;
	}
	d1 *= 2;
	for (var i = 0; i < 9; i++)	{
 		d1 += c.charAt(i)*(11-i);
	}
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(1) != d1){
		return false;
	}
    return true;
}

// Função que valida CNPJ
// O algorítimo de validação de CNPJ é baseado em cálculos
// para o dígito verificador (os dois últimos)
// Não entrarei em detalhes de como funciona
function validaCNPJ(CNPJ) {
	var a = new Array();
	var b = new Number;
	var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
	for (i=0; i<12; i++){
		a[i] = CNPJ.charAt(i);
		b += a[i] * c[i+1];
	}
	if ((x = b % 11) < 2) { a[12] = 0 } else { a[12] = 11-x }
	b = 0;
	for (y=0; y<13; y++) {
		b += (a[y] * c[y]);
	}
	if ((x = b % 11) < 2) { a[13] = 0; } else { a[13] = 11-x; }
	if ((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){
		return false;
	}
	return true;
}


	// Função que permite apenas teclas numéricas
	// Deve ser chamada no evento onKeyPress desta forma
	// return (soNums(event));
function soNums(e)
{
	if (document.all){var evt=event.keyCode;}
	else{var evt = e.charCode;}
	if (evt <20 || (evt >47 && evt<58)){return true;}
	return false;
}

//	função que mascara o CPF
function maskCPF(CPF){
	return CPF.substring(0,3)+"."+CPF.substring(3,6)+"."+CPF.substring(6,9)+"-"+CPF.substring(9,11);
}

//	função que mascara o CNPJ
function maskCNPJ(CNPJ){
	return CNPJ.substring(0,2)+"."+CNPJ.substring(2,5)+"."+CNPJ.substring(5,8)+"/"+CNPJ.substring(8,12)+"-"+CNPJ.substring(12,14);	
}