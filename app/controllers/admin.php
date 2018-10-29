<?php
require("include_path.php");
require("controleur_global.php");

if($joueur->admin()){
	if(isset($_GET["sendMsg"])){
		/*
		$players = $manager->liste_joueurs_classement();
		$expediteur = $manager->get(47);
		foreach($players as $player){
			$contenu = "Salut ".$player->pseudo().",

			Nous avons une bonne nouvelle à t'annoncer : Navadra devient gratuit et sans pub !
			Oui tu as bien entendu : gratuit !

			Pour cette version, nous limitons toujours le nombre de monstres et de défis quotidiens à 3 (5 pour ceux qui avaient un abonnement) car il est plus efficace d'en faire un peu chaque jour que beaucoup en une seule session.

			Bon jeu et à bientôt !

			PS : tu as essayé de cliquer sur l'icône de la carte en haut à gauche quand tu es sur l'île ?";
			if($player->id() != $expediteur->id()){
				if($conversations_manager->conversation_existante($expediteur->id(), $player->id())){ //Si il existe déjà une conversation entre ces joueurs
					$conversation = $conversations_manager->get($expediteur->id(), $player->id());
					$message = new Message(array("id_conversation" => $conversation->id(), "expediteur" => $expediteur->id(), "destinataire" => $player->id(), "contenu" => $contenu));
					$messages_manager -> add($message);
				} else { //Sinon on créer une nouvelle conversation
					$conversation = new Conversation(array("joueur1" => $expediteur->id(), "joueur2" => $player->id()));
					$conversations_manager -> add($conversation);
					$message = new Message(array("id_conversation" => $conversation->id(), "expediteur" => $expediteur->id(), "destinataire" => $player->id(), "contenu" => $contenu));
					$messages_manager -> add($message);
				}
				$conversation->setDate_dernier_msg($message->date_envoi());
				$conversations_manager->update($conversation);
			}
		}*/
	}
	include_once("header.php");
	include_once("admin_view.php");
} else {
	echo "Tu n'as pas les droits d'Admin !<br>";
}
