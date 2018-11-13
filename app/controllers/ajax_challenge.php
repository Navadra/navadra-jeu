<?php
require("include_path.php");

$joueur = $_SESSION["joueur"];
if (!isset( $joueur )) {
	header("Location: https://jeu.navadra.com");
	exit;
}

$joueur = $manager->get($joueur->id()); //Permet de prendre en comptes les actions des autres joueurs


if(isset($_POST["codeChallenge"])) //Get the json file of the desired challenge
{
	$codeChallenge_array = explode("_", $_POST["codeChallenge"]);
	$challenge_category = $codeChallenge_array[0];
	$challenge_name = $codeChallenge_array[1];
	$challenge_level = $codeChallenge_array[2];
	$challenge_exercise = $codeChallenge_array[3];
	
//  if($server == "localhost" || $server == "127.0.0.1"){
    $json = file_get_contents('../../generators/challenges/'.$challenge_category.'/'.$challenge_name.'/'.$challenge_level.'/'.$_POST["codeChallenge"].'.json');
/*  }
  else {
    $json = $mem->get($challenge_category.'/'.$challenge_name.'/'.$challenge_level.'/'.$_POST["codeChallenge"].'.json');
  }*/
  echo $json;
}

if(isset($_POST["historicAnswerTime"])) //Get the average answer time for the 3 last challenges of each type
{
	$historicExercises = $exercises_manager->get_average_answer_time($joueur);
	echo json_encode($historicExercises);
}

if(isset($_POST["historicExercises"])) //Get the average answer time for the 3 last challenges of each type
{
	$exercises = $exercises_manager->get_all_exercises_player($joueur->id());
	$exercises_json = array();
	foreach($exercises as $exercise){
		if($exercise->getSuccess() == 1){
			$complement = 0.2;
		} elseif($exercise->getSuccess() == 0){
			$complement = -0.2;
		}
		$score = $exercise->getMastery() + $complement;
		if($exercise->getChallenge() != "0"){ //No need for base exercises
			$exercises_json[] = array(
				"challenge" => $exercise->getChallenge(),
				"score" => $score,
				"date" => $exercise->getDate_exercise(),
				"diagnosis" => (int)$exercise->getDiagnosis()
				);
		}
	}
	echo json_encode($exercises_json);
}


if(isset($_POST["good_answers"], $_POST["wrong_answers"])) //Record a score during a challenge training
{
  //Save the score in DB
  $newScore = new Score(array(
      "element" => $_POST["element"],
	  "challenge" => $_POST["challenge"],
	  "id_joueur" => $joueur->id(),
	  "good_answers" => (int)$_POST["good_answers"],
	  "wrong_answers" => (int)$_POST["wrong_answers"],
	  "initial_level" => (int)$_POST["initial_level"],
	  "end_level" => (int)$_POST["initial_level"],
      "train" => 1,
	  "diagnosis" => (int)$_POST["diagnosis"],
	  "player_lvl" => $joueur->niveau(),
	  "player_xp_percent" => round($joueur->xp()/$joueur->xp_requise()*100),
	  "player_rank" => $manager->current_level_ranking($joueur),
  ));
  $scores_manager->add($newScore);
}

if(isset($_POST["exercises_record"])) //Record the exercises after a challenge
{
  //Save the exercises in DB
  $allExercises = json_decode($_POST["exercises_record"], true); //True to convert into associative array
  foreach($allExercises as $exercise)
  {
	  $newExercise = new Exercise(array(
		  "element" => $exercise["element"],
		  "challenge" => $exercise["challengeName"],
		  "level" => (int)$exercise["level"],
		  "number" => (int)$exercise["number"],
		  "mastery" => (int)$exercise["mastery"],
		  "id_joueur" => $joueur->id(),
		  "initial_time" => (int)$exercise["initialTime"],
		  "answer_time" => (int)$exercise["answerTime"],
		  "success" => (int)$exercise["success"],
		  "situation" => (int)$exercise["situation"],
		  "diagnosis" => (int)$exercise["diagnosis"],
	  ));
	  $exercises_manager->add($newExercise);
  }
}

if(isset($_POST["newCustomQuestion"])) {
  $newCustomQuestion = json_decode($_POST["newCustomQuestion"], true); //True to convert into associative array
	$newQuestion = new Question(array(
		"idPlayer" => $joueur->id(),
	  "category" => $newCustomQuestion["category"],
	  "question" => $newCustomQuestion["question"],
	  "answer" => $newCustomQuestion["answer"],
	  "choice2" => $newCustomQuestion["choice2"],
	  "choice3" => $newCustomQuestion["choice3"],
		"choice4" => $newCustomQuestion["choice4"]
	));
	$questions_manager->add($newQuestion);
	$questions = $questions_manager->getAll();
	$questionsJson = array();
	foreach($questions as $question){
		$player=$manager->get($question->idPlayer());
		$questionsJson[] = array(
				"id" => $question->id(),
				"idPlayer" => $question->idPlayer(),
				"category" => $question->category(),
			  "question" => $question->question(),
			  "answer" => $question->answer(),
			  "choice2" => $question->choice2(),
			  "choice3" => $question->choice3(),
				"choice4" => $question->choice4(),
				"timer" => $question->timer(),
				"goodAnswers" => $question->goodAnswers(),
				"wrongAnswers" => $question->wrongAnswers(),
				"dateCreation" => $question->dateCreation(),
				"playerPseudo" => $player->pseudo(),
				"playerPortrait" => $player->full_portrait()
			);
	}
	echo json_encode($questionsJson);
}

if(isset($_POST["getAllCustomQuestions"])) {
	$questions = $questions_manager->getAll();
	$questionsJson = array();
	foreach($questions as $question){
		$player=$manager->get($question->idPlayer());
		$questionsJson[] = array(
				"id" => $question->id(),
				"idPlayer" => $question->idPlayer(),
				"category" => $question->category(),
			  "question" => $question->question(),
			  "answer" => $question->answer(),
			  "choice2" => $question->choice2(),
			  "choice3" => $question->choice3(),
				"choice4" => $question->choice4(),
				"timer" => $question->timer(),
				"goodAnswers" => $question->goodAnswers(),
				"wrongAnswers" => $question->wrongAnswers(),
				"dateCreation" => $question->dateCreation(),
				"playerPseudo" => $player->pseudo(),
				"playerPortrait" => $player->full_portrait()
			);
	}
	echo json_encode($questionsJson);
}
