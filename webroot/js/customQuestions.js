var category;
var pause;
var score = 0;
var consecutiveGoodAnswers;
var anim1
var anim2;
var answerTime
var initialTimer
var timer
var chrono_interval
var plot_chrono;
var allQuestions = [];
var allQuestionsFiltered = [];
var currentQuestion = {};
var historicQuestions = [];
var historicQuestionsFiltered = [];

//Fonctions de formatage des champs pour gérer les erreurs
function inputError(object){
	object.css("border", "2px solid #f00");
}

function removeError(object){
	object.css("border", "2px solid #1c9500");
}

//Fonctions de contrôle du format des données
function validQuestion(object){
	var ok = object.val().match(/^[\s\S\r]{3,200}$/);
	if(ok == null) {
		inputError(object);
		return false;
	}	else {
		removeError(object);
		return true;
	}
}

function validChoice(object){
	var ok = object.val().match(/^[\s\S\r]{1,50}$/);
	if(ok == null) {
		inputError(object);
		return false;
	}	else {
		removeError(object);
		return true;
	}
}

//UTILS FUNCTIONS
//Mentor messages
function mentorMessages(answer){
	switch(answer){
		case "wrongAnswer" :
			var rand = math.randomInt(1, 5);
			if(rand==1)
				{var msg = "C'est pas grave, moi à ton âge je faisais plus d'erreurs que ça.";}
			if(rand==2)
				{var msg = "C'est en se trompant qu'on progresse.";}
			if(rand==3)
				{var msg = "Allez, le défi n'est pas fini, il faut persévérer !";}
			if(rand==4)
				{var msg = "Ne te laisse pas décourager par une mauvaise réponse.";}
			break;
		case "goodAnswer" :
			var rand = math.randomInt(1, 5);
			if(rand==1)
				{var msg = "Excellent !";}
			if(rand==2)
				{var msg = "Parfait, continue comme ça !";}
			if(rand==3)
				{var msg = "Bien joué, continue sur ta lancée ! ";}
			if(rand==4)
				{var msg = "Super, encore un effort !";}
			break;
	}
	$("#txt_bulle").text(msg);
}

function animationsReset(){
	if(anim1 != 0)	{
		clearTimeout(anim1);
		hideDiv();
		anim1 = 0;
	}
	if(anim2 != 0)	{
		$("#info_reponse").attr("class", "sans_rep");
		clearTimeout(anim2);
		$("#info_reponse").hide();
		anim2 = 0;
	}
}

//Hide the div containing the answer
function hideDiv(){
	$(".icone_reponse").hide();
	$("#info_reponse").switchClass($("#info_reponse").attr("class"), "sans_rep", 500);
	anim2 = setTimeout(function(){
		$("#info_reponse").hide();
		anim2=0;
	}, 500);
}

//Display the div containing the answer and format it with CSS classes
function displayDiv(type){
	$(".icone_reponse").show();
	$("#info_reponse").show();
	$("#info_reponse").switchClass("sans_rep", type, 500);
}

//MAIN FUNCTIONS
//Setup and display the div in case of a good answer
function good_answer(){
	animationsReset();
	score ++;
	answerTime = initialTimer - timer;
	$("#score").text(score);
	consecutiveGoodAnswers ++;

	//Div setup and display
	$(".icone_reponse").attr("src", "/webroot/img/icones/correct.png");
	if(consecutiveGoodAnswers > 1)
		{$("#info_reponse").html("Bravo !<br>" + consecutiveGoodAnswers + " bonnes réponses de suite !");}
	else
		{$("#info_reponse").html("Bravo !<br>Continue sur ta lancée !");}
	$("#info_reponse").css("cursor", "default");
	$("#info_reponse").unbind("click");
	displayDiv("bonne_rep");
	anim1 = setTimeout(function(){hideDiv();anim1=0;}, 1500);

	//Mentor message
	mentorMessages("goodAnswer");

	//Sound activation
	$('#son_bonne_rep')[0].pause();
	$('#son_bonne_rep')[0].currentTime = 0;
	$('#son_mauvaise_rep')[0].pause();
	$('#son_mauvaise_rep')[0].currentTime = 0;
	$('#son_bonne_rep')[0].play();

	//Store question result
	recordQuestion(1);
}

