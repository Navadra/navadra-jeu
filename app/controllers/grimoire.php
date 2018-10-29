<?php

require("include_path.php");
require("controleur_global.php");

//Détermination des sorts qui seront proposés au joueur
$sorts = $sorts_manager->liste_sorts($joueur);
$cache = "";

$nb_sorts = $sorts_manager->nb_sorts($joueur);
$niv_pour_nouveau_sort = 1; //Niveau requis pour que le joueur puisse apprendre un nouveau sort
$reinitialiser = explode(",", $joueur->cout_reinitalisation());
$achat = "non";
$chgt_elem = "non";
$element1 = "";
$element2 = "";

if($joueur->tuto() != "fini"){
	$mentor = "/webroot/img/personnages/".strtolower($challenges_manager->new_mentor($joueur))."_portrait.png"; //For tutorial
	$challenge = $challenges_manager->get_next_challenge($joueur); //We get next player challenge
}
else
{
	$mentor = $joueur->portrait_tuteur();
}

if(isset($_POST["num_sort"])) //If the player is trying to learn a new spell
{
	if($_POST["num_sort"] != "") //Si le champ "num_sort" est bien rempli c'est que le joueur a tenté d'acheter un sort
	{
		//On récupère le sort en question
		$sorts_tot = array_merge($sorts["feu"], $sorts["eau"], $sorts["vent"], $sorts["terre"]);
		foreach($sorts_tot as $sort_pot)
		{
			if($sort_pot->num() == $_POST["num_sort"])
			{
				$sort = $sort_pot;
				break;
			}
		}
		//On vérifie qu'il a le niveau requis pour le faire
		if($joueur->niveau() < $sort->niveau_requis())
			{$niveau_requis = false;}
		else
			{$niveau_requis = true;}
		//On vérifie aussi qu'il a le droit d'apprendre un sort supplémentaire (aucune limite pour l'instant)
		if($nb_sorts >= $joueur->nb_sorts_debloques() && !$sorts_manager->sort_existant((int)$_POST["num_sort"], $joueur))
			{$nouv_sort = false;}
		else
			{$nouv_sort = true;}
		//On vérifie qu'il a assez de pyrs
		if(($sort->element1() == "feu" && $joueur->pyrs_feu() < $sort->cout()) || ($sort->element1() == "eau" && $joueur->pyrs_eau() < $sort->cout()) || ($sort->element1() == "vent" && $joueur->pyrs_vent() < $sort->cout()) || ($sort->element1() == "terre" && $joueur->pyrs_terre() < $sort->cout()) )
			{$enoughPyrs = false;}
		else
			{$enoughPyrs = true;}
		//All conditions are necessary to learn a new spell
		if($enoughPyrs && $niveau_requis && $nouv_sort)
		{
			$echec = false;
			//On change l'icone noir et blanc en couleur
			$sort->colorIcon();
			//On note son ancien élément pour voir si l'achat va changer quelque chose
			$ancien_element = $joueur->element();
			//On diminue ses Pyrs du montant du sort et on augmente son total de Pyrs dépensées du montant du sort
			switch($sort->element1())	{
				case "feu":
					$joueur->setPyrs_feu($joueur->pyrs_feu() - $sort->cout());
					$joueur->setPyrs_feu_dep($joueur->pyrs_feu_dep() + $sort->cout());
					break;
				case "eau":
					$joueur->setPyrs_eau($joueur->pyrs_eau() - $sort->cout());
					$joueur->setPyrs_eau_dep($joueur->pyrs_eau_dep() + $sort->cout());
					break;
				case "vent":
					$joueur->setPyrs_vent($joueur->pyrs_vent() - $sort->cout());
					$joueur->setPyrs_vent_dep($joueur->pyrs_vent_dep() + $sort->cout());
					break;
				case "terre":
					$joueur->setPyrs_terre($joueur->pyrs_terre() - $sort->cout());
					$joueur->setPyrs_terre_dep($joueur->pyrs_terre_dep() + $sort->cout());
					break;
			}
			//On détermine le nouvel élément du joueur
			$nouveau_element = $joueur->element();
			if($joueur->changement_element($ancien_element, $nouveau_element)) {
				//$chgt_elem = "oui";
				$joueur->determiner_position();
				$joueur->update_portrait();
				$joueur->reinitialiser_histoires($ancien_element); //S'il connaissait les histoires spécifiques à son ancien élément, on les supprime
			}
			if($sort->niveau()==1){
				$sorts_manager->add($sort, $joueur);
				//If it is the first spell of that element, add 1 ultimate to DB (level 1)
				if(sizeof($sorts_manager->get_element($joueur, $sort->element1())) == 1)
				{
					$sorts_manager->create_ultimate($joueur, $sort->element1());
				}
			}	else {
				$sorts_manager->update($sort, $joueur);
			}
			$sorts = $sorts_manager->liste_sorts($joueur);
			$nb_sorts = $sorts_manager->nb_sorts($joueur);
			$niv_pour_nouveau_sort = 1; //Niveau requis pour que le joueur puisse apprendre un nouveau sort
			$manager->update($joueur);
			$achat = "oui";
			//Détermination du message du tuteur
			$msg_tuteur = $joueur->msg_tuteur("grimoire", "", "", "achat"); //On détermine le message dans la bulle du tuteur
			$spellBought = $sort;
		}
		else
		{
			$echec = true;
		}
	}
	elseif($_POST["num_sort"] == "" && $joueur->pyrs_feu() >= $reinitialiser[0] && $joueur->pyrs_eau() >= $reinitialiser[1] && $joueur->pyrs_vent() >= $reinitialiser[2] && $joueur->pyrs_terre() >= $reinitialiser[3]) //Sinon c'est qu'il a voulu réinitialiser ses sorts
	{
		$echec = false;
		//On diminue ses Pyrs du montant de la réinitialisation
		$joueur->setPyrs_feu($joueur->pyrs_feu() - $reinitialiser[0]);
		$joueur->setPyrs_eau($joueur->pyrs_eau() - $reinitialiser[1]);
		$joueur->setPyrs_vent($joueur->pyrs_vent() - $reinitialiser[2]);
		$joueur->setPyrs_terre($joueur->pyrs_terre() - $reinitialiser[3]);
		//On lui redonne les Pyrs qu'il avait déjà choisies
		$joueur->setPyrs_feu($joueur->pyrs_feu() + $joueur->pyrs_feu_dep() - 1);
		$joueur->setPyrs_eau($joueur->pyrs_eau() + $joueur->pyrs_eau_dep() - 1);
		$joueur->setPyrs_vent($joueur->pyrs_vent() + $joueur->pyrs_vent_dep() - 1);
		$joueur->setPyrs_terre($joueur->pyrs_terre() + $joueur->pyrs_terre_dep() - 1);
		//On lui remet son total de Pyrs dépensées à l'état de départ
		$joueur->setPyrs_feu_dep(1);
		$joueur->setPyrs_eau_dep(1);
		$joueur->setPyrs_vent_dep(1);
		$joueur->setPyrs_terre_dep(1);
		//On augmente son compteur de réinitialisation pour que la prochaine coute plus cher
		$joueur->setReinitialisation_sorts($joueur->reinitialisation_sorts() + 1);
		//On supprime tous ses sorts et on actualise la liste ainsi que son profil
		$sorts_manager->delete_all($joueur);
		$sorts = $sorts_manager->liste_sorts($joueur);
		$manager->update($joueur);
		//Détermination du message du tuteur
		$msg_tuteur = $joueur->msg_tuteur("grimoire", "", "", "conseil"); //On détermine le message dans la bulle du tuteur
	}
	else
	{
		$echec = true;
	}

}
if(!isset($_POST["num_sort"]) || $echec==true) //If he's just looking
{
	$msg_tuteur = $joueur->msg_tuteur("grimoire", "", "", "conseil"); //On détermine le message dans la bulle du tuteur
	$_POST["num_sort"] = "";
	$_POST["reset"] = "";
}

$nb_sorts = $sorts_manager->nb_sorts($joueur);
$couleur_info_sorts = "gris";
$reinitialiser = $joueur->cout_reinitalisation();


include_once("header.php");
include_once("grimoire_view.php");
include_once("footer_view.php");
