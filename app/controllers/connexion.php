<?php
require("include_path.php");

if(isset($_POST["pseudo"]))
{
	$ok = 0;
	if(!$manager->exists($_POST["pseudo"])) //Si le pseudo ou l'email n'existe pas dans la BDD
	{
		$ok ++;
		$msg = "Le pseudo ou l'email n'existe pas dans notre base !";
	}
	elseif(!$manager->loginPseudo(array($_POST["pseudo"], $_POST["mdp"]))) //Si la combinaison pseudo / mdp n'existe pas dans la BDD
	{
		if(!$manager->loginEmail(array($_POST["pseudo"], $_POST["mdp"]))) //Si la combinaison email / mdp n'existe pas dans la BDD
		{
			$ok ++;
			$msg = "Cette combinaison n'existe pas dans notre base !";
		}
	}

	if($ok > 0) //S'il y a au moins 1 erreur
	{
		include_once("header.php");
		include_once("connexion_view.php");
		include_once("footer_deco_view.php");
	}
	else
	{
		$joueur = $manager->get($_POST["pseudo"]);
		$joueur->setConnecte("oui");

		// AUTOREPAIR : SOME STUFFS ********
		// Check bad monsters done on index page
		// $monstres_manager->delete_bad_monsters($joueur);
		// Check expired abonnement (only if player has already one
		if ($joueur->abonnement_ok() == 1 && $abonnements_manager->is_valid($joueur->id()) == false) {
			$joueur->setAbonnement_ok(0);
		}
		$manager->update($joueur);

		$_SESSION["joueur"] = $joueur;
		header('Location:../../index.php');
	}


}
else
{
	if(isset($_GET["reason"]) && $_GET["reason"] == "doesNotExist") {
		$msg = "Ce code de parrainage n'existe pas !";
	} elseif(isset($_GET["reason"]) && $_GET["reason"] == "tooMuchInvitees") {
		$msg = "Désolé, ce code de parrainage ne donne plus le droit à des invitations supplémentaires !";
	}
	$_POST["pseudo"] = "";
	$_POST["mdp"] = "";
	include_once("header.php");
	include_once("connexion_view.php");
	include_once("footer_deco_view.php");
}
