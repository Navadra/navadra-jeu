var listClassrooms = {};
var idClassroom = parseInt($("#classroom").val());
var idStudent = 0;
var nameStudent = "";

if($("#contentFrame").length){
	$("#classroom option").each(function(){
		listClassrooms[$(this).val()] = $(this).html();
	});
	$("#contentFrame").css("height", 0.6*$(window).height());
	$(".legendIcon").css("vertical-align", "top")
	$("#loading").css("top", $("#contentFrame").offset().top + 0.5*$("#contentFrame").height() - 0.5*$("#loading").height()).css("left", $("#contentFrame").offset().left + 0.5*$("#contentFrame").width() - 0.5*$("#loading").width()).hide();
}

$("img").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

$("#confirmDelete").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"Oui": function(){
			deleteStudent();
			$(this).dialog("close");
		},
		"Non": function(){
			$(this).dialog("close");
		}
	}
});


function activateDeleteIcons(){
	$(".deleteStudent").on("click", function(){
		idStudent = parseInt($(this).attr("dataId"));
		nameStudent = $(this).attr("dataName");
		$("#confirmDelete").html("Voulez-vous vraiment supprimer <span class='p1 g'>"+nameStudent+"</span> de votre classe ?").dialog("open");
	});
}

function deleteStudent(){
	$("#loading").show();
	$("#content").empty();
	$.ajax({
		url: '/app/controllers/ajax_admin.php',
		type :'POST',
		data: 'deleteStudent='+idStudent,
		dataType: 'html',
		success: function(result, statut){
			refreshData();
		},
		error: function(result, statut, erreur){
			alert("Problème serveur : la requête a échoué, veuillez réessayer.");
		},
		complete: function(result, statut){
		}
	});
}

function refreshData(){
	$("#loading").show();
	$("#content").empty();
	$.ajax({
		url: '/app/controllers/ajax_admin.php',
		type :'POST',
		data: 'progressClassroom='+idClassroom,
		dataType: 'html',
		success: function(result, statut){
			$("#loading").hide();
			$("#content").html(result);
			styleTable();
		},
		error: function(result, statut, erreur){
			$("#loading").hide();
			alert("Problème serveur : la requête a échoué, veuillez réessayer.");
		},
		complete: function(result, statut){
		}
	});
}

//SUBMIT BUTTON align_middle
$("#classroom").on("change", function(){
	idClassroom = parseInt($(this).val());
	refreshData();
});

//REFRESH VIEW
styleTable();
function styleTable(){
	//SORTABLE TABLE
	var columnsOptions = [];
	$("#tableProgress thead tr th").each(function(index, value){
		if(index == 0){
			columnsOptions.push({ width: 0.15*$(window).width(), align: 'center' });
		} else {
			columnsOptions.push({ width: 0.10*$(window).width(), align: 'center' });
		}
		if($(this).attr("element") == undefined){
			$(this).css("background-color", gris);
		} else if($(this).attr("element") == "fire"){
			$(this).css("background-color", rouge_pastel);
		} else if($(this).attr("element") == "water"){
			$(this).css("background-color", bleu_pastel);
		} else if($(this).attr("element") == "wind"){
			$(this).css("background-color", jaune_pastel_bis);
		} else if($(this).attr("element") == "earth"){
			$(this).css("background-color", vert_pastel);
		}
		$(this).css("color", "#fff").css("cursor", "pointer");
	});

	$('#tableProgress').fxdHdrCol({
		fixedCols: 1,
		width:     "100%",
		height:    "100%",
		colModal: columnsOptions
	});

	$("#tableProgress tbody tr td").css("vertical-align", "baseline");
	$("#tableProgress tbody tr td img").css("vertical-align", "top");
	$(".nameStudent").css("cursor", "pointer").css("background-color", "#D9D9D9");
	$(".mastery0").css("background-color", "#FFF");
	$(".mastery1").css("background-color", "#F4B084");
	$(".mastery2").css("background-color", "#FCE4D6");
	$(".mastery3").css("background-color", "#D9EACE");
	$(".mastery4").css("background-color", "#B0D498");
	$(".mastery5").css("background-color", "#70AD47");
	$(".mastery6").css("background-color", "#76ABDC");

	$(".titles").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});

	activateDeleteIcons();
}

$(window).load(function(){
	if($("#showParameters").length){
		$("#showParameters").css("top", $("#fond_param_deco").offset().top + 1.4*$("#fond_param_deco").height()).css("left", $("#icone_parametres").offset().left + 0.5*$("#icone_parametres").width() - 0.5*$("#showParameters").width());
		$("#showParameters").effect("pulsate",{easing:"swing", times: 50}, 10000);
	}
	$("#rideau_haut").hide("slide",{easing:"easeInExpo", direction: "up"}, "slow");
	$("#rideau_bas").hide("slide",{easing:"easeInExpo", direction: "down"}, "slow");
	activateDeleteIcons();
});
