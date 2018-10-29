<?php
require("include_path.php");
require("controleur_global.php");

$contacts = $manager->liste_contacts($joueur);

if(isset($_GET["id"]) && !$combats_manager->exists($joueur->id(), (int)$_GET["id"])) //On vérifie que le joueur ne s'amuse pas à trafiquer le paramètre $_GET
{
	header('Location:../../index.php');
}
else
{
	if(isset($_GET["id"]))
	{
		$id_monstre = (int) $_GET["id"];
		$combat = $combats_manager->get($joueur->id(), $id_monstre);
	}


include_once("header.php");
include_once("contacts_view.php");
include_once("footer_view.php");
}

