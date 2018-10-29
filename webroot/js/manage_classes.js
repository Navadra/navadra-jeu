var listClassrooms = [];
var classroomName = "";
var classroomLevel = "";
var maxStudents = 0;

$("#level").buttonset();
$("#cancel").button();
$("#delete").button();
$("label[for=delete]").hide();
$("#loading").hide();

$("#missingInfo").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Fermer": function(){
			$(this).dialog("close");
		}
	}
});

$("#confirmDelete").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Supprimer": function(){
			deleteClass();
			$(this).dialog("close");
		},
		"Annuler": function(){
			$(this).dialog("close");
		}
	}
});

$("img").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

$(".titles").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

//VALIDATION FUNCTIONS
function validName(objet) {
	var ok = objet.val().match(/^[a-zàéèïîëçù'A-Z0-9° -@_]{2,20}$/);
	if(ok == null) {
		erreur_saisie(objet);
		return false;
	}	else {
		enlever_erreur(objet);
		return true;
	}
}

$("input[name=name]").on("keyup",function(){
	validName($(this));
});

$("input[name=level]").on("click",function(){
	classroomLevel = $(this).val();
});

function validMaxStudents(objet) {
	var ok = objet.val().match(/^[0-9]{1,2}$/);
	if(ok != null && parseInt(ok) >= 2 && parseInt(ok) <= 40) {
		enlever_erreur(objet);
		return true;
	}	else {
		erreur_saisie(objet);
		return false;
	}
}

$("input[name=maxStudents]").on("keyup",function(){
	validMaxStudents($(this));
});

function validFields(){
	classroomName = $("input[name=name]").val();
	maxStudents = parseInt($("input[name=maxStudents]").val());
	var ok = 1;
	if(!validName($("input[name=name]"))){
		ok = 0;
		var incorrectField = "name";
	} else if (!validMaxStudents($("input[name=maxStudents]"))) {
		ok = 0;
		var incorrectField = "maxStudents";
	} else if(classroomLevel == "") {
		ok = 0;
		var incorrectField = "level";
	}
	if(ok != 0){
		return true;
	} else {
		if(incorrectField == "name"){
			$("#missingInfo").html("Votre classe doit avoir un nom valide (ex: 6°2, 4°B, etc.).");
		} else if(incorrectField == "level"){
			$("#missingInfo").html("Vous n'avez pas coché le niveau de votre classe.");
		} else if(incorrectField == "maxStudents"){
			$("#missingInfo").html("Votre classe doit avoir un nombre d'élèves maximum compris entre 2 et 40.");
		}
		$("#missingInfo").dialog("open");
		return false;
	}
}

//SUBMIT BUTTON
$("#validate").on("click", function(){
	if(validFields()){
		$("#loading").show();
		$("#validate").hide();
		$("#cancel").hide();
		$("label[for=delete]").hide();
		$.ajax({
			url: '/app/controllers/ajax_admin.php',
			type :'POST',
			data: 'classroomId='+$("#classroomId").val()+'&classroomName='+classroomName+'&classroomLevel='+classroomLevel+'&maxStudents='+maxStudents,
			dataType: 'json',
			success: function(result, statut){
				listClassrooms = result;
				if(parseInt($("#classroomId").val()) == 0) {
					$("#confirmation").switchClass("rouge", "vert").html("Classe ajoutée");
				} else {
					$("#confirmation").switchClass("rouge", "vert").html("Classe modifiée");
				}
				$("#loading").hide();
				$("#validate").show();
				$("#cancel").show();
				resetFields();
				refreshResults();
				console.log(result);
			},
			error: function(result, statut, erreur){
				$("#confirmation").switchClass("vert", "rouge").html("Erreur : la requête a échoué !");
				$("#loading").hide();
				$("#validate").show();
				$("#cancel").show();
				if(parseInt($("#classroomId").val()) != 0){
					$("label[for=delete]").show();
				}
				console.log(erreur);
			},
			complete: function(result, statut){
			}
		});
	}
});


//REFRESH VIEW
function resetFields(){
	$("#actionTitle").html("Ajouter une classe");
	$("#classroomId").val(0);
	classroomName = "";
	$("input[name=name]").val("");
	classroomLevel = "";
	$("input[name=level]").each(function(){
		$(this).prop("checked", false).button("refresh");
	});
	maxStudents = 0;
	$("input[name=maxStudents]").val("");
	$("label[for=delete]").hide();
	$(".classrooms").removeClass("fond_beige_clair");
}

function refreshResults(){
	$("#listClassrooms").empty();
	listClassrooms.forEach(function(classroom) {
		var newDiv = '<div id="'+ classroom.id +'" class="ligne_scroll classrooms pb05 ph05">';
		newDiv += '<span class="l30">'+ classroom.name +'</span>';
		newDiv += '<span class="l8">'+ classroom.level +'</span>';
		newDiv += '<span class="l25">'+ classroom.nbStudents +' / '+ classroom.maxStudents +'</span>';
		newDiv += '<span class="l35">'+ classroom.code +'</span>';
		newDiv += '</div>';
		$(newDiv).appendTo($("#listClassrooms"));
	});
	activateLines();
}

activateLines();
function activateLines(){
	$(".classrooms").on("mouseover", function(){
		mettre_en_evidence_ligne_scroll($(this));
	});
	$(".classrooms").on("mouseout", function(){
		enlever_evidence_ligne_scroll($(this));
	});
	$(".classrooms").on("click", function(){
		$(".classrooms").removeClass("fond_beige_clair");
		$(this).addClass("fond_beige_clair");
		$("#actionTitle").html("Modifier une classe existante");
		$("#classroomId").val($(this).attr("id"));
		$("input[name=name]").val($(this).children("span:eq(0)").html());
		var maxTemp = $(this).children("span:eq(2)").html().match(/\/\s(\d+)/)[1];
		$("input[name=maxStudents]").val(maxTemp);
		var levelTemp = $(this).children("span:eq(1)").html();
		$("input[name=level]").each(function(){
			if($(this).val() == levelTemp){
				$(this).prop("checked", true).button("refresh");
				classroomLevel = levelTemp;
			}
		});
		$("label[for=delete]").show();
	});
}

$("#cancel").on("click", function(event){
	event.preventDefault();
	resetFields();
});

$("label[for=delete]").on("click", function(event){
	event.preventDefault();
	var idTemp = parseInt($("#classroomId").val());
	if(idTemp != 0){
		var nbTemp = $("#"+idTemp).children("span:eq(2)").html().match(/(\d+)\s\//)[1];
		$("#confirmDelete").html("Êtes vous sûr"+e+" de vouloir supprimer la classe "+$("input[name=name]").val()+ " qui compte "+nbTemp+ " élève(s) ?")
		$("#confirmDelete").dialog("open");
	}
});

function deleteClass(){
		$("#loading").show();
		$("#validate").hide();
		$("#cancel").hide();
		$("label[for=delete]").hide();
		$.ajax({
			url: '/app/controllers/ajax_admin.php',
			type :'POST',
			data: 'deleteClass='+$("#classroomId").val(),
			dataType: 'json',
			success: function(result, statut){
				$("#confirmation").switchClass("rouge", "vert").html("La classe "+$("input[name=name]").val() + " a été supprimée !");
				listClassrooms = result;
				resetFields();
				$("#loading").hide();
				$("#validate").show();
				$("#cancel").show();
				refreshResults();
				console.log(result);
			},
			error: function(result, statut, erreur){
				$("#confirmation").switchClass("vert", "rouge").html("Erreur : la requête a échoué !");
				$("#loading").hide();
				$("#validate").show();
				$("#cancel").show();
				$("label[for=delete]").show();
				console.log(erreur);
			},
			complete: function(result, statut){
			}
		});
}

$(window).load(function(){
	if($("#showParameters").length){
		$("#showParameters").css("top", $("#fond_param_deco").offset().top + 1.4*$("#fond_param_deco").height()).css("left", $("#icone_parametres").offset().left + 0.5*$("#icone_parametres").width() - 0.5*$("#showParameters").width());
		$("#showParameters").effect("pulsate",{easing:"swing", times: 50}, 10000);
	}
	$("#rideau_haut").hide("slide",{easing:"easeInExpo", direction: "up"}, "slow");
	$("#rideau_bas").hide("slide",{easing:"easeInExpo", direction: "down"}, "slow");
});
