function mudacor(b,a){b.style.backgroundColor=a}

function reajusta(){parent.document.getElementById("palc").height=document.getElementById("conteudo").clientheight}

function anexa(){topo=(screen.height-500)/2;esq=(screen.width-600)/2;window.open("../gft/a.php","galeria","top="+topo+",left="+esq+",scrollbars=yes,width=600, height=500")}

function anexar(b,a){opener.anexando(b,a);window.close()}

function anexando(b,a){document.all.campo_galeria.value=b;document.all.bot_anexar.style.display="none";document.all.bot_desanexar.style.display="block";document.all.gal_img_exibe.src=a;parent.document.all.palc.height=parent.document.all.palc.clientheight+50}

function desanexar(){document.all.campo_galeria.value="";document.all.bot_anexar.style.display="block";document.all.bot_desanexar.style.display="none";document.all.gal_img_exibe.src="painel/imgs/space.png";parent.document.all.palc.height=parent.document.all.palc.clientheight-50}

function mostra(a){banner_conteudo.location="b.php?id="+a}

function oculta(){banner_conteudo.location="o.php"};