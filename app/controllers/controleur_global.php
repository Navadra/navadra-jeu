<?php

$joueur = $_SESSION["joueur"];
if (!isset( $joueur ) ) {
  header("Location: https://jeu.navadra.com");
  exit;
}
$joueur = $manager->get($joueur->id()); //Permet de prendre en comptes les actions des autres joueurs

$adresse = $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"]; //Déterminer la page sur laquelle est le joueur
if (preg_match("#app/controllers/parametres.php#", $adresse)) {
  $adresse = "parametres";
} elseif (preg_match("#app/controllers/cinematique.php#", $adresse)) {
  $adresse = "cinematique";
} elseif (preg_match("#app/controllers/accueil_defi.php#", $adresse)) {
  $adresse = "accueil_defi";
} elseif (preg_match("#app/controllers/instructions_defi.php#", $adresse)) {
  $adresse = "instructions_defi";
} elseif (preg_match("#app/controllers/entrainement.php#", $adresse)) {
  $adresse = "entrainement";
} elseif (preg_match("#app/controllers/fin_defi.php#", $adresse)) {
  $adresse = "fin_defi";
} elseif (preg_match("#app/controllers/new_defi.php#", $adresse)) {
  $adresse = "new_defi";
} elseif (preg_match("#app/controllers/grimoire.php#", $adresse)) {
  $adresse = "grimoire";
} elseif (preg_match("#app/controllers/prepa_combats.php#", $adresse)) {
  $adresse = "prepa_combats";
} elseif (preg_match("#app/controllers/combats_decks.php#", $adresse)) {
  $adresse = "combats_decks";
} elseif (preg_match("#app/controllers/combattre.php#", $adresse)) {
  $adresse = "combattre";
} elseif (preg_match("#navadra.com/$#", $adresse)) {
  $adresse = "index";
} elseif (preg_match("#index.php#", $adresse)) {
  $adresse = "index";
} elseif (preg_match("#app/controllers/classement.php#", $adresse)) {
  $adresse = "classement";
} elseif (preg_match("#app/controllers/contacts.php#", $adresse) && isset($_GET["id"])) {
  $adresse = "inviter_contacts";
} elseif (preg_match("#app/controllers/contacts.php#", $adresse)) {
  $adresse = "contacts";
} elseif (preg_match("#app/controllers/histoires.php#", $adresse)) {
  $adresse = "histoires";
} elseif (preg_match("#app/controllers/liste_combats.php#", $adresse)) {
  $adresse = "liste_combats";
} elseif (preg_match("#app/controllers/messages.php#", $adresse)) {
  $adresse = "messages";
} elseif (preg_match("#app/controllers/profil.php#", $adresse)) {
  $adresse = "profil";
} elseif (preg_match("#app/controllers/recherche.php#", $adresse)) {
  $adresse = "recherche";
} elseif (preg_match("#app/controllers/customQuestions.php#", $adresse)) {
  $adresse = "customQuestions";
} elseif (preg_match("#app/controllers/admin.php#", $adresse)) {
  $adresse = "admin";
}

//Détermination du nombre de jours entre la dernière connexion et aujourd'hui
$nb_jours_dernier_log = $joueur->nb_jours_dernier_log();
if ($nb_jours_dernier_log == 0) {
  $playerFirstConnexion = 0;
} else {
  $playerFirstConnexion = 1;
}
if ($abonnements_manager->get_last_by_player_id($joueur->id()) != null) {
  $existingPayment = 1;
} else {
  $existingPayment = 0;
}
if($joueur->nb_jours_fin_tuto() == 0){
  $tutoFinishedToday = 1;
} else {
  $tutoFinishedToday = 0;
}

switch ($joueur->sexe()) {
  case "gars" :
    $feminin = "";
    break;
  case "fille" :
    $feminin = "e";
    break;
}

//Mise à jour de la date de dernière connexion
setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Paris');

$joueur->setDernier_log(strftime('%Y-%m-%d %H:%M:%S', time())); //

