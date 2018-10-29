var listTimeslots = [];
var listNotions = [];
var listNotionsObj = {};
var listClassrooms = {};
var idClassroom = "";
var startTime = "";
var endTime = "";
var notion1 = "";
var notion2 = "";
var notion3 = "";
var activeNotion;

var dateFormat = "dd/mm/yy",
frenchOptions = {
	defaultDate: "-1w",
	changeMonth: true,
	changeYear: true,
	numberOfMonths: 1,
	minDate: 0, // 0 days offset = today
	closeText: 'Fermer',
	prevText: 'Précédent',
	nextText: 'Suivant',
	currentText: 'Aujourd\'hui',
	monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
	monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
	dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
	dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
	dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
	weekHeader: 'Sem.',
	dateFormat: 'dd/mm/yy',
	firstDay: 1
},
dayPicker = $( "#day" ).datepicker(frenchOptions);

$(".notion").each(function(){
	listNotions.push($(this).html());
	listNotionsObj[$(this).html()] = $(this).attr('notion');
});

$("#classroom option").each(function(){
	listClassrooms[$(this).val()] = $(this).html();
});

$(".notionField").on("focus", function(){
	$("#menuChallenges").css("top", $(this).offset().top + 1.5*$(this).height()).css("left", $(this).offset().left).show();
	activeNotion = $(this);
});

$(".notionField").on("blur", function(){
	$("#menuChallenges").hide();
});

$("#menuChallenges").css("position", "absolute").hide();
$("#menuChallenges").menu({
	select: function(event, ui){
		var notionTemp = ui.item[0].innerText;
		var lastCharacter = notionTemp.substring(ui.item[0].innerText.length - 1);
		if(lastCharacter.match(/^[\wé]$/) == null){
			notionTemp = notionTemp.substring(0, ui.item[0].innerText.length - 1);
		}
		var notionEngTemp = ui.item[0].innerHTML.match(/notion="(\w+)"/)[1];
		console.log('Nouvelle notion : '+notionTemp);
		if(listNotions.indexOf(notionTemp) != -1){
			activeNotion.val(notionTemp);
			activeNotion.attr("notion", notionEngTemp);
			$(this).menu( "collapse").hide();
		}
	}
});
$(".ui-menu").css("width", 0.2*$(window).width());


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
			deleteTimeslot();
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
function validDay(objet) {
	var ok = objet.val().match(/^\d\d\/\d\d\/\d\d\d\d$/);
	if(ok == null) {
		erreur_saisie(objet);
		return false;
	}	else {
		enlever_erreur(objet);
		return true;
	}
}

function validStartTime(objet) {
	var ok = objet.val().match(/^\d\d:\d\d$/);
	if(ok == null) {
		erreur_saisie(objet);
		return false;
	}	else {
		var hourTemp = parseInt(objet.val().match(/^(\d\d):\d\d$/)[1]);
		var minTemp = parseInt(objet.val().match(/^\d\d:(\d\d)$/)[1]);
		if(hourTemp >= 0 && hourTemp <= 23 && minTemp <= 59){
			enlever_erreur(objet);
			return true;
		} else {
			erreur_saisie(objet);
			return false;
		}
	}
}

function validNotion(objet) {
	var ok = listNotions.indexOf(objet.val());
	if(ok == -1) {
		erreur_saisie(objet);
		return false;
	}	else {
		enlever_erreur(objet);
		return true;
	}
}

$("input[name=day]").on("change",function(){
	validDay($(this));
});

$("input[name=startTime]").on("keyup",function(){
	if(validStartTime($(this))){
		var hourTemp = parseInt($(this).val().match(/^(\d\d):\d\d$/)[1]);
		hourTemp ++;
		if(hourTemp < 10){
			hourTemp = "0" + hourTemp;
		}
		var minTemp = $(this).val().match(/^\d\d:(\d\d)$/)[1];
		$("#endTime").html(hourTemp + ":" + minTemp);
	}
});

$(".notionField").on("blur",function(){
	validNotion($(this));
});

