<?php
require("include_path.php");
require("controleur_global.php");

//Si l'utilisateur a soumis une réponse
if(isset($_POST["pseudo"]))
{
	if(!isset($_POST["sexe"])){$_POST["sexe"] = "";}
	if(!isset($_POST["classe"])){$_POST["classe"] = "";}
	$recherche = array(
		'pseudo' => $_POST["pseudo"], 
		'nom' => $_POST["nom"], 
		'prenom' => $_POST["prenom"], 
		'sexe' => $_POST["sexe"], 
		'classe' => $_POST["classe"],
		'departement' => $_POST["departement"],
		'college' => $_POST["college"]
		);
	$joueurs = $manager->recherche($recherche);
}
else
{
	$_POST["pseudo"] = "";
	$_POST["nom"] = "";
	$_POST["prenom"] = "";
	$_POST["classe"] = "";
	$_POST["sexe"] = "";
	$_POST["departement"] = "";
	$_POST["college"] = "";
}
include_once("header.php");
include_once("recherche_view.php");
include_once("footer_view.php");