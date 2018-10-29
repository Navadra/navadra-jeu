<?php
require("include_path.php");
require("controleur_global.php");

if($joueur->classe() == "Prof"){
	$classrooms = $classrooms_manager->getClassroomsTeacher($joueur);
	$challenges = $challenges_manager->get_challenges_by_element($joueur);
	$challengesFire = $challenges[0];
	$challengesWater = $challenges[1];
	$challengesWind = $challenges[2];
	$challengesEarth = $challenges[3];
	$challenges = array_merge($challengesFire, $challengesWater, $challengesWind, $challengesEarth);
	$students = $classrooms_manager->classroomStudents($classrooms[0]);
	if(is_array($classrooms) && count($classrooms) > 0){
		$challengesProgress = $classrooms_manager->classroomProgressByChallenge($classrooms[0]);
	}


	include_once("header.php");
	include_once("students_progress_view.php");
} else {
	echo "Tu n'es pas un Professeur !<br>";
}
