<?php

require("include_path.php");
require("controleur_global.php");

if($challenges_manager->get_assigned($joueur) > 0)
{
	$challenge = $challenges_manager->get_next_challenge($joueur);
}
else
{
	header('Location:/index.php');
}

if(isset($challenge))
{
	$msg_tuteur = $joueur->msg_tuteur("accueil_defi", "", "", ""); //On détermine le message dans la bulle du tuteur		
	$new_mentor = $challenges_manager->new_mentor($joueur); //For tutorial
	if(!isset($_SESSION["training"]) || $joueur->tuto() == "fini")
	{
		$mentor = $joueur->portrait_tuteur();
	}
	else
	{
		$mentor = "/webroot/img/personnages/".strtolower($challenges_manager->new_mentor($joueur))."_portrait.png"; //For tutorial
	}
	include_once("header.php");
	include_once("accueil_defi_view.php");
	include_once("footer_view.php");
}