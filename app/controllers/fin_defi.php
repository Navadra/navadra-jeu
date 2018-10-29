<?php
require("include_path.php");
require("controleur_global.php");
if($joueur->tuto() != "fini")
{
  $mentor = "/webroot/img/personnages/".strtolower($challenges_manager->new_mentor($joueur))."_portrait.png"; //For tutorial
}
else
{
  $mentor = $joueur->portrait_tuteur();
}
//End of the challenge and rewards display
if(isset($_POST["challenge_id"]) || ($joueur->tuto() != "fini" && $challenges_manager->countTries($joueur) > 0) )
{
  if(isset($_POST["challenge_id"]))
  {
    $challenge = $challenges_manager->get_by_id((int)$_POST["challenge_id"]);
  }
  else
  {
   $lastScore = $scores_manager->get_dernier_score($joueur);
   $challenge = $challenges_manager->get_by_name($joueur, $lastScore->getChallenge());
   $_POST["level"] = $challenge->getCurrent_level();
   $_POST["score"] = $lastScore->getGood_answers();
  }
  //Rewards attribution
  $formerLevel = $challenge->getCurrent_level(); //Get the best level reached by the player so far
  $newLevel = (int)$_POST["level"]; //le niveau atteint par le joueur cette fois ci
  $playerFormerLevel = $joueur->niveau(); //Stock former player level before giving XP
  $achievementMsg = "";
	$congratulations = 0;
	$endFreePeriod = 0;
  //If the player has mastered the level give a reward message
  if ($newLevel > $formerLevel){
    $challenge->setCurrent_level($newLevel);
		$achievementMsg = $challenge->endMessage($joueur);
		$congratulations = $challenge->congratulations();
  } elseif($joueur->tuto() != "fini"){
		$fictiveChallenge = new Challenge(array(
			"current_level" => $newLevel,
			"name" => $challenge->getName(),
			"tries" => 0
		));
		$achievementMsg = $fictiveChallenge->endMessage($joueur);
		$congratulations = $challenge->congratulations();
		$formerLevel = 0;
	}
	elseif($newLevel < $formerLevel){
    $newLevel = $formerLevel; //Correcting rare bugs
  }
  //In any case
  $regularXP = $challenge->regularXP((int)$_POST["score"]);
  $regularPyrs = $challenge->regularPyrs((int)$_POST["score"], $joueur);
	if($joueur->tuto() == "fini" && $classrooms_manager->hasTimeSlot($joueur) != "NoTimeSlot"){
		$regularXP = ceil($regularXP/3);
		$regularPyrs = ceil($regularPyrs/3);
	}

  $diagnosis = 0;
  $ultimate_missed = 0;
  //Only if the player is not trying to refresh the page to earn more XP and Pyrs
  if(!isset($_SESSION["form_soumis"]) || $_SESSION["form_soumis"] == false) {
    $_SESSION["form_soumis"] = true;
		$challenge->setTries($challenge->getTries() + 1);
		if( $formerLevel == 5 && $newLevel == 5){ //If the player has missed his ultimate
	        $ultimate_missed = 1;
	      	$wrong_answers = 1;
		} else {
			//Determine if it was a diagnosis
			if($challenge->getTries() == 1) {
			  $diagnosis = 1;
			  $challenge->setInitial_level($newLevel);
			}
			$limitedBefore = $joueur->gameLimitation();
			$regularPyrs = $joueur->addPyrs($regularPyrs, $challenge->getElement());
      $joueur->gagner_xp($regularXP);
			$limitedAfter = $joueur->gameLimitation();
			if($limitedBefore == 0 && $limitedAfter==1){ //Level up makes the player limited
				//$countChallenges = $challenges_manager->countTries($joueur) + 1;
				$countChallenges = $exercises_manager->nb_exercises($joueur);
				$classroom = $classrooms_manager->getClassroomStudent($joueur);
				if($classroom != "NoClassroom"){
					$teacher = $manager->get($classroom->idTeacher());
				} else {
					$teacher = "NoTeacher";
				}
				$joueur->sendEmailEndFreePeriod("player", $countChallenges, $teacher);
				$joueur->sendEmailEndFreePeriod("parent", $countChallenges, $teacher);
				//$endFreePeriod = 1;
			}
			if($joueur->niveau() > $playerFormerLevel && $joueur->niveau() == 8 && $joueur->gameLimitation() == 1){
				//$reminderEndFreePeriod = 1;
				//$countChallenges = $challenges_manager->countTries($joueur) + 1;
				$countChallenges = $exercises_manager->nb_exercises($joueur);
				$timeSlots = $timeslots_manager->countAll();
				$joueur->sendReminderEndFreePeriod("parent", $countChallenges, $timeSlots);
				/*
				$expediteur = $manager->get(47);
				if($joueur->id() != $expediteur->id()){
					$contenu = $joueur->emailFeedbackFreePlan();
					if($conversations_manager->conversation_existante($expediteur->id(), $joueur->id())){ //Si il existe déjà une conversation entre ces joueurs
						$conversation = $conversations_manager->get($expediteur->id(), $joueur->id());
						$message = new Message(array("id_conversation" => $conversation->id(), "expediteur" => $expediteur->id(), "destinataire" => $joueur->id(), "contenu" => $contenu));
						$messages_manager -> add($message);
					} else { //Sinon on créer une nouvelle conversation
						$conversation = new Conversation(array("joueur1" => $expediteur->id(), "joueur2" => $joueur->id()));
						$conversations_manager -> add($conversation);
						$message = new Message(array("id_conversation" => $conversation->id(), "expediteur" => $expediteur->id(), "destinataire" => $joueur->id(), "contenu" => $contenu));
						$messages_manager -> add($message);
					}
					$conversation->setDate_dernier_msg($message->date_envoi());
					$conversations_manager->update($conversation);
				} */
			}
			if($joueur->niveau() > $playerFormerLevel && $joueur->niveau() == 10 && $joueur->classe() != "Prof"){
				if(!$joueur->sameEmail()){
					$joueur->send_email("125840", "Jérémie", "Une belle progression", $joueur->email(), $params = '{ "Prenom": "'.$joueur->firstname().'" }');
				} /*
				$expediteur = $manager->get(47);
				if($joueur->id() != $expediteur->id()){
					$contenu = $joueur->msgProgressPlayer();
					if($conversations_manager->conversation_existante($expediteur->id(), $joueur->id())){ //Si il existe déjà une conversation entre ces joueurs
						$conversation = $conversations_manager->get($expediteur->id(), $joueur->id());
						$message = new Message(array("id_conversation" => $conversation->id(), "expediteur" => $expediteur->id(), "destinataire" => $joueur->id(), "contenu" => $contenu));
						$messages_manager -> add($message);
					} else { //Sinon on créer une nouvelle conversation
						$conversation = new Conversation(array("joueur1" => $expediteur->id(), "joueur2" => $joueur->id()));
						$conversations_manager -> add($conversation);
						$message = new Message(array("id_conversation" => $conversation->id(), "expediteur" => $expediteur->id(), "destinataire" => $joueur->id(), "contenu" => $contenu));
						$messages_manager -> add($message);
					}
					$conversation->setDate_dernier_msg($message->date_envoi());
					$conversations_manager->update($conversation);
				} */
			}
      $wrong_answers = 10 - (int)$_POST["score"];
		}

    //Save the score in DB
    $newScore = new Score(array(
      "element" => $challenge->getElement(),
      "challenge" => $challenge->getName(),
      "id_joueur" => $joueur->id(),
      "good_answers" => (int)$_POST["score"],
      "wrong_answers" => $wrong_answers,
      "initial_level" => $formerLevel,
      "end_level" => $newLevel,
      "train" => 0,
      "diagnosis" => $diagnosis,
	  "player_lvl" => $joueur->niveau(),
	  "player_xp_percent" => round($joueur->xp()/$joueur->xp_requise()*100),
	  "player_rank" => $manager->current_level_ranking($joueur),
    ));
    $scores_manager->add($newScore);

    //Save challenge and player
    $manager->update($joueur);
    $challenges_manager->save_or_update($challenge);
		include_once("challengesMonstersGeneration.php");
  }
  else
  {
    //Determine if it was a diagnosis
    if($challenge->getTries() == 1) {
      $diagnosis = 1;
    }
    $regularPyrs = $joueur->displayPyrs($regularPyrs, $challenge->getElement());
  }

  $levelUp = "no";
  if($joueur->niveau() > $playerFormerLevel){
	  $levelUp = "yes";
	  $joueur->update_portrait();
		// ***********************************************
    // ***** ACHIEVEMENTS
    if ($joueur->niveau() == 10) $achievement_manager->add( $joueur->id(), 32 );
    if ($joueur->niveau() == 20) $achievement_manager->add( $joueur->id(), 33 );
    if ($joueur->niveau() == 30) $achievement_manager->add( $joueur->id(), 34 );
    if ($joueur->niveau() == 40) $achievement_manager->add( $joueur->id(), 35 );
    if ($joueur->niveau() == 50) $achievement_manager->add( $joueur->id(), 36 );
  }
  if ($newLevel == 6) {
		// ***********************************************
    // ***** ACHIEVEMENTS FOR DEFIS ULTIMES
    $achiv_chall_num = $achievement_manager->set_achievement_for_ultimate_challenge( $joueur->id(), $challenge->getName() );
  }

	include_once("challengesMonstersGeneration.php");
  $nb_defis_joueur = $challenges_manager->get_all_stock($joueur); //On recalcule le nombre de défis du joueur pour pouvoir l'afficher sur la vue
  $msg_tuteur = $joueur->msg_tuteur("fin_defi", $nb_defis_joueur, $scores_manager->has_scored_today($joueur), ""); //On détermine le message dans la bulle du tuteur

  include_once("header.php");
  include_once("fin_defi_view.php");
  include_once("footer_view.php");
}
