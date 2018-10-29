<?php
require("include_path.php");
require("controleur_global.php");

if(isset($_GET["id"]) && $manager->exists((int) $_GET["id"]))
{
$joueur_profil =  $manager->get((int) $_GET["id"]);
if($joueur_profil->sexe() == "gars"){
	$ep = "";
} else {
	$ep = "e";
}
$last_fights = $combats_manager->last_fights($joueur_profil);
$teamates = $combats_manager->best_teamates($joueur_profil);

$pourcent_xp_profil = round($joueur_profil->xp()/$joueur_profil->xp_requise() * 100); //Pour la barre d'XP du joueur dont on consulte le profil

//Récupère le profil du joueur ciblé par élément décroissant
$profil_elem = $joueur_profil->profil_elem_classe();
$elems = array_keys($profil_elem);
$elem1 = $elems[0].",".$profil_elem[$elems[0]];
$elem2 = $elems[1].",".$profil_elem[$elems[1]];
$elem3 = $elems[2].",".$profil_elem[$elems[2]];
$elem4 = $elems[3].",".$profil_elem[$elems[3]];

//Détermine les trophés gagnés
$trophees = $saisons_manager->recompenses_joueur($joueur_profil, $joueur);
if($trophees == "Aucune saison"){unset($trophees);}

//Get all player's titles
$titles = $titles_manager->get_all($joueur_profil);
if($titles){
	$playerTitle = $titles[0];
}

// Get trophées
$trophy_count = $achievement_manager->count_all();
$trophy_player = $achievement_manager->get_all($joueur_profil);
if($joueur->id() == $joueur_profil->id()){
	$trophy_title = "Tu possèdes <strong>".sizeof($trophy_player)."</strong> trophées sur <strong>".$trophy_count."</strong>";
} else {
	$trophy_title = $joueur_profil->pseudo()." possède <strong>".sizeof($trophy_player)."</strong> trophées sur <strong>".$trophy_count."</strong>";
}
//Get all challenges
$challenges = $challenges_manager->get_all_challenges($joueur_profil);
foreach($challenges as $challenge){
	$last_score = $scores_manager->get_last_score_challenge($challenge, $joueur);
	if($last_score == null){
		$challenge->setTime_remaining(0);
	} else {
		$date = strtotime(preg_replace('#\d+:\d+:\d+#','00:00:00',$last_score->getDate_score())); //We consider the last score was made at midnight of the same day
		$date_diff = time() - $date;
		if($date_diff > 24*60*60){
			$challenge->setTime_remaining(0);
		} else {
			$date_diff = 24*60*60 - $date_diff;
			$hours = floor($date_diff/60/60);
			if($hours < 10){
				$hours = "0".$hours;
			}
			$date_diff -= $hours*60*60;
			$minutes = floor($date_diff/60);
			if($minutes < 10){
				$minutes = "0".$minutes;
			}
			$challenge->setTime_remaining("".$hours."h".$minutes."min");
		}
	}
}

//For the sponsor of the player
$invitee_code = $codes_manager->get_invited($joueur_profil->id());
$sponsor_title = "Parrain";
$sponsored = "Joueurs parrainés";
if($joueur->admin() && $joueur->id() == $joueur_profil->id()){
	$sponsor_title = "Créer un code ";
	$sponsored = "Code";
}
elseif(is_string($invitee_code)){
	$player_sponsor = $invitee_code;
} else {
	$player_sponsor = $manager->get($invitee_code->id_sponsor());
	if($player_sponsor->admin()){
		$player_sponsor = "Equipe Navadra";
	} else if($player_sponsor->sexe() == "fille"){
		$sponsor_title = "Marraine";
	}
}
//For other players invited by the player
$sponsor_code = $codes_manager->get_sponsor($joueur_profil->id());
$player_invitees = array();
if($sponsor_code->id_invited()){
	foreach($sponsor_code->id_invited() as $id_invitee){
		if(sizeof($player_invitees) < 3){
			$player_invitees[] = $manager->get($id_invitee);
		}
	}
}
if($joueur->admin() && $joueur->id() == $joueur_profil->id()){
	$invitees_sentence = "Fais pleuvoir les aventuriers sur Navadra !";
	$color_sentence = "jaune";
} else {
	$invitees_left = 3 - sizeof($player_invitees);
	if($invitees_left >= 1 && $joueur->id() == $joueur_profil->id()){
		$invitees_sentence = "Invite 3 amis pour découvrir un monstre légendaire !";
		$color_sentence = "vert";
	} elseif($invitees_left >= 1){
		$invitees_sentence = $joueur_profil->pseudo()." doit inviter 3 amis pour découvrir un monstre légendaire !";
		$color_sentence = "vert";
	} elseif($joueur->id() == $joueur_profil->id() && $codes_manager->rewardObtained($joueur) == 0){
		$invitees_sentence = "Tu peux maintenant découvrir un monstre légendaire !";
		$color_sentence = "jaune";
	} elseif($joueur->id() == $joueur_profil->id() && $codes_manager->rewardObtained($joueur) == 1){
		$invitees_sentence = "Tu as déjà découvert un monstre légendaire !";
		$color_sentence = "jaune";
	} elseif($codes_manager->rewardObtained($joueur_profil) == 0){
		$invitees_sentence = $joueur_profil->pseudo()." peut maintenant découvrir un monstre légendaire !";
		$color_sentence = "jaune";
	} elseif($codes_manager->rewardObtained($joueur_profil) == 1){
		$invitees_sentence = $joueur_profil->pseudo()." a déjà découvert un monstre légendaire !";
		$color_sentence = "jaune";
	}
}
$modeAdmin = false;
$modeTeacher = false;
$modeStudent = false;
if($joueur->admin() && $joueur->id() == $joueur_profil->id()){
	$modeAdmin = true;
}
if($joueur->classe() == "Prof" && $joueur->id() == $joueur_profil->id()){
	$modeTeacher = true;
} elseif($joueur->id() == $joueur_profil->id()) {
	$modeStudent = true;
	$classroom = $classrooms_manager->getClassroomStudent($joueur);
}

include_once("header.php");
include_once("profil_view.php");
include_once("footer_view.php");

}
else
{
header('Location:../../index.php');
}
