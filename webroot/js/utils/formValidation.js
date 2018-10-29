$("#info_input").hide();

$("input").on("blur", function(){
	$("#info_input").hide();
});


function erreur_saisie(objet){
	objet.css("border", "2px solid #f00");
	$("#info_input").css("color", "#f00").html("").css("z-index", "103");
	$("<span class='ib l10 md4'><img class='img_30' src='/webroot/img/icones/faux.png'/></span>").appendTo($("#info_input"));
	if(objet.attr("name") == "email" && typeof classe !== 'undefined' && classe == "Prof")	{
		$("<span class='ib l75 align_haut'>Saisissez de préférence votre email personnel, les boites mails académiques pouvant bloquer nos emails.</span>").appendTo($("#info_input"));
	}
	else if(objet.attr("title"))	{
		$("<span class='ib l75 align_haut'>"+objet.attr("title")+"</span>").appendTo($("#info_input"));
	} else {
		return false;
	}
	var yPos = objet.offset().top - 1.3*$("#info_input").height() - objet.height();
	var xPos = objet.offset().left;
	$("#info_input").css("top", yPos).css("left", xPos).show("clip", {easing: "swing"}, 250);
}

function enlever_erreur(objet){
	objet.css("border", "2px solid #1c9500");
	$("#info_input").css("color", "#1c9500");
	$("#info_input").html("<img class='img_30 ib' src='/webroot/img/icones/correct.png'/>");
	$("#info_input").hide("clip", {easing: "swing"}, 250);
}


function regexEmail(email, domainList){
	var regex = email.match(/^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9_-]{2,}\.[a-zA-Z]{2,4}$/);
	email = email.toLowerCase();
	if(regex != null && domainList == true){
		var domainNames = ["free.fr", "wanadoo.fr", "orange.fr", "gmail.com", "hotmail.com", "bbox.fr", "aol.fr", "hotmail.fr", "laposte.net", "yahoo.fr", "icloud.com", "outlook.fr", "sfr.fr", "outlook.com", "gmx.fr", "orange.com", "numericable.com", "live.fr", "yahoo.com", "laposte.fr", "voila.fr", "hotmail.be", "aliceadsl.fr", "neuf.fr", "gmx.com", "bouyguestelecom.fr", "enteduc.fr", "numericable.fr"];
		var domainEmail = email.match(/^[a-zA-Z0-9._-]{1,}@([a-zA-Z0-9_.-]{2,}\.[a-zA-Z]{2,4})$/)[1];
		if(domainNames.indexOf(domainEmail) == -1){
			return "Ce nom de domaine n'est pas autorisé.";
		} else {
			return true;
		}
	} else if(regex != null && domainList == false){
		return true;
	}	else {
		return "L'adresse ne respecte pas le format email classique.";
	}
}