if($joueur->tuto() != "fini"){
  $etapes_tuto = ["cinematique_0", "index_1", "combattre_2", "index_3", "accueil_defi_4", "fin_defi_5", "grimoire_6", "grimoire_7", "index_8", "prepa_combats_9", "combats_decks_10", "combattre_11", "index_12", "fini"];
  $tuto_next = false;
  if(isset($_GET["tuto"])) $tuto_next = $_GET["tuto"] == "next";
  $num_etape_actuelle = array_search( $joueur->tuto() , $etapes_tuto); // Index of the current tuto
  $prochaine_etape = $etapes_tuto[$num_etape_actuelle + 1];
  if ($tuto_next && ($prochaine_etape != "grimoire_7" || isset($_POST["num_sort"])) && (($prochaine_etape == "fini" && $adresse == "index") || ($prochaine_etape == "fin_defi_5" && $adresse == "new_defi") || strpos($prochaine_etape, $adresse) !== false) ) {
    $joueur->setTuto($prochaine_etape);
    if($prochaine_etape == "fini") {
      setlocale(LC_TIME, 'fr_FR');
      date_default_timezone_set('Europe/Paris');
      $date = strftime('%Y-%m-%d %H:%M:%S',time());
      $joueur->setDate_fin_tuto($date);
			if($classrooms_manager->hasTimeSlot($joueur) == "NoTimeSlot"){
				$joueur->setStock_challenges(2); //2 other challenges
			}
			$joueur->setNb_combats(4);
			$joueur->sendEmailActivationLink();
			if(!$joueur->sameEmail() && $joueur->classe() != "Prof"){
				$joueur->send_email("34640", "Jérémie", "Navadra, l'aventure commence ici !", $joueur->email(), $params = '{ "Prenom": "'.$joueur->prenom().'" }');
			} elseif($joueur->classe() == "Prof"){
				$joueur->send_email("110352", "Jérémie", "Navadra : un outil pensé pour les profs", $joueur->email(), $params = '{ "Prenom": "'.$joueur->prenom().'" }');
			}
    }
  }
}
$manager->update($joueur);

$hour = strftime('%H', time());
// ACHIEVEMENTS : Trophée connexion entre 6H et 9H du mat (69)
if ($hour > 5 && $hour < 10) {
  $achievement_manager->add($joueur->id(), 69);
}

if($adresse != "fin_defi"){
	include_once("challengesMonstersGeneration.php");
}

if ($joueur->tuto() == "fini") {
  //Crée une nouvelle saison si changement de mois
  if ($saisons_manager->nouvelle_saison()) {
    $joueurs_classement = $manager->liste_joueurs_classement();
    $classement_saison = array();
    $prestige_saison = array();
    foreach ($joueurs_classement as $jou) {
      $classement_saison[] = $jou->id();
      $prestige_saison[] = $jou->prestige();
      if ($joueur->id() != $jou->id()) {
        $jou->setPrestige(0);
        $manager->update($jou);
      } else {
        $joueur->setPrestige(0);
        $manager->update($joueur);
      }
    }
    $classement_saison = implode(",", $classement_saison);
    $prestige_saison = implode(",", $prestige_saison);
    $saison = new Saison(array(
        "classement" => $classement_saison,
        "prestige" => $prestige_saison,
        "vues" => "",
    ));
    $saisons_manager->add($saison);
  }
}

$redirection = false;
//Redirection toward parameters if player has not completed his profile at level 4
/*
if ($joueur->requireEmailCompletion($classrooms_manager->hasTimeSlot($joueur)) || $joueur->requireEmailConfirmation($classrooms_manager->hasTimeSlot($joueur))) {
  switch ($adresse) {
    case ("parametres") :
      break;
    case ("fin_defi") :
      break;
    case ("index") :
      header('Location:/app/controllers/parametres.php');
      break;
    default:
      header('Location:/app/controllers/parametres.php');
      break;
  }
  $redirection = true;
} */