//Setup and display the div in case of a wrong answer
function wrong_answer(info){
	//Variable incrementation
	animationsReset();
	answerTime = initialTimer - timer;
	consecutiveGoodAnswers = 0;
	pause = true;

	//Div setup and display
	$(".icone_reponse").attr("src", "/webroot/img/icones/warning.png");
	$("#info_reponse").html("<span class='ib l100'>La bonne réponse était : <span class='g'>"+info+"</span></span>");
	$("#info_reponse").append("<span id='closeInfoAnswer' class='mh4 lien_souligne ib l100 p1 i'>Clique ici ou appuie sur Entrée pour passer à la question suivante.</span>");
	$("#closeInfoAnswer").css("cursor", "pointer");
	displayDiv("mauvaise_rep");

	//Mentor message
	mentorMessages("wrongAnswer");

	//Sound activation
	$('#son_bonne_rep')[0].pause();
	$('#son_bonne_rep')[0].currentTime = 0;
	$('#son_mauvaise_rep')[0].pause();
	$('#son_mauvaise_rep')[0].currentTime = 0;
	$('#son_mauvaise_rep')[0].play();

	//New challenge generation on div click
	$("#info_reponse").unbind("click");
	$("#closeInfoAnswer").on("click", function(){
		hideDiv();
		pause = false;
		recordQuestion(0);
	});
}

function recordQuestion(success){
	historicQuestions[currentQuestion.id] ++;
	startNewQuestion();
}

//TIMER MANAGEMENT
function chronoStart(){
  $("#timer").show();
  timer = initialTimer;
	$("#timer").css("height", 0.02*$(window).height());
	plot_chrono = $.jqplot('timer', [[[timer, 1]]], {
		stackSeries: true,
		captureRightClick: false,
		seriesDefaults: {
			renderer: $.jqplot.BarRenderer,
			shadowAngle: 0,
			rendererOptions: {
				barDirection: 'horizontal',
				highlightMouseDown: true,
				highlightMouseOver: true,
				barWidth: 100,
				shadowOffset: 0
			},
			pointLabels: {show: false, formatString: '%d'}
		},
		legend: {
			show: false
		},
		grid: {
			drawGridLines: false,
			gridLineColor: '#FCD9D8',
			background: '#FCD9D8',
			borderWidth: 0,
			shadow: false
		},
		gridPadding: {
			top: 0,
			bottom: 0,
			left: 0,
			right: 0
		},
		axesDefaults: {
			show: false,
			showTicks: false,
			showTickMarks: false,
			min: 0,
			max: timer
		},
		axes: {
			xaxis: {
				show: false,
				showTicks: false,
				showTickMarks: false,
			},
			yaxis: {
				show: false,
				showTicks: false,
				showTickMarks: false
			}
		},
		animate: false,
		animateReplot: !$.jqplot.use_excanvas,
		seriesColors: ["#ac0000"]
	});

	clearInterval(chrono_interval);
  chrono_interval = setInterval(function () {
		if(timer > 0 && !pause)	{
			timer -= 100;
			plot_chrono.series[0].data = [[[timer]]];
			plot_chrono.redraw();
		}
		if (timer <= 0 && !pause)	{
			timer = 0;
			clearInterval(chrono_interval);
			wrong_answer(currentQuestion.answer);
		}
  }, 100);
}

function chronoHide(){
  clearInterval(chrono_interval);
  $("#timer").hide();
}

function shuffle(array) {
    for (let i = array.length; i; i--) {
        let j = Math.floor(Math.random() * i);
        [array[i - 1], array[j]] = [array[j], array[i - 1]];
    }
}

function focusLessPracticedQuestion(){
	let minPracticed = math.min(historicQuestionsFiltered);
	shuffle(allQuestionsFiltered);
	allQuestionsFiltered.forEach(function(value){
		if(historicQuestionsFiltered[value.id] == minPracticed){
			currentQuestion = value;
		}
	});
}

