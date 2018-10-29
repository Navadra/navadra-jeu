<?php
require("include_path.php");
require("controleur_global.php");

/*
$liste_invitations = $combats_manager->liste_invitations($joueur); //On récupère toutes les invitations en cours du joueur
$invitations_prioritaires = array();
$reste_invitations = array();
foreach($liste_invitations as $invit)
{
	if( $joueur->id() == $invit->prochain_a_jouer() && $invit->tous_prets()) //Si le joueur est le prochain à jouer et que tout le monde est prêt
	{
		$date = $invit->derniere_invit_format_time();
		$invit->setPrioritaire("oui");
		$invitations_prioritaires[$date] = $invit;
	}
	elseif(!in_array($joueur->id(), $invit->id_prets(), true)) //Si le joueur est invité mais qu'il n'a pas encore répondu s'il était prêt
	{
		$date_invitations = $invit->date_invitations();
		$date = strtotime($date_invitations[$joueur->id()]);
		$invit->setPrioritaire("oui");
		$invitations_prioritaires[$date] = $invit;
	}
	else //Sinon on les range dans le reste des invitations à trier par date d'invitation décroissante
	{
		if($invit->id_orga() == $joueur->id())
		{
			$date = $invit->derniere_invit_format_time();
			$invit->setPrioritaire("non");
		}
		else
		{
			$date_invitations = $invit->date_invitations();
			$date = strtotime($date_invitations[$joueur->id()]);
			$invit->setPrioritaire("non");
		}
		$reste_invitations[$date] = $invit;
	}
}
krsort($invitations_prioritaires); //On trie les invitations prioritaires par date décroissante
krsort($reste_invitations); //On trie le reste des invitations par date décroissante
$liste_invitations = array_merge($invitations_prioritaires, $reste_invitations);
*/

//Suppression des combats de plus de 30 jours
//$combats_manager->delete_vieux_combats();

$liste_combats_passes = $combats_manager->liste_combats_passes($joueur); //On récupère tous les combats passés du joueur
foreach($liste_combats_passes as $combat)
{
	if($combat->vu()=="" || !in_array($joueur->id(), $combat->vu()))
	{
		$combat->setPrioritaire("oui");
		$combat->combat_vu($joueur); //After putting emphasis on fight not seen, we consider them as viewed
		$clone_combat = clone $combat;
		$combats_manager->update($clone_combat);
	}
	else
	{
		$combat->setPrioritaire("non");
	}
}







include_once("header.php");
include_once("liste_combats_view.php");
include_once("footer_view.php");
