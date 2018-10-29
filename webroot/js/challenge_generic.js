// JavaScript Document

//GLOBAL VARIABLES
var exerciseCount = 0;
var score = 0;
var consecutiveGoodAnswers = 0; //Count the number of good answers made in a row
var anim1 = 0; //Variable associated with the first SetTimeout
var anim2 = 0; //Variable associated with the second SetTimeout
var chrono_interval; //Variable associated with the chrono SetTimeout
var LastQuestions = []; //Variable to keep track of the scores for the last 2 questions

var idChallenge = parseInt($("input[name=challenge_id]").val());
var codeChallenge; //Type : "element_name_x_x
var masteryChallenge; //Current mastery of the challenge
var tries = parseInt($("#challenge_tries").html());
if(tries == 0) {
	var diagnosis = 1;
} else {
	var diagnosis = 0;
}
var element = $("#challenge_element").html();
//While all exercises are not available
var level = parseInt($("#challenge_level").html());
if(level < 1){
	level = 1;
}
var initial_level = level;
var challenge_name = $("#challenge_name").html();
var potential_challenges = [{"element": element, "name": challenge_name, "currentMastery": level}];

var numberExercises;
var situation; //0 for training, 1 for challenges and 2 for fights

var timer = 0;
var initialTimer = 0;
var answerTime = 0;
var pause= false; //Pause the SetInterval when user submit a wrong answer



//UTILS FUNCTIONS
//Mentor messages
function mentorMessages(answer){
	switch(answer)
	{
		case "initialization" :
			var rand = math.randomInt(1, 2);
			if(rand==1)
				{var msg = "Allez, à toi de jouer!";}
			break;
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

//Reset animations (if the user is chaining too fast the answers)
function animationsReset(){
	if(anim1 != 0)
	{
		clearTimeout(anim1);
		hideDiv();
		anim1 = 0;
	}
	if(anim2 != 0)
	{
		$("#info_reponse").attr("class", "sans_rep");
		clearTimeout(anim2);
		$("#info_reponse").hide();
		anim2 = 0;
	}
	if($("#tutorial_focus").length) {
		$("#tutorial_focus").hide();
	}
}

//Hide the div containing the answer
function hideDiv(){
	$(".icone_reponse").hide();
	$("#info_reponse").switchClass($("#info_reponse").attr("class"), "sans_rep", 500);
	if($("#tutorial_focus").length) {
		$("#tutorial_focus").hide();
	}
	anim2 = setTimeout(function(){
		$("#info_reponse").hide();
		anim2=0;
	}, 500);
}

//Display the div containing the answer and format it with CSS classes
function displayDiv(type){
	$(".icone_reponse").show();
	$("#info_reponse").show();
	$("#info_reponse").switchClass("sans_rep", type, 500, function(){
		if(playerTuto != "fini" && !$("#tutorial_focus").length && type == "mauvaise_rep"){
			$("<img id='tutorial_focus' class='img_200' src='/webroot/img/icones/fleche4.png'/>").appendTo("body").css("position", "absolute");
		}
		if(playerTuto != "fini" && type == "mauvaise_rep") {
			$("#tutorial_focus").css("top", $("#info_reponse").offset().top + $("#info_reponse").outerHeight()).css("left", $("#info_reponse").offset().left + 0.5*$("#info_reponse").outerWidth() - 0.5*$("#tutorial_focus").width())
			$("#tutorial_focus").show();
			$("#tutorial_focus").effect("pulsate", {easing: "swing"}, 1000);
		}
	});
}

//MAIN FUNCTIONS
//Setup and display the div in case of a good answer
function good_answer(){
	//Variable incrementation
	animationsReset();
	score ++;
	answerTime = initialTimer - timer;
	$("input[name=score]").val(score);
	$("#score").text(score);
	consecutiveGoodAnswers ++;
	LastQuestions.push(1);

	//Div setup and display
	$(".icone_reponse").attr("src", "/webroot/img/icones/correct.png");
	if(consecutiveGoodAnswers > 1)
		{$("#info_reponse").html("<span class='p3'>Bravo !<br>" + consecutiveGoodAnswers + " bonnes réponses de suite !</span>");}
	else
		{$("#info_reponse").html("<span class='p3'>Bravo !<br>Continue sur ta lancée !</span>");}
	$("#info_reponse").css("cursor", "default");
	$("#info_reponse").unbind("click");
	displayDiv("bonne_rep");
	anim1 = setTimeout(function(){hideDiv();anim1=0;}, 1500);

	//Mentor message
	mentorMessages("goodAnswer");

	//Sound activation
	if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
		//do nothing
	} else {
		$('#son_bonne_rep')[0].pause();
		$('#son_bonne_rep')[0].currentTime = 0;
		$('#son_mauvaise_rep')[0].pause();
		$('#son_mauvaise_rep')[0].currentTime = 0;
		$('#son_bonne_rep')[0].play();
	}

	//Record the answer in DB
	record_exercise(1);
}

//Setup and display the div in case of a wrong answer
function wrong_answer(info){
	//Variable incrementation
	animationsReset();
	answerTime = initialTimer - timer;
	consecutiveGoodAnswers = 0;
	pause = true;
	LastQuestions.push(0);

	$("#currentQuestion").empty();
	$("#currentQuestion").html("<span class='ib l100 p4 mb4 g'>"+info+"</span>");
	$("#challenge_content").children().detach().appendTo("#currentQuestion");
	if($("#challenge_validate").length){
		$("#challenge_validate").hide();
		$("#submitIndication").hide();
	}
	setTimeout(function(){
		if($("#circle_proportionality").length){
			var columnTemp = challenge.view.circleProportionality[0];
			var positionTemp = challenge.view.circleProportionality[1];
			$("#circle_proportionality").detach().appendTo("#firstPart");
			circleProportionalityPositionning($("#circle_proportionality"), columnTemp, positionTemp);
		}
	}, 500)
	$("#challenge_buttons span").css("background-color", "#535353");
	$("#explanations").empty();
	$("#explanations").append("<span id='challengeHint' class='ib l100 align_gauche mh2 relatif'>"+challenge.hint+"</span>");
	$("#firstPart").switchClass("col50", "l100");
	$("#secondPart").hide();
	$("#showExplanations").show();
	$("#correctionScreen").show("slide", {easing:"swing"}, 500);
	//Mentor message
	mentorMessages("wrongAnswer");

	//Possibility to report bug
	$("#suggestion").detach().appendTo("#correctionScreen span:eq(0)");

	//Sound activation
	if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
		//do nothing
	} else {
		$('#son_bonne_rep')[0].pause();
		$('#son_bonne_rep')[0].currentTime = 0;
		$('#son_mauvaise_rep')[0].pause();
		$('#son_mauvaise_rep')[0].currentTime = 0;
		$('#son_mauvaise_rep')[0].play();
	}
}