function validFields(){
	var ok = 1;
	if(!validDay($("input[name=day]"))){
		ok = 0;
		var incorrectField = "day";
	} else if (!validStartTime($("input[name=startTime]"))) {
		ok = 0;
		var incorrectField = "startTime";
	} else if (!validNotion($("input[name=notion1]")) || !validNotion($("input[name=notion2]")) || !validNotion($("input[name=notion3]"))) {
		ok = 0;
		var incorrectField = "notion";
	} else if ($("input[name=notion1]").val() == $("input[name=notion2]").val() || $("input[name=notion1]").val() == $("input[name=notion3]").val() || $("input[name=notion2]").val() == $("input[name=notion3]").val() ) {
		ok = 0;
		var incorrectField = "allNotions";
	}
	if(ok != 0){
		idClassroom = parseInt($('select[name=classroom]').find(":selected")[0].value);
		var dayTemp = $("input[name=day]").val().match(/(\d+)\/\d+\/\d+/)[1];
		var monthTemp = $("input[name=day]").val().match(/\d+\/(\d+)\/\d+/)[1];
		var yearTemp = $("input[name=day]").val().match(/\d+\/\d+\/(\d+)/)[1];
		startTime = yearTemp +"-"+ monthTemp +"-"+ dayTemp +" "+ $("input[name=startTime]").val() +":00";
		endTime = yearTemp +"-"+ monthTemp +"-"+ dayTemp +" "+ $("#endTime").html() +":00";
		notion1 = $("input[name=notion1]").attr("notion");
		notion2 = $("input[name=notion2]").attr("notion");
		notion3 = $("input[name=notion3]").attr("notion");
		return true;
	} else {
		if(incorrectField == "day"){
			$("#missingInfo").html("Le jour du créneau doit être au format jj/mm/aaaa.");
		} else if(incorrectField == "startTime"){
			$("#missingInfo").html("L'heure de départ doit être au format hh:mm et comprise entre 08h00 et 17h59.");
		} else if(incorrectField == "notion"){
			$("#missingInfo").html("Vous devez obligatoirement renseigner 3 notions à travailler pendant le créneau.<br><br>Pour cela, utilisez le menu qui s'affiche.");
		} else if(incorrectField == "allNotions"){
			$("#missingInfo").html("Les 3 notions doivent obligatoirement être différentes.");
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
			data: 'timeslotId='+$("#timeslotId").val()+'&idClassroom='+idClassroom+'&startTime='+startTime+'&endTime='+endTime+'&notion1='+notion1+'&notion2='+notion2+'&notion3='+notion3,
			dataType: 'json',
			success: function(result, statut){
				listTimeslots = result;
				if(parseInt($("#timeslotId").val()) == 0) {
					$("#confirmation").switchClass("rouge", "vert").html("Créneau ajouté");
				} else {
					$("#confirmation").switchClass("rouge", "vert").html("Créneau modifié");
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
				if(parseInt($("#timeslotId").val()) != 0){
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
	$("#actionTitle").html("Ajouter un créneau");
	$("#timeslotId").val(0);
	day = "";
	$("input[name=day]").val("");
	startTime = "";
	$("input[name=startTime]").val("");
	endTime = "";
	$("#endTime").html("");
	notion1 = "";
	$("input[name=notion1]").val("");
	notion2 = "";
	$("input[name=notion2]").val("");
	notion3 = "";
	$("input[name=notion3]").val("");
	$("label[for=delete]").hide();
	$(".timeslots").removeClass("fond_beige_clair");
}

function refreshResults(){
	$("#listTimeslots").empty();
	listTimeslots.forEach(function(timeslot) {
		var newDiv = '<div id="'+ timeslot.id +'" class="ligne_scroll timeslots pb05 ph05">';
		newDiv += '<span class="l12">'+ listClassrooms[timeslot.idClassroom] +'</span>';
		newDiv += '<span class="l10">'+ timeslot.day +'</span>';
		newDiv += '<span class="l15">'+ timeslot.hour +'</span>';
		newDiv += '<span class="l20">'+ timeslot.notion1 +'</span>';
		newDiv += '<span class="l20">'+ timeslot.notion2 +'</span>';
		newDiv += '<span class="l20">'+ timeslot.notion3 +'</span>';
		newDiv += '</div>';
		$(newDiv).appendTo($("#listTimeslots"));
	});
	activateLines();
}

activateLines();
function activateLines(){
	$(".timeslots").on("mouseover", function(){
		mettre_en_evidence_ligne_scroll($(this));
	});
	$(".timeslots").on("mouseout", function(){
		enlever_evidence_ligne_scroll($(this));
	});
	$(".timeslots").on("click", function(){
		$(".timeslots").removeClass("fond_beige_clair");
		$(this).addClass("fond_beige_clair");
		$("#actionTitle").html("Modifier un créneau existant");
		$("#timeslotId").val($(this).attr("id"));

		$('#classroom option[name="'+$(this).children("span:eq(0)").html()+'"]').prop('selected', true);
		$("input[name=day]").val($(this).children("span:eq(1)").html());
		var startTimeTemp = $(this).children("span:eq(2)").html().match(/(\d+:\d+) à \d+:\d+/)[1];
		$("input[name=startTime]").val(startTimeTemp);
		var endTimeTemp = $(this).children("span:eq(2)").html().match(/\d+:\d+ à (\d+:\d+)/)[1];
		$("#endTime").html(endTimeTemp);
		$("input[name=notion1]").val($(this).children("span:eq(3)").html()).attr("notion", listNotionsObj[$(this).children("span:eq(3)").html()]);
		$("input[name=notion2]").val($(this).children("span:eq(4)").html()).attr("notion", listNotionsObj[$(this).children("span:eq(4)").html()]);;
		$("input[name=notion3]").val($(this).children("span:eq(5)").html()).attr("notion", listNotionsObj[$(this).children("span:eq(5)").html()]);;
		$("label[for=delete]").show();
	});
}

$("#cancel").on("click", function(event){
	event.preventDefault();
	resetFields();
});

$("label[for=delete]").on("click", function(event){
	event.preventDefault();
	var idTemp = parseInt($("#timeslotId").val());
	if(idTemp != 0){
		$("#confirmDelete").html("Êtes vous sûr"+e+" de vouloir supprimer ce créneau ?")
		$("#confirmDelete").dialog("open");
	}
});

function deleteTimeslot(){
		$("#loading").show();
		$("#validate").hide();
		$("#cancel").hide();
		$("label[for=delete]").hide();
		$.ajax({
			url: '/app/controllers/ajax_admin.php',
			type :'POST',
			data: 'deleteTimeslot='+$("#timeslotId").val(),
			dataType: 'json',
			success: function(result, statut){
				$("#confirmation").switchClass("rouge", "vert").html("Le créneau a bien été supprimé !");
				listTimeslots = result;
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
