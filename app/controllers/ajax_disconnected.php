<?php
require("include_path.php");

//Get all the challenges of the player
if(isset($_POST["historicChallenges"])){
  $idPlayer = (int)$_POST["historicChallenges"];
  $player = $manager->get($idPlayer);
  $challenges = $challenges_manager->get_tried($player);
  $challenges_json = array();
  foreach($challenges as $challenge){
    $challenges_json[] = array(
      "element" => $challenge->getElement(),
      "name" => $challenge->getName(),
      "category" => $challenge->title_element(),
      "notion" => $challenge->notion(),
      "tries" => (int)$challenge->getTries(),
      "level" => (int)$challenge->getCurrent_level()
      );
  }
  echo json_encode($challenges_json);
}

//Get all the scores (not training) and the fights of the player
if(isset($_POST["historicPractice"])){
  $idPlayer = (int)$_POST["historicPractice"];
  $player = $manager->get($idPlayer);
  $scores = $scores_manager->get_non_training_scores_player($player);
  $fights = $combats_manager->list_fights_chronogically($player);
  $practice_json = array();
  $dateTemp = "";
  $countTemp = 0;
  foreach($scores as $key=>$score){
    $dateScore = substr($score->getDate_score(),0,10);
    if($dateTemp != "" && $dateScore != $dateTemp){ //New day
      $practice_json[] = array(
        "type" => "challenge",
        "count" => $countTemp,
        "date" => $dateTemp
      );
      $countTemp = 1;
      $dateTemp = $dateScore;
    } else { //Same day
      $countTemp ++;
      $dateTemp = $dateScore;
    }
  }
  $practice_json[] = array(
    "type" => "challenge",
    "count" => $countTemp,
    "date" => $dateTemp
  );
  $dateTemp = "";
  $countTemp = 0;
  foreach($fights as $fight){
    $dateFight = substr($fight->date_combat(),0,10);
    if($dateTemp != "" && $dateFight != $dateTemp){ //New day
      $practice_json[] = array(
        "type" => "fight",
        "count" => $countTemp,
        "date" => $dateTemp
      );
      $countTemp = 1;
      $dateTemp = $dateFight;
    } else { //Same day
      $countTemp ++;
      $dateTemp = $dateFight;
    }
  }
  $practice_json[] = array(
    "type" => "fight",
    "count" => $countTemp,
    "date" => $dateTemp
  );
  echo json_encode($practice_json);
}