function closeCorrection(){
	$("#currentQuestion").empty();
	if($("#circle_proportionality").length){
		$("#circle_proportionality").remove();
	}
	$("#explanations").empty();
	$("#correctionScreen").hide();
	$("#suggestion").detach().appendTo("#challenge_borders");
	pause = false;
	//Record the answer
	record_exercise(0);
}

function levelAdjustement(){
	//If it is the first time the player is trying, adjust the level
	if(tries == 0)	{
		var Last2 = LastQuestions.slice(math.max(0,LastQuestions.length-2)); // Take the last 2 questions
		if(math.sum(Last2) == 2){
			if(level < 5){
				level ++;
				potential_challenges[0]["currentMastery"] = level;
				LastQuestions = [];
			}
		}
		if(math.sum(Last2) == 0 && Last2.length == 2){
			if(level > 1){
				level --;
				potential_challenges[0]["currentMastery"] = level;
				LastQuestions = [];
			}
		}
	} else {
		var Last5 = LastQuestions.slice(math.max(0,LastQuestions.length-5)); // Take the last 5 questions
		if(math.sum(Last5) == 5 && exerciseCount < 10 && level < 5){ //If it's not the first time and the player has 5 good answers in a row
			level++;
			potential_challenges[0]["currentMastery"] = level;
			LastQuestions = [];
		}
		else if(exerciseCount == 10 && score>=8 && level < 5){ //If it's not the first time and the player has at least a score of 8/10 at the end of the challenge
			level++;
		}
		else if(exerciseCount == 10 && score==10 && initial_level == 5){ //If it's the ultimate challenge
			level++;
		}
	}
	$("input[name=level]").val(level);
}

function timingAdjustement(success){
	if(historic_timing[codeChallenge] != undefined) //If there is already some data on this exercise
	{
		if(success == 1)
		{
			historic_timing[codeChallenge] = math.mean(historic_timing[codeChallenge], answerTime);
		}
		else if(success == 0)
		{
			historic_timing[codeChallenge] = math.mean(historic_timing[codeChallenge], initialTimer);
		}
	}
	else
	{
		if(success == 1)
		{
			historic_timing[codeChallenge] = answerTime;
		}
		else if(success == 0)
		{
			historic_timing[codeChallenge] = initialTimer;
		}
	}
}

