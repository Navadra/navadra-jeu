<?php

require("include_path.php");
require("controleur_global.php");

if(isset($_GET["id"]) && $challenges_manager->validId((int)$_GET["id"], $joueur)){
	$challenge = $challenges_manager->get_by_id((int)$_GET["id"]);
	$return_page = "/app/controllers/profil.php?id=".$joueur->id()."&tab=challenges";
} else {
	$challenge = $challenges_manager->get_next_challenge($joueur); //On récupère le défi avec le plus petit palier en stock
	$return_page = "/app/controllers/accueil_defi.php";
}

if(isset($challenge)) 
{
  	if($joueur->tuto() != "fini")
	{
		$_SESSION["training"] = "yes";
		$mentor = "/webroot/img/personnages/".strtolower($challenges_manager->new_mentor($joueur))."_portrait.png"; //For tutorial
	}
	else
	{
		$mentor = $joueur->portrait_tuteur();
	}
	include_once("header.php");
  	include_once("entrainement_view.php");
} 
else 
{
  header('Location:/index.php');
}