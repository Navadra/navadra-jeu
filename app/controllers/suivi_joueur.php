<?php
require("include_path.php");

if(isset($_SESSION["joueur"])){
	$player = $_SESSION["joueur"];
}

if(isset($_GET["link"])){
	$player = $manager->get_by_suivi_link( $_GET["link"] );
} elseif(isset($joueur) && $joueur->admin() && isset($_GET["pseudo"])){
	$player = $manager->get( $_GET["pseudo"] );
}

if(isset($player)){

	if($player->sexe() == "gars"){
		$child = "fils";
		$e = "";
	} else {
		$child = "fille";
		$e = "e"; //Terminaison féminine pour les conjugaisons
	}

	$firstScore = $scores_manager->get_premier_score($player);
	$challenge = new Challenge(array());
	$facticePlayer = new Joueur(array(
		"niveau" => 1,
		"xp" => 0,
		"tutoriel" => "fin_defi_5"
	));
	$xpFirstScore = $challenge->regularXP($firstScore->getGood_answers(), $facticePlayer) + $challenge->bonusXP($firstScore->getEnd_level(), $facticePlayer);
	$facticePlayer->gagner_xp($xpFirstScore);

	$initialRanking = $manager->initial_level_ranking($player, $facticePlayer);
	$currentRanking = $manager->current_level_ranking($player);

	$allChallenges = $challenges_manager->get_all_challenges($player);
	$lockedFireChallenges = array();
	$lockedWaterChallenges = array();
	$lockedWindChallenges = array();
	$lockedEarthChallenges = array();
	foreach($allChallenges as $challenge){
		if($challenge->getTries()==0 && $challenge->getElement()=="fire"){
			$lockedFireChallenges[] = $challenge;
		} elseif($challenge->getTries()==0 && $challenge->getElement()=="water"){
			$lockedWaterChallenges[] = $challenge;
		} elseif($challenge->getTries()==0 && $challenge->getElement()=="wind"){
			$lockedWindChallenges[] = $challenge;
		} elseif($challenge->getTries()==0 && $challenge->getElement()=="earth"){
			$lockedEarthChallenges[] = $challenge;
		}
	}

	if ($abonnements_manager->get_last_by_player_id($player->id()) != null) {
	  $existingPayment = 1;
	} else {
	  $existingPayment = 0;
	}

include_once("suivi_joueur_view.php");
} else {
	echo("L'URL renseignée ne correspond à aucun joueur enregistré.");
}
