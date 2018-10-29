<?php
if (session_status() == PHP_SESSION_NONE) {
	header("Location: https://jeu.navadra.com");
	exit;
}
if(!isset($_SESSION["joueur"])) //Si l'utilisateur n'est pas connecté
{
	include_once("head_references.php"); //Inclure la partie Head de la page HTML avec les différents liens
	include_once("header_deco.php"); //Inclure la vue du Header
}
else  //Si l'utilisateur est connecté
{
	$pourcent_xp = round($joueur->xp()/$joueur->xp_requise() * 100); //Pourcentage utilisé pour la barre d'expérience

	//Améliorations
		$ameliorations_realisees = $ameliorations_manager->get_all_realisees();
		$ameliorations_en_cours = $ameliorations_manager->get_all_en_cours();
		$ameliorations_non_vues = 0;
		foreach($ameliorations_en_cours as $amelioration)
		{
			if(!in_array($joueur->id(), $amelioration->vues()))
			{
				$ameliorations_non_vues++;
			}
		}
		if($ameliorations_non_vues != 0)
		{
			$notif_ameliorations = $ameliorations_non_vues;
		}

//Calcul des notifications
	if($joueur->tuto() == "fini")
	{

		//Liste combats
		$nb_notifs = $combats_manager->notifications($joueur);
		if($nb_notifs>0) //Si le joueur a des notifications de combat
		{
			$notif_combats = $nb_notifs;
		}

		//Histoires
		$nb_histoires_vues = sizeof($joueur->histoires_vues());
		if(!$joueur->histoires_vues())
		{
			$nb_histoires_vues = 0;
		}
		if(sizeof($joueur->histoires_debloques()) != $nb_histoires_vues) //Si le joueur n'a pas vu toutes ses histoires
		{
			$notif_histoires = sizeof($joueur->histoires_debloques()) - $nb_histoires_vues;
		}


		//Messages
		$conversations_h = $conversations_manager->get_all($joueur);
		$nb_conv_non_lues = 0;
		foreach($conversations_h as $conversation_h)
		{
			if($messages_manager->nbre_non_lu($conversation_h, $joueur) != 0)
			{
				$nb_conv_non_lues++;
			}
		}
		if($nb_conv_non_lues != 0)
		{
			$notif_messages = $nb_conv_non_lues;
		}


		//Paramètres
		if($joueur->gameLimitation()){
			//$notif_parametres = 1;
		}	elseif($joueur->percentageCompletionProfile() != 100)	{
			$notif_parametres = 1;
		}

	}
	if($joueur->tuto() != "fini")
	{
		$titre_accueil = "Finis le tutoriel d'abord";
		$titre_profil = "Finis le tutoriel d'abord";
		$titre_classement = "Finis le tutoriel d'abord";
		$titre_combats = "Finis le tutoriel d'abord";
		$titre_grimoire = "Finis le tutoriel d'abord";
		$titre_histoire = "Finis le tutoriel d'abord";
		$titre_messages = "Finis le tutoriel d'abord";
		$titre_contacts = "Finis le tutoriel d'abord";
		$titre_recherche = "Finis le tutoriel d'abord";
		$titre_parametres = "Finis le tutoriel d'abord";
		$nb = "_nb";
		$link_accueil = "#";
		$link_profil = "#";
		$link_classement = "#";
		$link_combats = "#";
		$link_grimoire = "#";
		$link_histoire = "#";
		$link_messages = "#";
		$link_contacts = "#";
		$link_recherche = "#";
		$link_parametres = "#";
	}
	else
	{
		$titre_accueil = "Revenir à l'île";
		$titre_profil = "Profil";
		$titre_classement = "Classement";
		$titre_combats = "Liste des combats";
		$titre_grimoire = "Grimoire";
		$titre_histoire = "Histoire";
		$titre_messages = "Messages";
		$titre_contacts = "Contacts";
		$titre_recherche = "Recherche de joueurs";
		$titre_parametres = "Paramètres";
		$nb = "";
		$link_accueil = "/index.php";
		$link_profil = "/app/controllers/profil.php?id=".$joueur->id();
		$link_classement = "/app/controllers/classement.php";
		$link_combats = "/app/controllers/liste_combats.php";
		$link_grimoire = "/app/controllers/grimoire.php";
		$link_histoire = "/app/controllers/histoires.php";
		$link_messages = "/app/controllers/messages.php";
		$link_contacts = "/app/controllers/contacts.php";
		$link_recherche = "/app/controllers/recherche.php";
		$link_parametres = "/app/controllers/parametres.php";
	}

	$_SESSION["joueur"] = $joueur;
	include_once("head_references.php"); //Inclure la partie Head de la page HTML avec les différents liens

	if (!preg_match("#app/controllers/test_challenge.php#", $adresse) && $adresse!="new_defi" && $adresse!="combattre" && $adresse != "entrainement" && $adresse != "customQuestions")
	{
		include_once("header_co.php");//Inclure la vue du Header
	}
}