//Si le joueur tente de quitter un combat en cours, considère le combat comme perdu
if (isset($_SESSION["combat_en_cours"]) && $combats_manager->exists_id((int)$_SESSION["combat_en_cours"]) && $joueur->tuto() == "fini") {
  $combat = $combats_manager->get_id((int)$_SESSION["combat_en_cours"]);
  if ($joueur->id() == $combat->prochain_a_jouer()) //Si le joueur est toujours le prochain à jouer sur ce combat, c'est qu'il a essayé de tricher en quittant le combat en cours
  {
    $deroulement = $combat->deroulement();
    if (isset($_SESSION["deroulement_combat"])) //Si une variable de Session a été créée (compense Ajax défaillant)
    {
      $copie_monstre = $combat->copie_monstre();
      $deroulement = $_SESSION["deroulement_combat"];
      $deroulement_txt = "";
      foreach ($deroulement as $etape) {
        $deroulement_txt .= $etape . ";";
      }
      $issue = "";
      if (strpos($deroulement_txt, "defaite" !== false)) {
        $issue = "defaite";
      } elseif (strpos($deroulement_txt, "victoire" !== false)) {
        $issue = "victoire";
      }
      if ($issue == "defaite" || $issue == "victoire") {
        $deroulement_txt = substr($deroulement_txt, 0, strlen($deroulement_txt) - 1); //On enlève le point virgule final si le combat est fini
        setlocale(LC_TIME, 'fr_FR');
        date_default_timezone_set('Europe/Paris');
        $date = strftime('%Y-%m-%d %H:%M:%S', time());
        $combat->setDate_combat($date);
        if ($issue == "defaite") {
          $combat->setIssue("defaite");
          $combat->setPrestige($copie_monstre->perte_prestige($combat->nb_chasseurs(), $classrooms_manager->hasTimeSlot($joueur)));
        } elseif ($issue == "victoire") {
          $combat->setIssue("victoire");
          $combat->setPrestige($copie_monstre->gain_prestige($combat->nb_chasseurs(), $classrooms_manager->hasTimeSlot($joueur)));
        }
        $combat->setVu("");
        $combat->combat_vu($joueur);
      }
      $deroulement = $deroulement_txt;
      $combat->setEndu_monstre_restante((int)$_SESSION["endu_monstre"]);
      unset($_SESSION["deroulement_combat"]);
      unset($_SESSION["endu_monstre"]);
    } elseif ($combat->nb_chasseurs_ko() == $combat->nb_chasseurs() - 1) //Si le joueur était le dernier à jouer
    {
      $deroulement .= "defaite, , , ";
      $combat->setIssue("defaite");
      setlocale(LC_TIME, 'fr_FR');
      date_default_timezone_set('Europe/Paris');
      $date = strftime('%Y-%m-%d %H:%M:%S', time());
      $combat->setDate_combat($date);
      $copie_monstre = $combat->copie_monstre();
      $combat->setPrestige($copie_monstre->perte_prestige($combat->nb_chasseurs(), $classrooms_manager->hasTimeSlot($joueur)));
      foreach ($combat->ordre() as $id_chasseur) //On met à jour le prestige des chasseurs
      {
        if ($joueur->id() != $id_chasseur) {
          $chasseur = $manager->get($id_chasseur);
          $chasseur->setPrestige($chasseur->prestige() + $combat->prestige());
          $manager->update($chasseur);
        } else {
          $joueur->setPrestige($joueur->prestige() + $combat->prestige());
          $manager->update($joueur);
        }
      }
      $combat->setVu("");
    } else //Si le joueur n'était pas le dernier à jouer
    {
      $deroulement .= "changer_joueur, , , ;";
      $combat->setVu("");
    }
		$combat->setDeroulement($deroulement);
    $combats_manager->update($combat);
		if($monstres_manager->exists($combat->id_monstre())) {
			$monstreTemp = $monstres_manager->get_id($combat->id_monstre());
			$monstreTemp->setDead(1);
			$monstres_manager->update($monstreTemp);
		}
  }
  unset($_SESSION["combat_en_cours"]);
}

