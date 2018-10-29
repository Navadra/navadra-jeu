<?php
require("include_path.php");
require("controleur_global.php");

//Si l'URL est bien valide
if (isset($_GET["id"]) && $combats_manager->combat_valide($joueur, (int)$_GET["id"])) {
  $combat = $combats_manager->get_id((int)$_GET["id"]);

  //Si le combat ne s'est pas encore achevé et que le joueur est le prochain à jouer
  if ($combat->issue() == "" && $joueur->id() == $combat->prochain_a_jouer()) {
    // Liste des sorts
    $all_spells = $sorts_manager->get($joueur, true);
    // Order spells by elem
    $spells_by_elem = $sorts_manager->order_by_elem($all_spells);

    //On récupère le monstre mais on ne le supprime pas
    $monstre = $combat->copie_monstre();
    if ($monstre == "") {
      $monstre = $monstres_manager->get_id($combat->id_monstre());
      $combat->setCopie_monstre($monstre); //On effectue la copie du monstre
      $combat->setEndu_monstre_restante($monstre->endu());
	  $combats_manager->update($combat);
    }

    //On récupère la liste des joueurs qui participent au combat
    $combattants = array();
    $id_joueurs = $combat->ordre();
    //Permet de gérer le cas des requêtes ajax qui ne sont pas complétées à cause du serveur de merde
    if($id_joueurs == "") {
      if($combat->id_invites() == "") {
        $invites = array();
      }
      else {
        $invites = $combat->id_invites();
      }
      $id_joueurs = array_merge(array($combat->id_orga()), $invites);
      $combat->setOrdre($id_joueurs);
      $combats_manager->update($combat);
    }

    foreach($id_joueurs as $id_joueur) {
      $combattants[] = $manager->get($id_joueur);
    }

    // Count all spells, if less than 6 prepare the droppable array
    $count = count($all_spells);

    $selected_spells = array();
    if ($count < 6) {
      $selected_spells = $all_spells;
    }
    else {
      $selected_spells = $sorts_manager->get_best_spells($joueur, $monstre, $spells_by_elem);
    }


    //Détermination du message du tuteur
    $msg_tuteur = $joueur->msg_tuteur("combats_decks", "", "", ""); //On détermine le message dans la bulle du tuteur

    include_once("header.php");
    include_once("combats_decks_view.php");
    include_once("footer_view.php");
  }
  //Le joueur n'est pas le prochain à jouer, on retourne à l'accueil
  else {
    header('Location:../../index.php');
  }
} //Si le joueur tente d'arriver sur la page sans id du monstre ou avec une mauvaise id redirection vers l'index
else {
  header('Location:../../index.php');
}