//Get all the exercises of the player
if(isset($_POST["historicExercises"])){
  $idPlayer = (int)$_POST["historicExercises"];
  $exercises = $exercises_manager->get_all_exercises_player($idPlayer);
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

//Check whether the given code is valid or not
if(isset($_POST["invitationCode"])){
  $code = $_POST["invitationCode"];
  if(!$codes_manager->exists($code) && !$classrooms_manager->exists($code)){
    echo "unknown";
  } elseif($codes_manager->exists($code)) {
    $requestedCode = $codes_manager->get_code($code);
		$usable = $codes_manager->usable($code);
	} elseif($classrooms_manager->exists($code)) {
		$classroom = $classrooms_manager->getByCode($code);
		$usable = $classroom->notFull();
	}
  if(isset($usable) && $usable == "ok"){
    	echo "valid";
  } elseif(isset($usable)) {
    	echo "overUsed";
  }
}

//find pseurriel and get abonnements info
if ( isset( $_POST["pseurriel"] ) ) {
  $data_json = array();

  $pseurriel = $_POST["pseurriel"];
  if ($pseurriel != null && sizeof($pseurriel) > 0 ) {
    // ID, email, pseudo
    $donnees = $abonnements_manager->get_by_pseurriel( $pseurriel );
    if ($donnees != null && sizeof($donnees) > 0 ) {
      $j_id = $donnees[0];
      $j_email = $donnees[1];

      if ($j_email != null && sizeof($j_email) > 0 ) {
        // Partially obfuscate email
        $index_at = strpos($j_email, "@");
        // Keep first letter
        $start = substr($j_email, 0, 1).'****';
        // Keep lasst letter
        $end = substr($j_email, $index_at - 1, 1);
        // append with last part after '@'
        $j_email = $start.'****'.$end.substr($j_email, $index_at);
        //$j_email = substr($j_email, 0, 2).'****'.substr($j_email, strpos($j_email, "@"));
      }

      $j_pseudo = $donnees[2];

      $abonnement = $abonnements_manager->get_last_by_player_id( $j_id );
      if ($abonnement != null ) {
        $j_formule = $abonnement->getFormule();
        $j_until = $abonnement->getDt_expiration();
        $data_json[] = array(
            "id" => $j_id,
            "email" => $j_email,
            "pseudo" => $j_pseudo,
            "formule" => $j_formule,
            "until" => $j_until
        );
      }
      else {
        $data_json[] = array(
            "id" => $j_id,
            "email" => $j_email,
            "pseudo" => $j_pseudo
        );
      }
    }
  }
  //error_log ( "###################### data_json = ".json_encode($data_json[0]) );
  echo json_encode($data_json[0]);
}

// After payment update players abonnement :)
if ( isset( $_POST["secret"], $_POST["id_player"], $_POST["id_parent_wp"], $_POST["formule"], $_POST["qty"] )  ) {

  //error_log ( " AFTER PAYMENT UPDATE METHOD " );
  $navadra_secret = 'fR56!b32v4dfg*!78_-HY-(6-ERFSDGgdrgdrDG45643U6RSFGHbs(yeqvGERC';
  //$navadra_secret = 'fR56%°!b32v4';

  if ( $navadra_secret != $_POST["secret"] ) {
    echo json_encode("No access");
  }
  else {
    $abonnements_manager->save_or_update( $_POST["id_player"], $_POST["id_parent_wp"], $_POST["formule"], $_POST["qty"] );

    // GET PLAYER
    $player = $manager->get_by_id($_POST["id_player"]);

    // CREATE 2 CHALLENGES
    $player->setStock_challenges($player->stock_challenges() + 2);
    $manager->update($player);

    // CREATE 2 MONSTERS SOLO
    $monstres_manager->nouveau_monstre_solo($player, $player->niveau());
    $monstres_manager->nouveau_monstre_solo($player, $player->niveau());

    // CREATE 1 MONSTERS MULTI
    $monstres_manager->apparition_monstre_multi($player, $player->niveau(), 2.5);


    echo json_encode('OK');
  }
}

// After payment update players abonnement :)
if ( isset($_GET["ultimate_achievements"])) {
  $achievement_manager->leverage_existing_ultimate_challenges();
  echo json_encode("OK");
}

if ( isset( $_POST["ping"] ) ) {

    echo json_encode( 'pong' );
}

if(isset($_GET["confirmEmail"])){
	$player = $manager->get_by_suivi_link( $_GET["confirmEmail"] );
	if(isset($player)){
		$player->setEmail_confirme(1);
		$manager->update($player);

		if(isset($_SESSION['joueur'], $joueur)){
			$joueur->setConnecte("non");
			$manager->update($joueur);
			unset($_SESSION['joueur']);
			session_destroy();
		}

		$player->setConnecte("oui");
		if ($player->abonnement_ok() == 1 && $abonnements_manager->is_valid($player->id()) == false) {
			$player->setAbonnement_ok(0);
		}
		$manager->update($player);
		$_SESSION["joueur"] = $player;
		header('Location:../../index.php');
	} else {
		$success = 0;
		include_once("head_references.php");
		include_once("confirm_email_view.php");
	}
}

if(isset($_GET["confirmEmailParent"])){
	$player = $manager->get_by_suivi_link( $_GET["confirmEmailParent"] );
	if($player != null){
		$player->setEmail_confirme(1);
		$manager->update($player);
		$success = 1;
	} else {
		$success = 0;
	}
	include_once("head_references.php");
	include_once("confirm_email_view.php");
}

//Check whether the given code is valid or not
if(isset($_POST["sendReminder"])){
  $email = $_POST["sendReminder"];
	$fictivePlayer = new Joueur(array(
		"Pseudo" => "test"
	));
  if(!$fictivePlayer->emailValide($email)){
    echo "wrongEmail";
  } else {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$dateCreation = strftime('%Y-%m-%d %H:%M:%S', time());
		$q = $db_RW->prepare('INSERT INTO demo (email, date_inscription) VALUES(:email, :date_inscription)');
		$q->execute(array(
			'email' => $email,
			'date_inscription' => $dateCreation
		));
		$fictivePlayer->send_email("140144", "Navadra", "Essayez Navadra sur ordinateur", $email, $params = '{ "Pseudo": "test" }');
		echo "emailSent";
	}
}