if (isset($_GET["fight"]) && $joueur->tuto() == "fini") {
	$fight = $combats_manager->get_id((int)$_GET["fight"]);
	$monsterFought = $monstres_manager->get_id($fight->id_monstre());
	if ($monsterFought->nb_chasseurs() == 1 && $monstres_manager->nb_monstres_solo($joueur) == 0 && $monstres_manager->nb_monstres_multi($joueur) < 2) { //Le joueur ne peux pas avoir plus de 2 monstres multi
		$niveau_monstre = $joueur->niveau();
		$apparition_monstre_multi = $monstres_manager->apparition_monstre_multi($joueur, $niveau_monstre, "?", $nb_jours_dernier_log); //Apparition aléatoire d'un nouveau monstre multi
		if ($apparition_monstre_multi) {
			$monstres[] = $apparition_monstre_multi;
			$nouveau_monstre_multi = $apparition_monstre_multi;
		}
	}
	/*
  $fight = $combats_manager->get_id((int)$_GET["fight"]);
  $prestige_earned = $fight->prestige();
  if ($prestige_earned > 0) {
    $joueur->setPrestige($joueur->prestige() - $prestige_earned);
    $manager->update($joueur);
    //$old_week_individual_ranking = $manager->week_individual_ranking($joueur, $combats_manager, (int)$_GET["fight"]);
		$old_week_individual_ranking = 0;
    $old_global_individual_ranking = $manager->global_individual_ranking($joueur, $combats_manager);

    $joueur->setPrestige($joueur->prestige() + $prestige_earned);
    $manager->update($joueur);
    //$new_week_individual_ranking = $manager->week_individual_ranking($joueur, $combats_manager);
		$new_week_individual_ranking = 1;
    $new_global_individual_ranking = $manager->global_individual_ranking($joueur, $combats_manager);

    if ($new_week_individual_ranking < $old_week_individual_ranking || $new_global_individual_ranking < $old_global_individual_ranking) {
      $msg_end_fight = $joueur->ranking_change($old_week_individual_ranking, $new_week_individual_ranking, $old_global_individual_ranking, $new_global_individual_ranking);
    }
  }*/
}

//Redirection if tutorial not finished
switch ($joueur->tuto()) {
  case "cinematique_0" :
    if ($adresse != "cinematique") {
      header('Location:/app/controllers/cinematique.php');
      $redirection = true;
    }
    break;
  case "index_1" :
    if ($adresse != "index") {
      header('Location:/index.php');
      $redirection = true;
    }
    break;
  case "combattre_2" :
		$combat = $combats_manager->dernier_combat($joueur);
		if ($adresse != "combattre") {
			header('Location:/app/controllers/combattre.php?id=' . $combat->id());
			$redirection = true;
		}
    break;
  case "index_3" :
    if ($adresse != "index") {
      header('Location:/index.php');
      $redirection = true;
    }
    break;
  case "accueil_defi_4" :
    if ($adresse != "defi" && $adresse != "accueil_defi" && $adresse != "instructions_defi" && $adresse != "entrainement" && $adresse != "fin_defi") {
      header('Location:/app/controllers/accueil_defi.php');
      $redirection = true;
    }
    break;
  case "fin_defi_5" :
    if ($challenges_manager->countTries($joueur) == 0 && $adresse != "new_defi" && !isset($_POST["challenge_id"])) {
      header('Location:/app/controllers/new_defi.php');
      $redirection = true;
    } elseif ($challenges_manager->countTries($joueur) > 0 && $adresse != "fin_defi") {
      header('Location:/app/controllers/fin_defi.php');
      $redirection = true;
    }
    break;
  case "grimoire_6" :
    if ($adresse != "grimoire") {
      header('Location:/app/controllers/grimoire.php');
      $redirection = true;
    }
    break;
  case "grimoire_7" :
    if ($adresse != "grimoire") {
      header('Location:/app/controllers/grimoire.php');
      $redirection = true;
    }
    break;
  case "index_8" :
    if ($adresse != "index") {
      header('Location:/index.php');
      $redirection = true;
    }
    break;
  case "prepa_combats_9" :
    $monstres = $monstres_manager->get($joueur);
    $monstre = $monstres[0];
    if ($adresse != "prepa_combats") {
      header('Location:/app/controllers/prepa_combats.php?idm=' . $monstre->id());
      $redirection = true;
    }
    break;
  case "combats_decks_10" :
    $combat = $combats_manager->dernier_combat($joueur);
    if ($adresse != "combats_decks") {
      header('Location:/app/controllers/combats_decks.php?id=' . $combat->id());
      $redirection = true;
    }
    break;
  case "combattre_11" :
    $combat = $combats_manager->dernier_combat($joueur);
    if ($adresse != "combattre") {
      header('Location:/app/controllers/combattre.php?id=' . $combat->id());
      $redirection = true;
    }
    break;
  case "index_12" :
    if ($adresse != "index") {
      header('Location:/index.php');
      $redirection = true;
    }
    break;
  case "fini" :
    break;
  default :
    header('Location:/index.php');
    $redirection = true;
    break;
}

//Empeche le joueur de soumettre 2 fois un formulaire
if ($adresse != "fin_defi" && $redirection == false) {
  $_SESSION["form_soumis"] = false;
} //On réinitialise la variable de session soumission
