<?php
require("include_path.php");

$joueur = $_SESSION["joueur"];
if (!isset( $joueur )) {
	header("Location: https://jeu.navadra.com");
	exit;
}

$manager = new JoueursManager($db_RO, $db_RW);
$joueur = $manager->get($joueur->id()); //Permet de prendre en comptes les actions des autres joueurs
$challenges_manager = new ChallengesManager($db_RO, $db_RW);
$sorts_manager = new SortsManager($db_RO, $db_RW);
$monstres_manager = new MonstresManager($db_RO, $db_RW);
$combats_manager = new CombatsManager($db_RO, $db_RW);
$conversations_manager = new ConversationsManager($db_RO, $db_RW);
$messages_manager = new MessagesManager($db_RO, $db_RW);

//Si le joueur tente de quitter un combat en cours, considère le combat comme perdu
	if(isset($_SESSION["combat_en_cours"]))
	{
		$combat = $combats_manager->get_id((int)$_SESSION["combat_en_cours"]);
		if($joueur->id() == $combat->prochain_a_jouer($joueur)) //Si le joueur est toujours le prochain à jouer sur ce combat
		{
			$deroulement = $combat->deroulement();
			if($combat->nb_chasseurs_ko() == $combat->nb_chasseurs() - 1) //Si le joueur était le dernier à jouer
			{
				$deroulement .= "defaite, , , ";
				$combat->setIssue("defaite");
				setlocale(LC_TIME, 'fr_FR');
				date_default_timezone_set('Europe/Paris');
				$date = strftime('%Y-%m-%d %H:%M:%S',time());
				$combat->setDate_combat($date);
				$monstre = $combat->copie_monstre();
				$combat->setPrestige($monstre->perte_prestige($combat->nb_chasseurs()));
				foreach($combat->ordre() as $id_chasseur) { //On met à jour le prestige des chasseurs
					if($joueur->id() != $id_chasseur)	{
						$chasseur = $manager->get($id_chasseur);
						$chasseur->setPrestige($chasseur->prestige() + $combat->prestige());
						$manager->update($chasseur);
					}
					else
					{
						$joueur->setPrestige($joueur->prestige() + $combat->prestige());
						$manager->update($joueur);
					}
				}
			}
			else //Si le joueur n'était pas le dernier à jouer
			{
				$deroulement .= "changer_joueur, , , ;";
			}
			$combat->setDeroulement($deroulement);
			$combat->setVu("");
			$combats_manager->update($combat);
		}
		unset($_SESSION["combat_en_cours"]);
	}

$joueur->setConnecte("non");
$manager->update($joueur);
unset($_SESSION['joueur']);

session_destroy();

header('Location:../../index.php');

exit;