function startNewQuestion(){
	allQuestionsFiltered = [];
	historicQuestionsFiltered = [];
	currentQuestion = {};
	allQuestions.forEach(function(value){
		if(value.category == category || category == "Toutes"){
			allQuestionsFiltered.push(value);
			if(historicQuestions[value.id] == undefined){
				historicQuestions[value.id] = 0;
			}
			historicQuestionsFiltered[value.id] = historicQuestions[value.id];
		}
	});
	if(allQuestionsFiltered.length > 0){
		$("#showMoreTest").show();
		pause = false;
		focusLessPracticedQuestion();
		$("#authorQuestion").html(currentQuestion.playerPseudo);
		$("#authorPortrait").html("<img class='img_80' src='"+currentQuestion.playerPortrait+"'/>");
		$("#newQuestion").html(currentQuestion.question);
		initialTimer = currentQuestion.timer * 1000;
		let arrayTemp = [currentQuestion.answer, currentQuestion.choice2, currentQuestion.choice3, currentQuestion.choice4];
		shuffle(arrayTemp);
		$("#answer1").html(arrayTemp[0]);
		$("#answer2").html(arrayTemp[1]);
		$("#answer3").html(arrayTemp[2]);
		$("#answer4").html(arrayTemp[3]);
		chronoStart();
	} else {
		$("#emptyCategory").dialog("open");
	}
}

function verification(value){
	if(value == currentQuestion.answer){
		good_answer();
	} else {
		wrong_answer(currentQuestion.answer);
	}
}

function getAllQuestions(){
	$.ajax({
		url: '/app/controllers/ajax_challenge.php',
		type: 'POST',
		data: 'getAllCustomQuestions=get',
		dataType: 'json',
		success: function (result, status) {
			allQuestions = result;
		},
		error: function (result, status, error) {
			console.log(result, satuts, error);
		},
		complete: function (result, status) {
		}
	});
}

function cleanTest(){
		pause = true;
		score = 0;
		$("#score").text(score);
		chronoHide();
		$("#authorQuestion").html("");
		$("#authorPortrait").html("");
		$("#newQuestion").html("");
		$("#answer1").html("");
		$("#answer2").html("");
		$("#answer3").html("");
		$("#answer4").html("");
}