function start_exercise(id_chal){
	chronoHide();
	if (typeof challenge == 'undefined' || typeof challenge.timer == 'undefined') {
		console.log("le fichier de l'exercice (" + id_chal + ") n'existe pas : ca va planter !");
	}
	initialization();
	chronoStart();
}

function record_exercise(success){
	var levelTemp = parseInt(codeChallenge.match(/\w+_\w+_(\d+)_\d+/)[1]);
	var numberTemp = parseInt(codeChallenge.match(/\w+_\w+_\d+_(\d+)/)[1]);
	exercises_record.push({
		element: element,
		challengeName: challenge_name,
		level: levelTemp,
		number: numberTemp,
		mastery: level,
		initialTime: initialTimer,
		answerTime: answerTime,
		success: success,
		situation: situation,
	    diagnosis: diagnosis
	});
	levelAdjustement();
	timingAdjustement(success);
	if(success == 0 && initial_level == 5 && situation == 1) {
		challengeEnd();
	} else {
		get_new_exercice();
	}
}

//TIMER MANAGEMENT
function chronoStart(){

	if(historic_timing[codeChallenge] != undefined) {
		var userFactor = 4;
		var baseFactor = 1;
		initialTimer = (historic_timing[codeChallenge]*userFactor + challenge.timer*baseFactor)/(userFactor+baseFactor);
	}  else {
		initialTimer = challenge.timer;
	}
	timer = initialTimer;

	if($("#timer").length){
	  $("#timer").show();

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

	  chrono_interval = setInterval(function () {
		if(timer > 0 && !pause)	{
			timer -= 100;
			plot_chrono.series[0].data = [[[timer]]];
			plot_chrono.redraw();
		}
		if (timer <= 0)	{
			timer = 0;
			clearInterval(chrono_interval);
			challengeTimeOut();
		}
	  }, 100);
	} else {
		chrono_interval = setInterval(function () {
		if(timer > 0 && !pause)	{
			timer -= 100;
		}
		if (timer <= 0)	{
			timer = 0;
			clearInterval(chrono_interval);
			challengeTimeOut();
		}
	  }, 100);
	}
}

function chronoHide(){
	clearInterval(chrono_interval);
	if($("#timer").length){
	  $("#timer").hide();
	}
}

//INITIALIZATION
if($(".icone_reponse").length){$(".icone_reponse").hide()}; //Hide the div displaying the answer
if($("#info_reponse").length){$("#info_reponse").hide()}; //Hide the image displaying the answer
mentorMessages("initialization"); //Permet d'afficher le message d'accueil du tuteur


