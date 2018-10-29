<?php
require("include_path.php");
require("controleur_global.php");

if($joueur->admin()){
	include_once("header.php");
	include_once("colleges_view.php");
} else {
	echo "Tu n'as pas les droits d'Admin !<br>";
}
