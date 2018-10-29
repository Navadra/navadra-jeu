<?php
require("include_path.php");
require("controleur_global.php");

if($joueur->classe() == "Prof"){
	$timeslots = $timeslots_manager->getTeacherNotPast($joueur);
	$classrooms = $classrooms_manager->getClassroomsTeacher($joueur);
	$challenges = $challenges_manager->get_challenges_by_element($joueur);
	$challengesFire = $challenges[0];
	$challengesWater = $challenges[1];
	$challengesWind = $challenges[2];
	$challengesEarth = $challenges[3];
	include_once("header.php");
	include_once("manage_timeslots_view.php");
} else {
	echo "Tu n'es pas un Professeur !<br>";
}