$(window).load(function(){ //Handle display errors
	if($("#musique_defi").length)	{
		$("#musique_defi").css("position", "absolute").css("bottom", "1%");
		$("#musique_defi").css("left", 0.5*$(window).width() - 0.5*$("#musique_defi").width());
	}
	//Si le tuteur empiète sur les boutons de réponse
	if($("#challenge_buttons span:eq(0)").length)	{
		if($("#tuteur_defi").offset().left + $("#tuteur_defi").width() > $("#challenge_buttons span:eq(0)").offset().left)
		{
			$("#tuteur_defi").css("left", "0%"); //On enlève le décalage du tuteur
			if($("#tuteur_defi").offset().left + $("#tuteur_defi").width() > $("#challenge_buttons span:eq(0)").offset().left) //Si le tuteur empiète encore sur les boutons de réponse
			{
				$("#tuteur_defi").css("width", $("#challenge_buttons span:eq(0)").offset().left - $("#tuteur_defi").offset().left).css("height", "auto"); //On rétréci le tuteur
			}
		}
	}

	//Si le tuteur empiète sur les chiffres à cliquer
	if($("#chiffres").length)	{
		if($("#tuteur_defi").offset().left + $("#tuteur_defi").width() > $("#chiffres span:eq(0)").offset().left)
		{
			$("#tuteur_defi").css("left", "0%"); //On enlève le décalage du tuteur
			if($("#tuteur_defi").offset().left + $("#tuteur_defi").width() > $("#chiffres span:eq(0)").offset().left) //Si le tuteur empiète encore sur les chiffres
			{
				$("#tuteur_defi").css("width", $("#chiffres span:eq(0)").offset().left - $("#tuteur_defi").offset().left).css("height", "auto"); //On rétréci le tuteur
			}
		}
	}

	//Si le tuteur empiète sur les chiffres à placer
	if($(".rep:eq(0)").length)	{
		if($("#tuteur_defi").offset().left + $("#tuteur_defi").width() > $(".rep:eq(0)").offset().left)
		{
			$("#tuteur_defi").css("left", "0%"); //On enlève le décalage du tuteur
			if($("#tuteur_defi").offset().left + $("#tuteur_defi").width() > $(".rep:eq(0)").offset().left) //Si le tuteur empiète encore sur les chiffres
			{
				$("#tuteur_defi").css("width", $(".rep:eq(0)").offset().left - $("#tuteur_defi").offset().left).css("height", "auto"); //On rétréci le tuteur
			}
		}
	}

	//For Bug Feedback
   $("textarea[name=descriptif_bug]").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});

	$("textarea").on("focus", function(){
		resize_textarea($(this));
	});

	$("textarea[name=descriptif_bug]").on("keyup",function(){
		descriptif_long_valide($(this));
	});

	$("#fermer_feedback").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/refuser_selec.png");
	});

	$("#fermer_feedback").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/refuser.png");
	});

	$("#fermer_feedback").on("click", function(){
		hide_clip($("#feedback"));
		if(!$("#correctionScreen").is(":visible")){
			pause = false;
		}
	});

	$(".valider_feedback").on("click", function(event){
		event.preventDefault();
		if( descriptif_long_valide($("textarea[name=descriptif_bug]")) ){
			report_bug(codeChallenge, user_answer, true_answer);
		}
	});

	$('<div id="suggestion" class="bulle_daide_cliquable">Reporter un bug</div>').appendTo("#challenge_borders");
	$("#suggestion").css("position", "absolute").css("right", "5%").css("top", "-5%").css("width","8%");

	$("#suggestion").on("click", function(){
		display_clip($("#feedback"));
		pause = true;
	});

	$("#feedback").hide();


	//Pausing and resuming challenge
	$('<img id="pause_challenge" title="Pause" src="/webroot/img/icones/pause.png"/>').appendTo("#challenge_borders");
	$("#pause_challenge").css("position", "absolute").css("right", "10%").css("top", "5%").css("width","4%").css("cursor", "pointer");
	$('<img id="exit" title="Abandonner le défi" src="/webroot/img/icones/exit.png"/>').appendTo("#challenge_borders");
	$("#exit").css("position", "absolute").css("right", "4%").css("top", "5%").css("width","4%").css("cursor", "pointer");

	$("#pause_challenge").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
  });

  $("#exit").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
  });

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
		dialogClass: "no-close",
		buttons: {
			"Abandonner" : function(){
				$(this).dialog("close");
				abortChallenge();
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

	$("#resume_challenge").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});

  pause = true;
	$("#resume_challenge").hide().removeClass("cache");
	$("#resume_challenge").show("slide", {easing:"swing"}, 500);
	$("#resume_challenge").on("click", function(){
		$("#resume_challenge").attr("title", "Allez, on y retourne !");
		$("#resume_challenge").unbind("click");
		pause = false;
		if($("#challenge_content input:eq(0)").length){
			$("#challenge_content input:eq(0)").focus();
		}
		$("#screen_challenge").hide("clip",{easing:"swing"}, 500, function(){
			$("#loading_msg").hide();
			$("#resume_challenge").on("click", function(){
				pause = false;
				$("#screen_challenge").hide();
				if($("#challenge_content input:eq(0)").length){
					$("#challenge_content input:eq(0)").focus();
				}
			});
		});
	});

	$("#correctionScreen").hide();

	$("#commandsChallenge").css("position", "absolute").css("bottom", "2%").css("width", "100%").css("cursor","pointer");

	$("#hideCorrection").on("mouseover", function(){
		$(this).attr("src", "/webroot/img/icones/resume_hover.png");
	});

	$("#hideCorrection").on("mouseout", function(){
		$(this).attr("src", "/webroot/img/icones/resume.png");
	});

	$("#hideCorrection").tooltip({
		show: {
			effect: "slideDown",
			delay: 250
		}
	});

	$("#hideCorrection").on("click", function(){
		closeCorrection();
	});

	$("#showExplanations").on("click", function(){
		$(this).hide();
		$("#firstPart").switchClass("l100", "col50", 500, "swing", function(){
			$("#secondPart").show();
		});
	});

	function abortChallenge(){
		$(location).attr('href',"/index.php");
	}




});
