// Callback to get new challenge code
function get_new_exercice()
{
	if(exerciseCount == 10 || exerciseCount == 20)
	{
		enough_training();
	}
	exerciseCount ++;
	$("#questionCount").html(exerciseCount);
	var challengeRequest = challenge_choice(potential_challenges, element);
	codeChallenge = challengeRequest[0];
	masteryChallenge = challengeRequest[1];
	console.log("ID CHALLENGE = " + codeChallenge);
	get_challenge_json(codeChallenge); // !! Callback to start_exercise(codeChallenge);
}

function enough_training(){
	$("#bulle_daide").css("position", "absolute").css("cursor", "pointer");
	$("#bulle_daide").css("top", "30%").css("width", "30%").css("left", "45%");
	$("#bulle_daide").html("<span class='g ib l100 mb6'>On dirait que tu es prêt"+e+" !</span><span class='ib l100'>Pour finir l'entrainement et passer au vrai défi, clique sur le bouton de retour.</span>");
	$("#bulle_daide").show("clip", {easing: "swing"}, 500);
	if(playerVolumeSoundEffects == 1){
	$("#son_bulles_daide").trigger("play");
	}
	$('<img id="closeIndexPopUp" class="img_80" src="/webroot/img/icones/refuser.png">').appendTo($("#bulle_daide")).load(function() {
		$("#closeIndexPopUp").css("position", "absolute").css("top", -0.4*$("#closeIndexPopUp").height()).css("right", -0.4*$("#closeIndexPopUp").height()).on("click", function () {
			object.hide("clip", {easing: "swing"}, 500,function(){
			});
		});
	});
	$("#bulle_daide").on("click", function(){
		$("#bulle_daide").hide("clip", {easing: "swing"}, 500);
	});
	if(!$("#fleche_droite").length)	{
		$('<img id="fleche_droite" class="img_50" src="/webroot/img/icones/fleche2.png">').appendTo("body").css("position", "absolute").hide();
	}
	var pos_x = $("#bouton_retour").offset().left;
	var pos_y = $("#bouton_retour").offset().top;
	var larg = $("#bouton_retour").width();
	var haut = $("#bouton_retour").height();
	$("#fleche_droite").css("height", haut*2);
	var larg_fleche = $("#fleche_droite").width();
	var haut_fleche = $("#fleche_droite").height();
	$("#fleche_droite").css("top", pos_y + 0.5*haut - 0.5*haut_fleche).css("left", pos_x + larg);
	$("#fleche_droite").show("scale", {easing: "swing", percent: 400}, 1000, function(){
		$("#fleche_droite").effect("pulsate", {easing: "swing"}, 1000, function(){
		});
	});
}

function challengeTimeOut(){
  console.log("ChallengeTimeOut");
}

function challengeEnd(){
	console.log("ChallengeEndTraining");
}

$(function(){

  situation = 0;

  //Record the training
  $("#bouton_retour").on("click", function(event){
	  event.preventDefault();
	  var wrong_answers = exerciseCount - score - 1;
		$.ajax({
		url: '/app/controllers/ajax_challenge.php',
		type: 'POST',
		data: 'element='+element+'&challenge='+challenge_name+'&good_answers='+score+'&wrong_answers='+wrong_answers+'&initial_level='+level+'&train=1&diagnosis='+diagnosis+'&exercises_record='+JSON.stringify(exercises_record),
		dataType: 'json',
		success: function (result, status) {
		},
		error: function (result, status, error) {
		  //Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function (result, status) {
		  $(location).attr('href', $("#bouton_retour a").attr('href'));
		}
	  });
  });

  $("#bouton_retour").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
  });

});

$(window).load(function(){
	get_new_exercice();
});
