<?php
require("include_path.php");
require("controleur_global.php");

//If player still in the tutorial
//$finished_fights = $combats_manager->count_finished($joueur); => allow to prevent page actualization but can induce bugs
if ($joueur->tuto() != "fini" && ($joueur->tuto() == "combattre_2" || $joueur->tuto() == "combattre_11") ) {
	$joueur->setNb_combats(1);
	$manager->update($joueur);
	$nb_monstres_joueur = $monstres_manager->nb_monstres_solo($joueur); //We determine the number of monsters of the player
  if ($nb_monstres_joueur == 0)	{
    $monstre = new Monstre(array("nb_chasseurs" => 1)); //We create a monster solo if the player does not have it already
	  if($joueur->tuto() == "combattre_2") {
      	$monstres_manager->add_premiers_monstres($monstre, $joueur, $joueur->element(), 1);
	  } else {
		$monstres_manager->add_premiers_monstres($monstre, $joueur, $joueur->element_force(), 1);
	  }
  }	else {
    $monstres = $monstres_manager->get($joueur);
    $monstre = $monstres[0];
  }
	//Reinitialize the fight
	if($combats_manager->exists($joueur->id(), $monstre->id()))	{
		$combat = $combats_manager->get($joueur->id(), $monstre->id());
		$combat->setDeroulement('');
		$combat->setIssue('');
		$combat->setPrestige(0);
		$combats_manager->update($combat);
	}
	if ($joueur->tuto() == "combattre_2")	{
		$_GET["s"] = 0;
	}
	else if ($joueur->tuto() == "combattre_11")	{
		$spellsTutorial =$sorts_manager->get($joueur, true);
		$spellTuto = $spellsTutorial[0];
		$_GET["s"] = $spellTuto->num();
	}
}
//Si l'URL est bien valide
//if( isset($combat) || (isset($_GET["id"]) && $combats_manager->combat_valide($joueur, (int)$_GET["id"])) ){
if( (isset($_GET["id"]) && $combats_manager->combat_valide($joueur, (int)$_GET["id"])) ){
//  if(!isset($combat))  {
  	$combat = $combats_manager->get_id((int)$_GET["id"]);
//  }
  $_SESSION["combat_en_cours"] = $combat->id();

  //Si le combat ne s'est pas encore achevé et que le joueur est le prochain à jouer
  if ($combat->issue() == "" && $joueur->id() == $combat->prochain_a_jouer())
  {
    $spells_req = $_GET["s"];
    //error_log("Spells : " . $_GET["s"]);
    $sorts = array();
    if ($spells_req > 0)
	{
      $spells_s = explode(",", $_GET["s"]);
      if (is_array($spells_s) == false || count($spells_s) < 1) {
        // If player has no spell, get default ones
        //error_log("Player has no spells");
        $sorts = $sorts_manager->get_best_spells($joueur, $monstre, $spells_by_elem);
      } else {
        //error_log("Player has spells");
        // Must get spells details as it's still a list of numbers
        $sorts = $sorts_manager->recuperer_sorts($joueur, $spells_s);
      }
      //error_log("Count spells : " . count($sorts));

      // We get all critical spells : to add them hidden inside page
      $critical_spells = $sorts_manager->recuperer_sorts($joueur, array(7,14,21,28));

      //On récupère les noms des sorts du joueur
      // Kloug $sorts = $sorts_manager->get_ordre_croissant($chasseur);
      $nom_sorts = array();
      foreach ($sorts as $sort) {
        $nom_sorts[] = $sort->nom();
      }
    }

    //On récupère tous les participants au combat dans l'ordre
    $chasseurs = array();
    $id_chasseurs = $combat->ordre();
    foreach ($id_chasseurs as $id_chass) {
      if ($id_chass != $joueur->id()) {
        $chasseurs[] = $manager->get($id_chass);
      }
    }
    $chasseur = $joueur;

    //On récupère le monstre mais on ne le supprime pas
    $monstre = $monstres_manager->get_id($combat->id_monstre());
    $combat->setCopie_monstre($monstre); //On effectue la copie du monstre


    //On détermine si le joueur est le dernier à jouer ou pas
    if ($combat->nb_chasseurs_ko() == $combat->nb_chasseurs() - 1) {
      $dernier_joueur = "oui";
    } else {
      $dernier_joueur = "non";
    }

  }
  else  {
    $redirect_fight = true;
		header('Location:../../index.php');
  }
  if(!isset($redirect_fight))
  {
	  //Dans tous les cas (que le combat soit passé ou pas)
	  //On initie le numéro du tour à 1
	  $tour = 1;

	  //On récupère les PM du joueur et du monstre et on les ajuste en fonction des affinités élémentaires
	  //  $pm_joueur = round($chasseur->facteur_elem_atq($monstre->element()) / 100 * $chasseur->pm());
	  //  $pm_monstre = round($chasseur->facteur_elem_def($monstre->element()) / 100 * $monstre->pm());
	  //On récupère les PM du joueur et du monstre et on ne les ajuste pas en fonction des affinités élémentaires
	  $pm_joueur = $chasseur->pm();
	  $pm_monstre = $monstre->pm();
	  $endu_joueur = $chasseur->endu();
	  $endu_monstre = $monstre->endu();

	  if ($joueur->tuto() == "combattre_2")
	  {
		$pm_monstre = 1; //Fix because the player has only a weak attack
		$endu_monstre = round(0.5 * $endu_monstre); //Fix because the player has only a weak attack
		$combat->setEndu_monstre_restante($endu_monstre);
	  }
	  elseif ($joueur->tuto() == "combattre_11")
	  {
		$pm_monstre = round(0.2*$pm_monstre); //Fix to ensure player has the possibility to use his ultimate
		$combat->setEndu_monstre_restante(1.2*$endu_monstre);
	  }
	  elseif ($joueur->tuto() == "fini" && $joueur->nb_jours_fin_tuto() == 0 && $joueur->nb_combats() == 4) //Si le joueur a fini son tuto et qu'il l'a fini aujourd'hui
	  {
		$endu_monstre = round(0.8 * $endu_monstre); //Fix because the player does not necessarily have 3 spells
		$pm_monstre = round(0.5*$pm_monstre); //Fix to ensure player has the possibility to use his ultimate
		$combat->setEndu_monstre_restante($endu_monstre);
	  }

	  $combats_manager->update($combat);

	  $profil_elem_joueur = $chasseur->profil_elem_decompose();
	  $profil_elem_monstre = $monstre->element() . ",100";

	  //On détermine la taille du joueur
	  switch ($monstre->nb_chasseurs())
	  {
		case 1 :
		  $hauteur_joueur = "h60";
		  break;
		case 2.5 :
		  $hauteur_joueur = "h50";
		  break;
		case 4.5 :
		  $hauteur_joueur = "h40";
		  break;
		case 8 :
		  $hauteur_joueur = "h30";
		  break;
	  }

	  $profil_elem_joueur = $chasseur->profil_elem_decompose();
	  $profil_elem_monstre = $monstre->element() . ",100";
	  //Détermination du message du tuteur
	  $msg_tuteur = $joueur->msg_tuteur("combattre", $combat->nb_chasseurs(), "", ""); //On détermine le message dans la bulle du tuteur
	  $cache = "";

	  if (isset($_SESSION["deroulement_combat"]))
	  {
		unset($_SESSION["deroulement_combat"]);
		unset($_SESSION["endu_monstre"]);
	  }

	  if ($combat->issue() == "")
	  {
		include_once("header.php");
		include_once("combattre_view.php");
	  }
	  else
	  {
		include_once("header.php");
		include_once("combat_passe_view.php");
	  }
  }
}
else {
	header('Location:../../index.php');
}
