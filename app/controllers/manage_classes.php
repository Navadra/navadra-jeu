<?php
require("include_path.php");
require("controleur_global.php");

if($joueur->classe() == "Prof"){
	$classrooms = $classrooms_manager->getClassroomsTeacher($joueur);
	include_once("header.php");
	include_once("manage_classes_view.php");
} else {
	echo "Tu n'es pas un Professeur !<br>";
}
