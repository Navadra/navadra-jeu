var success = parseInt($("#info_reponse").html());

function displayDiv(){
	if(success == 1){
		var type = "bonne_rep";
		$(".icone_reponse").attr("src", "/webroot/img/icones/correct.png");
		$("#info_reponse").html("<span class='p3'>Email confirmé, merci !</span>");
	} else {
		var type = "mauvaise_rep";
		$(".icone_reponse").attr("src", "/webroot/img/icones/warning.png");
		$("#info_reponse").html("<span class='p3'>Désolé, ce lien n'est pas valide !</span>");
	}

	//Div setup and display
	$("#info_reponse").css("top", 0.5*$(window).height() - 0.5*$("#info_reponse").height());
	$(".icone_reponse").css("top", 0.5*$(window).height() - 0.5*$("#info_reponse").height()).show();
	$("#info_reponse").addClass(type).show();
}

$("#info_reponse").css("padding-top", "20px").css("padding-bottom", "20px").css("display", "flex").css("justify-content", "center").css("align-items", "center");
$("#info_reponse").hide();
$(".icone_reponse").hide();
displayDiv();