$(window).load(function(){

   $(".titles").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	 });

	$("textarea").on("focus", function(){
		resize_textarea($(this));
	});

	//Pausing and resuming challenge
	$('<img id="pause_challenge" class="titles" title="Pause" src="/webroot/img/icones/pause.png"/>').appendTo("#challenge_borders");
	$("#pause_challenge").css("position", "absolute").css("right", "10%").css("top", "5%").css("width","4%").css("cursor", "pointer");
	$('<img id="exit" class="titles" title="Abandonner le défi" src="/webroot/img/icones/exit.png"/>').appendTo("#challenge_borders");
	$("#exit").css("position", "absolute").css("right", "4%").css("top", "5%").css("width","4%").css("cursor", "pointer");

  $("#pause_challenge").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/pause_hover.png");
	});

	$("#pause_challenge").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/pause.png");
	});

	$("#pause_challenge").on("click", function(){
		pause = true;
		$("#screen_challenge").show();
	});

	$("#exit").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/exit_hover.png");
	});

	$("#exit").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/exit.png");
	});

	$("#exit").on("click", function(){
		pause = true;
		$("#exit_confirm").dialog("open");
	});

	$("#exit_confirm").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
			"Abandonner" : function(){
				$(this).dialog("close");
				$(location).attr('href',"/index.php");
			},
			"Continuer": function(){
				pause = false;
				$(this).dialog("close");
			}
		}
	});

  $("#resume_challenge").css("cursor", "pointer");

  $("#resume_challenge").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/resume_hover.png");
	});

	$("#resume_challenge").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/resume.png");
	});

  pause = true;
	$("#resume_challenge").hide().removeClass("cache");
	$("#resume_challenge").show("slide", {easing:"swing"}, 500);
	$("#resume_challenge").on("click", function(){
		$("#resume_challenge").attr("title", "Allez, on y retourne !");
		$("#resume_challenge").unbind("click");
		pause = false;
		$("#screen_challenge").hide("clip",{easing:"swing"}, 500, function(){
			$("#loading_msg").hide();
			$("#resume_challenge").on("click", function(){
				pause = false;
				$("#screen_challenge").hide();
			});
		});
	});

	if($(".icone_reponse").length){$(".icone_reponse").hide()}; //Hide the div displaying the answer
	if($("#info_reponse").length){$("#info_reponse").hide()}; //Hide the image displaying the answer

	$("#question").css("height", "80px");
	$(".choice").css("width", "45%");
	$("#categoryChoice").selectmenu();
	$("#categoryChoiceTest").selectmenu();

	$("#categoryChoiceTest").on("selectmenuchange", function(){
		category = $(this).val();
		if(category != "--"){
			startNewQuestion();
		}
	});

	$("#question").on("keyup",function(){
		validQuestion($(this));
	});

	$(".choice").on("keyup",function(){
		validChoice($(this));
	});

	$("#incorrectQuestion").dialog({
		autoOpen: false,
		resizable: false
	});

	$("#emptyCategory").dialog({
		autoOpen: false,
		resizable: false
	});

	$("#createQuestion").on("click", function(){
		var ok = validQuestion($("#question")) * validChoice($("#answer")) * validChoice($("#choice2")) * validChoice($("#choice3")) * validChoice($("#choice4"));
		if(ok != 0){
			var newQuestion = {category: $("#categoryChoice").val(), question: $("#question").val(), answer: $("#answer").val(), choice2: $("#choice2").val(), choice3: $("#choice3").val(), choice4: $("#choice4").val()};
			$("#loadingCreation").show();
			$("#switchTest").hide();
			$("#createQuestion").hide();
			$.ajax({
				url: '/app/controllers/ajax_challenge.php',
				type: 'POST',
				data: 'newCustomQuestion='+JSON.stringify(newQuestion),
				dataType: 'json',
				success: function (result, status) {
					$("#question").val("");
					$("#answer").val("");
					$("#choice2").val("");
					$("#choice3").val("");
					$("#choice4").val("");
					$("#confirmationMsg").switchClass("rouge", "vert").html("Question enregistrée !");
					display_clip($("#confirmationMsg"));
					allQuestions = result;
				},
				error: function (result, status, error) {
					$("#confirmationMsg").switchClass("vert", "rouge").html("Problème serveur : question non enregistrée...");
					display_clip($("#confirmationMsg"));
				},
				complete: function (result, status) {
					$("#loadingCreation").hide();
					$("#switchTest").show();
					$("#createQuestion").show();
				}
			});
		} else{
			$("#incorrectQuestion").dialog("open");
		}
  });

	$("#questionCreation").hide();
	$(".questionTest").hide();
	$("#loadingCreation").hide();
	$("#confirmationMsg").hide();

	$("#selectCreation").on("click", function(){
		$("#selectMode").hide();
		display_clip($("#questionCreation"));
	});

	getAllQuestions();

	$("#selectTest").on("click", function(){
		$("#selectMode").hide();
		$("#showMoreTest").hide();
		display_clip($(".questionTest"));
	});

	$("#switchTest").on("click", function(){
		$("#selectMode").hide();
		$("#questionCreation").hide();
		$("#showMoreTest").hide();
		display_clip($(".questionTest"));
	});

	$("#switchCreation").on("click", function(){
		$("#selectMode").hide();
		$(".questionTest").hide();
		cleanTest();
		display_clip($("#questionCreation"));
	});

	$("#challenge_buttons span").on("click", function(){
		verification($(this).html());
	});

	$(document).on("keydown", function (event) {
	  var key = event.which || event.keyCode;
		//Enter Key
		if (key == 13) {
			if(pause !=undefined && pause==true) {
				hideDiv();
				pause = false;
				recordQuestion(0);
			}
		}
	});


});
