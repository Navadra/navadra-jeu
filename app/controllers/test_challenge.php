<?php

require("include_path.php");
require("controleur_global.php");

if( isset($_SERVER['SERVER_ADDR'])) $server_add = $_SERVER['SERVER_ADDR'];
if( isset($_SERVER['SERVER_NAME'])) $server = $_SERVER['SERVER_NAME'];

if($joueur->admin() || $server == "localhost" || $server_add == "localhost" || $server == "127.0.0.1" || $server_add == "127.0.0.1"){
	$challenge = $challenges_manager->get_any_challenge($joueur); //On récupère le défi avec le plus petit palier en stock
} else {
	echo "Tu n'as pas les droits d'Admin !<br>";
}

include_once("header.php");
include_once("test_challenge_view.php");
