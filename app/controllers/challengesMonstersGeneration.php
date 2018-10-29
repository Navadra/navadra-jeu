<?php

$sum_challenges = $challenges_manager->get_all_stock($joueur);
$sum_monsters = $monstres_manager->nb_monstres_total($joueur);
$sum_monsters_solo = $monstres_manager->nb_monstres_solo($joueur);
$timeSlot = $classrooms_manager->hasTimeSlot($joueur);
$challengesDoneToday = $scores_manager->count_scored_today($joueur);
$monstersFoughtToday = $combats_manager->count_finished_today($joueur);

$newChallenges = 3;
$newMonsters = 5;

if($joueur->classe() == "Prof" && $joueur->nb_jours_fin_tuto() != 0 && $sum_challenges == 0){
	$joueur->setStock_challenges(1);
	$sum_challenges ++;
	$manager->update($joueur);
} elseif($joueur->classe() == "Prof"){
	//nothing
} elseif($timeSlot != "NoTimeSlot"){
	if($nb_jours_dernier_log != 0){
		$joueur->setStock_challenges(0);
		$manager->update($joueur);
		$challenges_manager->removeStockChallenges($joueur);
		$sum_challenges = $challenges_manager->get_all_stock($joueur);
		$monstres_manager->updateMonstersLevel($joueur);
	}
	if($sum_challenges == 0 && ($challengesDoneToday < 3 || ($joueur->nb_jours_fin_tuto() > 0 && $monstersFoughtToday > 0) || ($joueur->nb_jours_fin_tuto() == 0 && $monstersFoughtToday >= 6) ) ) {
		$allChallenges = $challenges_manager->get_all_challenges($joueur);
		$oldestTry = "2100-01-01";
		$lowestMastery = 6;
		foreach($allChallenges as $c){
			if(in_array($c->getName(), $timeSlot) && $c->getTries() == 0) {
				$selectedChallenge = $c;
			} elseif(in_array($c->getName(), $timeSlot) && substr($c->getDate_last_try(), 0, 10) <= $oldestTry) {
				$oldestTry = substr($c->getDate_last_try(), 0, 10);
				if($c->getCurrent_level() <= $lowestMastery){
					$lowestMastery = $c->getCurrent_level();
				}
			}
		}
		if(!isset($selectedChallenge)){
			foreach($allChallenges as $c){
				if(in_array($c->getName(), $timeSlot) && $c->getTries() > 0 && substr($c->getDate_last_try(), 0, 10) == $oldestTry && $c->getCurrent_level() == $lowestMastery) {
					$selectedChallenge = $c;
				}
			}
		}
		$selectedChallenge->setStock(1);
		$challenges_manager->save_or_update($selectedChallenge);
		$sum_challenges ++;
	}
	if($sum_monsters_solo == 0 && $joueur->tuto() == "fini" && $challengesDoneToday >= 3 && ($joueur->nb_jours_fin_tuto() > 0 || $monstersFoughtToday >= 6) ){
		$monstres_manager->newMonstersTimeSlot($joueur, 3);
	}
	if($joueur->nb_combats() == 0){
		$joueur->setNb_combats(5);
		$manager->update($joueur);
	}
	$newChallenges = 0;
	$newMonsters = 0;
} else {
	$newChallenges = 3;
	$newMonsters = 3;
}

//Challenge and monsters generation if it is the first connexion of the day
if ($joueur->tuto() == "fini") {
  if ($nb_jours_dernier_log != 0) {
    $nb_new_challenges = max(0, $newChallenges - $sum_challenges);
    $joueur->setStock_challenges($joueur->stock_challenges() + $nb_new_challenges);
    $sum_challenges = $sum_challenges + $nb_new_challenges; //Actualization for the view
  }

  $monstres = $monstres_manager->get($joueur);
  //Vérification du nombre de monstres du joueur et génération de nouveaux monstres si nécessaire
  if ($nb_jours_dernier_log != 0 && $timeSlot == "NoTimeSlot") { //Si la dernière connexion de l'utilisateur ne date pas d'aujourd'hui
    for ($i = 1; $i <= $newMonsters; $i++) { //On rajoute au max 3 monstre solo par jour depuis la dernière connexion
      $nb_monstres_joueur = $monstres_manager->nb_monstres_solo($joueur); //On recalcul à chaque fois le nbre de monstres solo du joueur
      if ($nb_monstres_joueur < 5) { //Si le joueur n'a pas déjà 5 monstres solo on lui en créé un
        $niveau_monstre = $joueur->niveau();
        $monstres[] = $monstres_manager->nouveau_monstre_solo($joueur, $niveau_monstre);
      }
    }
  }

  //Remise à 5 du nombre de combats auxquels le joueur à le droit aujourd'hui
  if ($nb_jours_dernier_log != 0) { //Si la dernière connexion de l'utilisateur ne date pas d'aujourd'hui
    $joueur->setNb_combats(3);
    $joueur->determiner_position(); //on le change de place sur sa zone
    $manager->update($joueur);
  }
}
