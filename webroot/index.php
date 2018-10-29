<?php

require("app/controllers/include_path.php");

if(isset($_SESSION["joueur"]))   //Si l'utilisateur est connecté
{
  include_once("controleur_global.php");
	if($joueur->tuto() == "fini" && ($joueur->nb_jours_fin_tuto() != 0 || $monstersFoughtToday >= 5)){
		$invitationsList = $combats_manager->liste_invitations($joueur);
	  foreach($invitationsList as $invitation){
	    $monsterFight = $monstres_manager->get_id($invitation->id_monstre());
	    if(!in_array($monsterFight, $monstres)){
	      $monstres[] = $monsterFight;
	    }
	  }
		//Suppression des monstres multijoueurs si leur durée de disponibilité est arrivée à expiration
	  foreach ($monstres as $m) {
	    if ($m->temps_avant_fuite() <= 0) {
	      if ($combats_manager->exists($joueur->id(), $m->id())) {
	        $combat = $combats_manager->get($joueur->id(), $m->id());
	        if($combat->deroulement() == ""){
	          $combats_manager->delete($combat);
	          $m->setDead(1);
	          $monstres_manager->update($m);
	          array_splice($monstres, array_search($m, $monstres), 1);
	        }
	      } else {
	        $m->setDead(1);
	        $monstres_manager->update($m);
	        array_splice($monstres, array_search($m, $monstres), 1);
	      }
	    }
	  }
	}
  if ($joueur->tuto() == "fini" && $joueur->nb_jours_fin_tuto() != 0) //Si le joueur a fini son tuto et qu'il ne l'a pas fini aujourd'hui
  {
		// AUTOREPAIR : Check bad monsters for current player
		$monstres_manager->delete_bad_monsters($joueur);

		$msg_tuteur = $joueur->msg_tuteur("index", $sum_challenges, $scores_manager->has_scored_today($joueur), ""); //On détermine le message dans la bulle du tuteur
    //Si le joueur n'a pas vu le bilan de la dernière saison
    $derniere_saison = $saisons_manager->get_derniere();
    if ($joueur->tuto() == "fini" && in_array($joueur->id(), explode(",", $derniere_saison->classement())) && !in_array($joueur->id(), explode(",", $derniere_saison->vues()))) {
      $podium = $derniere_saison->podium();
      $podium_saison = array();
      foreach ($podium as $id_jou) {
        $jou = $manager->get((int)$id_jou);
        $podium_saison[] = $jou;
      }
      $podium_prestige = $derniere_saison->podium_prestige();
      $info_saison = $joueur->recompense_saison(explode(",", $derniere_saison->classement()), $derniere_saison->classement_joueur($joueur));
      if ($derniere_saison->vues() != "") {
        $derniere_saison->setVues($derniere_saison->vues() . "," . $joueur->id());
      } else {
        $derniere_saison->setVues($joueur->id());
      }
      $saisons_manager->update($derniere_saison);
      $manager->update($joueur);
    }
  }
  elseif ($joueur->tuto() == "index_1")  {
    $nb_monstres_joueur = $monstres_manager->nb_monstres_solo($joueur); //We determine the number of monsters of the player
    if ($nb_monstres_joueur == 0)	{
      $monstre = new Monstre(array("nb_chasseurs" => 1)); //We create a monster solo if the player does not have it already
      $monstres_manager->add_premiers_monstres($monstre, $joueur, $joueur->element(), 1);
			$combat = new Combat(array("id_orga" => $joueur->id(), "id_monstre" => $monstre->id())); //We create related fight
			$combats_manager->add($combat);
    } else {
      $monstres = $monstres_manager->get($joueur);
      $monstre = $monstres[0];
			$combat = $combats_manager->get($joueur->id(), $monstre->id());
    }
  } elseif ($joueur->tuto() == "index_3")  {
		$joueur->setNb_combats(5);
		if($timeSlot == "NoTimeSlot"){
			$joueur->setStock_challenges(1);
		}
		$manager->update($joueur);
  } elseif ($joueur->tuto() == "index_8")  {
    $nb_monstres_joueur = $monstres_manager->nb_monstres_solo($joueur); //We determine the number of monsters of the player
    if ($nb_monstres_joueur == 0)
		{
      $monstre = new Monstre(array("nb_chasseurs" => 1)); //We create a monster solo if the player does not have it already
      $monstres_manager->add_premiers_monstres($monstre, $joueur, $joueur->element_force(), 1);
    }
		else
		{
      $monstres = $monstres_manager->get($joueur);
      $monstre = $monstres[0];
    }
		if($challenges_manager->get_assigned($joueur) > 0)
		{
			$challenge = $challenges_manager->get_next_challenge($joueur); //We get next player challenge
			$challenge->setStock(0);
			$challenges_manager->save_or_update($challenge);
		}
  }
  elseif ($joueur->tuto() == "index_12")
  {
    $fight = $combats_manager->dernier_combat($joueur);
		if($fight->issue() == "victoire")
		{
			$winLastFight = "yes";
		}
		else
		{
			$winLastFight = "no";
		}
	$prestigeLastFight = $fight->prestige();
  }
  elseif($joueur->tuto() == "fini")  {
		$msg_tuteur = $joueur->msg_tuteur("index", $sum_challenges, $scores_manager->has_scored_today($joueur), ""); //On détermine le message dans la bulle du tuteur
		$nb_monstres_joueur = $sum_monsters;
		$_SESSION["newMonster"] = "yes";
		if($sum_challenges > 0){
			$popMonster = "remainingChallenges";
		}	elseif($sorts_manager->spellsToBuy($joueur) > 0){
			$popMonster = "spellsToBuy";
		} else {
			$popMonster = "ok";
		}
	  if($nb_monstres_joueur == 0 && $joueur->nb_combats() == 4 && $popMonster == "ok") { //On crée un autre monstre solo de l'élément neutre du joueur
	  	 $monstre = new Monstre(array("nb_chasseurs"=>1));
			 $monstres_manager->add_premiers_monstres($monstre, $joueur, $joueur->element_neutre(), $joueur->niveau());
	  } elseif($nb_monstres_joueur == 0 && $joueur->nb_combats() == 3 && $popMonster == "ok"){ //On crée un autre monstre solo de l'élément du joueur
	  	 $monstre = new Monstre(array("nb_chasseurs"=>1));
			 $monstres_manager->add_premiers_monstres($monstre, $joueur, $joueur->element(), $joueur->niveau());
	  } elseif($nb_monstres_joueur == 0 && $joueur->nb_combats() == 2 && $popMonster == "ok"){ //On crée un autre monstre solo de l'élément qui est la faiblesse du joueur
		  $monstre = new Monstre(array("nb_chasseurs"=>1));
		  $playerSpells = $sorts_manager->nb_sorts($joueur);
		  $monstres_manager->add_premiers_monstres($monstre, $joueur, $joueur->element_faiblesse(), $joueur->niveau());
	  } elseif($nb_monstres_joueur == 0 && $joueur->nb_combats() == 1 && $popMonster == "ok"){  //On crée un dernier monstre multi
		 $monstre = $monstres_manager->apparition_monstre_multi($joueur, $joueur->niveau(), 2.5);
		 if($monstres) {
			 array_unshift($monstres , $monstre);
		 }
	 } elseif($popMonster == "ok") {
			$_SESSION["newMonster"] = "no";
		  if($monstres) {
			  $monstre = $monstres[0];
		  } elseif(!isset($_SESSION["msg_fin_tuto"])) {
			  $_SESSION["msg_fin_tuto"] = "deja_vu";
			  $fight = $combats_manager->dernier_combat($joueur);
			  if($fight->issue() == "victoire")  {
			  	 $msg_fin_tuto = "win";
			  } else {
				 	$msg_fin_tuto = "loose";
			  }
		  }
	  }
		if(!$monstres && isset($monstre)){
			$monstres = array();
			$monstres[] = $monstre;
		}
}

	if($timeSlot != "NoTimeSlot" && $challengesDoneToday < 3){
		$monstres = false;
	}

	if(!isset($podium_saison) && !isset($nouveau_monstre_multi) && !isset($msg_end_fight) && !isset($monstre) && !isset($msg_fin_tuto) && (!isset($popMonster) || $popMonster == "ok") && $joueur->tuto() == "fini" && $monstres_manager->nb_monstres_total($joueur) == 0 && $sum_challenges == 0){
		$dateSignUp = strtotime($joueur->date_inscription());
		$dateIntegrationVideos = strtotime("2016-12-01 23:00:00");
		if($joueur->histoires_vues() != "" && count($joueur->histoires_vues()) == 1 && $dateSignUp > $dateIntegrationVideos){
			header('Location:/app/controllers/cinematique.php');
		}
	}

  $position = $joueur->position();
  $posX = $position["posX"];
  $posY = $position["posY"];
  include_once("header.php");
  include_once("index_co.php");
  include_once("footer_view.php");
}
else //Si l'utilisateur n'est pas connecté
{
  if(isset($_POST["pseudo"]))
	{
		$ok = 0;
		if(!$manager->exists($_POST["pseudo"])) //Si le pseudo n'existe pas dans la BDD
		{
			$ok ++;
			$msg = "Ce pseudo n'existe pas, vérifie ta saisie !";
		}
		elseif(!$manager->exists(array($_POST["pseudo"], $_POST["mdp"]))) //Si la combinaison pseudo / mdp n'existe pas dans la BDD
		{
			$ok ++;
			$msg = "Et non, ce n'est pas le bon mot de passe !";
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
		} elseif(isset($_GET["reason"]) && ($_GET["reason"] == "tooMuchInvitees" || $_GET["reason"] == "tooMuchStudents") ) {
			$msg = "Désolé, ce code de parrainage ne donne plus le droit à des invitations supplémentaires !";
		}
		$_POST["pseudo"] = "";
		$_POST["mdp"] = "";
		include_once("header.php");
		include_once("connexion_view.php");
		include_once("footer_deco_view.php");
	}
}
