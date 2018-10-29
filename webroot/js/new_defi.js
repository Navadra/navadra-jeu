// Callback to get new challenge id
function get_new_exercice(){
	if(exerciseCount < numberExercises) //While the player has not reached the maximum possible
	{
		exerciseCount ++;
		$("#questionCount").html(exerciseCount);
		var challengeRequest = challenge_choice(potential_challenges, element);
		codeChallenge = challengeRequest[0];
		masteryChallenge = challengeRequest[1];
		console.log("ID CHALLENGE = " + codeChallenge);
		get_challenge_json(codeChallenge); // !! Callback to start_exercise(codeChallenge);
	}
	else {
		challengeEnd();
	}
}

function challengeTimeOut()
{
  call_verification(); //Verifies if the answer given by the user is correct
}

//Save the result and display the end screen
function challengeEnd()
{
	var total_duration = 0;
	$.each(exercises_record, function(index, exercise){
		total_duration += exercise.answerTime;
	});
	total_duration = total_duration / 1000;
	$("input[name=total_duration]").val(total_duration);
	$.ajax({
		url: '/app/controllers/ajax_challenge.php',
		type: 'POST',
		data: 'exercises_record='+JSON.stringify(exercises_record),
		dataType: 'json',
		success: function (result, status) {
		},
		error: function (result, status, error) {
		  //Erreur est une chaine de caractère à afficher au joueur
		},
		complete: function (result, status) {
		  $("#form_challenge").submit();
		}
	  });
}

$(function(){

  situation = 1;

  // Count the number of exercises given to the user
  if (tries == 0)  {
  	numberExercises = 10;
  } else  {
 	numberExercises = 10;
  }

  $("#questionTot").html("/" + numberExercises);

});

$(window).load(function(){
	get_new_exercice();
});
