<?php
require("include_path.php");
require("controleur_global.php");

//Soit le monstre appartient au joueur soit le joueur a été invité à un combat avec ce monstre sinon redirection vers l'accueil
if(isset($_GET["idm"])  &&  ($monstres_manager->monstre_existant_id((int)$_GET["idm"], $joueur) || $combats_manager->exists($joueur->id(), (int)$_GET["idm"])) )
{

//On vérifie si le combat existe, s'il n'existe pas on le créé
if($combats_manager->exists($joueur->id(), (int)$_GET["idm"]))
{
	$combat = $combats_manager->get($joueur->id(), (int)$_GET["idm"]);
}
else
{
	$combat = new Combat(array("id_orga" => $joueur->id(), "id_monstre" => (int)$_GET["idm"]));
	$combats_manager->add($combat);
}

//On récupère le monstre
if($combat->copie_monstre() != "")
{
	$monstre = $combat->copie_monstre();
}
else
{
	$monstre = $monstres_manager->get_id((int)$_GET["idm"]);
}

//On récupère la liste des joueurs qui participe au combat
$combattants = array();
$id_joueurs = $combat->ordre();
if($id_joueurs == "") //Permet de gérer le cas des requêtes ajax qui ne sont pas complétées à cause du serveur de merde
{
	if($combat->id_invites() == "")
	{
		$invites = array();
	}
	else
	{
		$invites = $combat->id_invites();
	}
	$id_joueurs = array_merge(array($combat->id_orga()), $invites);
	$combat->setOrdre($id_joueurs);
	$combats_manager->update($combat);
}

foreach($id_joueurs as $id_joueur)
{
	$combattants[] = $manager->get($id_joueur);
}

	// Count player's spells to know if he has to prepare a deck or go directly to fight
  $count_spells = count($sorts_manager->get($joueur, true));

//Détermination du message du tuteur
$msg_tuteur = $joueur->msg_tuteur("prepa_combats", "", "", ""); //On détermine le message dans la bulle du tuteur

if($joueur->id() == $combat->id_orga() && $monstre->nb_chasseurs() != 1) {
	$contacts = $manager->liste_contacts($joueur);
	$researchPlayers = $manager->potential_partners($joueur);
}

include_once("header.php");
include_once("prepa_combats_view.php");
include_once("footer_view.php");
}
else //Si le joueur tente d'arriver sur la page sans id du monstre ou avec une mauvaise id redirection vers l'index
{
	header('Location:../../index.php');
}
