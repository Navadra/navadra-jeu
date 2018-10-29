<?php
require("include_path.php");
require("controleur_global.php");
if($joueur->tuto() != "fini")
{
	$mentor = "/webroot/img/personnages/".strtolower($challenges_manager->new_mentor($joueur))."_portrait.png"; //For tutorial
}
else
{
	$mentor = $joueur->portrait_tuteur();
}
if(isset($_GET["id"]) && $challenges_manager->validId((int)$_GET["id"], $joueur)){
	$challenge = $challenges_manager->get_by_id((int)$_GET["id"]);
	$last_score = $scores_manager->get_last_score_challenge($challenge, $joueur);
	$date = strtotime(preg_replace('#\d+:\d+:\d+#','00:00:00',$last_score->getDate_score())); //We consider the last score was made at midnight of the same day
	$date_diff = time() - $date;
	if($date_diff < 24*60*60){
		unset($challenge);
	}
} else {
	$challenge = $challenges_manager->get_next_challenge($joueur); //On récupère le défi avec le plus petit palier en stock
}

//On diminue directement le stock de défi d'un (au cas où le joueur quitte en cours de défi pour le refaire ça ne marchera pas
if ($joueur->tuto() == "fini" && !isset($last_score)){  //On ne diminue le stock d'1 que si le joueur n'est pas en tutoriel
	$challenge->setStock($challenge->getStock() - 1);
}

$challenges_manager->save_or_update($challenge);

include_once("header.php");
include_once("new_defi_view.php");
