<?php
require("include_path.php");
require("controleur_global.php");

$contacts = $manager->liste_contacts($joueur);

//Suppression des messages de plus de 60 jours
//$messages_manager->delete_vieux_msgs();

//Si le joueur a soumis un message création d'un nouveau msg
if(isset($_POST["message"]) && strlen($_POST["message"]) > 1 && strlen($_POST["message"]) < 850)
{
	$_POST["id_destinataire"] = (int) $_POST["id_destinataire"];
	//Si il existe déjà une conversation existante entre ces joueurs 
	if($conversations_manager->conversation_existante($joueur->id(), $_POST["id_destinataire"]))
	{
		$conversation = $conversations_manager->get($joueur->id(), $_POST["id_destinataire"]);
		$message = new Message(array("id_conversation" => $conversation->id(), "expediteur" => $joueur->id(), "destinataire" => $_POST["id_destinataire"], "contenu" => $_POST["message"]));
		$messages_manager -> add($message);
	}
	//Sinon on créer une nouvelle conversation
	else
	{
		$conversation = new Conversation(array("joueur1" => $joueur->id(), "joueur2" => $_POST["id_destinataire"]));
		$conversations_manager -> add($conversation);
		$message = new Message(array("id_conversation" => $conversation->id(), "expediteur" => $joueur->id(), "destinataire" => $_POST["id_destinataire"], "contenu" => $_POST["message"]));
		$messages_manager -> add($message);
	}
	$conversation->setDate_dernier_msg($message->date_envoi());
	$conversations_manager->update($conversation);
}

//Retrouve toutes les conversations du joueur, détermine s'il a au moins un message non lu dans cette conversation et récupère les données à afficher en html
$conversations = $conversations_manager->get_all($joueur);
foreach($conversations as $conversation)
{
	if($messages_manager->nbre_non_lu($conversation, $joueur) != 0)
	{
		$conversation->setNouveau_msg("oui");
	}
	else
	{
		$conversation->setNouveau_msg("non");
	}
	$messages = $messages_manager->get_all($conversation);
	$conversation->setAffichage_html("");
	foreach($messages as $msg)
	{
		$expediteur = $manager->get($msg->expediteur());
		$expediteur = "<span class='ib md2 mg2'>".$expediteur->pseudo()."</span>";
		$tiret= "<span class='ib p0 md2'>-</span>";
		$date_envoi = "<span class='ib p0'>".$msg->date_envoi_format_fr()."</span>";
		$contenu_msg = str_replace('\n', '<br />', nl2br($msg->contenu()));
		$contenu = "<span class='ib mh1 mb4 l98 mg2 p0'>".$contenu_msg."</span>";
		$conversation->setAffichage_html($conversation->affichage_html().$expediteur.$tiret.$date_envoi.$contenu);
	}
	//Stocke la conservation si elle correspond au joueur demandé (en ayant cliqué sur le bouton message de profil.php)
	if(isset($_GET["id"]) && ( (int) $_GET["id"] == $conversation->joueur1() || (int) $_GET["id"] == $conversation->joueur2()))
	{
		$contenu_initial = $conversation->affichage_html();
		$destinataire = $manager->get($conversation->determiner_correspondant($joueur)); //Détermine le joueur avec qui le joueur connecté dialogue
		$id_destinataire_initial = $destinataire->id();
		$titre = "Conversation avec ".$destinataire->pseudo();
	}
}

if(isset($_GET["id"]) && !$conversations_manager->conversation_existante($joueur->id(), (int) $_GET["id"])) //Si le joueur vient de profil.php et qu'il n'existe pas encore de conversation entre eux
{
	$contenu_initial = "";
	$destinataire = $manager->get((int) $_GET["id"]);
	$id_destinataire_initial = $destinataire->id();
	$titre = "Conversation avec ".$destinataire->pseudo();
}
elseif(isset($_POST["message"]) && strlen($_POST["message"]) > 1 && strlen($_POST["message"]) < 850) //Si le joueur vient de poster un message
{
	$derniere_convers = $conversations[0];
	$destinataire = $manager->get((int) $_POST["id_destinataire"]);
	$contenu_initial = $derniere_convers->affichage_html();
	$id_destinataire_initial = $destinataire->id();
	$titre = "Conversation avec ".$destinataire->pseudo();
}
elseif($conversations && !isset($_GET["id"])) //S'il y a au moins une conversation et que le joueur ne vient pas de profil.php
{
	$contenu_initial = "";
	$id_destinataire_initial = 0;
	$titre = "Sélectionne une conversation";
}
elseif(!isset($_GET["id"])) //S'il n'y a aucune conversation et que le joueur ne vient pas de profil.php
{
	$contenu_initial = "";
	$id_destinataire_initial = 0;
	$titre = "Aucune conversation en cours";
}

$liste_id_correspondants = ""; //Initie la variable qui stocke tous les id des correspondants du joueur


include_once("header.php");
include_once("messages_view.php");
include_once("footer_view.php");
