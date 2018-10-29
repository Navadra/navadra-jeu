<?php
require("include_path.php");
require("controleur_global.php");

//Variable used in the view
if($joueur->bulles_daide_actives() == "oui"){
	$bulles_daide = "checked";
}else{
	$bulles_daide = "";
}
if($joueur->advanced_description() == 1){
	$advanced_description = "checked";
}else{
	$advanced_description = "";
}
$music_settings = $joueur->music_settings();
if($music_settings[0] == 1){
	$volume_music = "checked";
}else{
	$volume_music = "";
}
if($music_settings[1] == 1){
	$volume_sound_effects = "checked";
}else{
	$volume_sound_effects = "";
}
if($music_settings[2] == 1){
	$volume_interface = "checked";
}else{
	$volume_interface = "";
}
if($joueur->sameEmail()){
	$sameEmail = "checked";
}else{
	$sameEmail = "";
}

//Determine which tab to display
if(isset($_GET["tab"]) && $_GET["tab"]=="avatar"){
	$href_tab1 = "/app/controllers/profil.php?id=".$joueur->id();
	$href_tab2 = "/app/controllers/profil.php?id=".$joueur->id();
	$get = "?tab=avatar";
} else {
	$href_tab1 = "/index.php";
	$href_tab2 = "#";
	$get = "";
}

//Si l'utilisateur a soumis une réponse
if(isset($_POST["pseudo"])){
		//On effectue les contrôles sur les données utilisateur
		$ok = 0;
		$msg=array();
		if ($_POST["nom"] != "" && !$joueur->nomValide($_POST["nom"]))	{
			$ok++;
			$msg["nom"]="Entre 3 et 30 caractères sans chiffres.";
		}	elseif ($_POST["nom"] == "" && $joueur->nom() != "") {
			$ok++;
			$msg["nom"]="Tu ne peux pas effacer ton nom sans en renseigner un autre à la place.";
		}
		if ($_POST["prenom"] != "" && !$joueur->nomValide($_POST["prenom"])){
			$ok++;
			$msg["prenom"]="Entre 3 et 30 caractères sans chiffres.";
		} elseif ($_POST["prenom"] == "" && $joueur->prenom() != "") {
			$ok++;
			$msg["prenom"]="Tu ne peux pas effacer ton prénom sans en renseigner un autre à la place.";
		}
		if (!$joueur->pseudoValide($_POST["pseudo"]))	{
			$ok++;
			$msg["pseudo"]="Entre 3 et 15 caractères : lettres, chiffres, espace et certains caractères spéciaux (' -@_).";
		}
		elseif($manager->exists($_POST["pseudo"]) && $_POST["pseudo"]!= $joueur->pseudo())	{
			$ok++;
			$msg["pseudo"]="Désolé mais ce pseudo est déjà pris !";
		}
		if ($_POST["mdp_new"] != "" || $_POST["mdp2_new"] != "") //Si un nouveau mot de passe a été renseigné
		{
			if (!$joueur->mdpValide($_POST["mdp_new"])) //Si le premier mot de passe n'est pas valide
			{
				$ok++;
				$msg["mdp_new"]="Entre 6 et 30 caractères : lettres, chiffres et ponctuation (.!?_-).";
			}
			elseif (!$joueur->mdpValide($_POST["mdp2_new"])) //Si le second mot de passe n'est pas valide
			{
				$ok++;
				$msg["mdp_2new"]="Entre 6 et 30 caractères : lettres, chiffres et ponctuation (.!?_-).";
			}
			elseif(!$joueur->mdpIdentiques($_POST["mdp_new"], $_POST["mdp2_new"]))
			{
				$ok++;
				$msg["mdp_new"]="Tes 2 mots de passe doivent être identiques.";
				$msg["mdp_2new"]="Tes 2 mots de passe doivent être identiques.";
			}
			elseif(!$manager->exists($_POST["pseudo"], $_POST["mdp"]))
			{
				$ok++;
				$msg["mdp"]="Mauvais mot de passe, essaie encore !";
			}
		}
		elseif ($_POST["email"] != "" && !$joueur->emailValide($_POST["email"])) //Si le champ email est non vide, alors on vérifie que ce soit une adresse valide
		{
			$ok++;
			$msg["email"]="L'adresse ne respecte pas le format email classique.";
		}
		elseif ($_POST["email"] != "" && $joueur->classe() != "Prof" && !$joueur->domaineValide($_POST["email"]))
		{
			$ok++;
			$msg["email"]="Ce nom de domaine n'est pas autorisé.";
		}
		elseif($_POST["email"] != "" && $manager->exists($_POST["email"]) && $_POST["email"]!= $joueur->email() && (!isset($_POST["email_parent"]) || $_POST["email"] != $_POST["email_parent"]) )
		{
			$ok++;
			$msg["email"]="Il existe déjà un compte avec cette adresse email !";
		}
		elseif ($_POST["email"] == "" && $joueur->email() != "") //Si le champ email est vide, alors que le joueur avait déjà renseigné une adresse email
		{
			$ok++;
			$msg["email"]="Pour la sécurité de ton compte, tu ne peux pas supprimer une adresse email sans en renseigner une autre à la place.";
			$_POST["email"] = $joueur->email();
		}
		elseif ($joueur->classe() != "Prof" && $_POST["email_parent"] != "" && !$joueur->emailValide($_POST["email_parent"])) //Si le champ email_parent est non vide, alors on vérifie que ce soit une adresse valide
		{
			$ok++;
			$msg["email_parent"]="L'adresse ne respecte pas le format email classique.";
		}
		elseif ($joueur->classe() != "Prof" && $_POST["email_parent"] != "" && !$joueur->domaineValide($_POST["email_parent"]))
		{
			$ok++;
			$msg["email_parent"]="Ce nom de domaine n'est pas autorisé.";
		}
		elseif ($joueur->classe() != "Prof" && $_POST["email_parent"] == "" && $joueur->email_parent() != "") //Si le champ email est vide, alors que le joueur avait déjà renseigné une adresse email
		{
			$ok++;
			$_POST["email_parent"] = $joueur->email_parent();
			$msg["email_parent"]="Tu ne peux pas supprimer une adresse email d'un parent sans en renseigner une autre à la place.";
		}
		if ($_POST["departement"] != "" || $_POST["college"] != "") {
			$CollegeList = array();
			$q = $db_RO->prepare('SELECT * FROM colleges');
			$q->execute();
			while($data = $q->fetch()) {
				$CollegeList[] = array("departement" => $data["departement"], "nom" => $data["nom"]);
			}
		}
		if ($_POST["departement"] != "" && !$joueur->departementValide($CollegeList, $_POST["departement"])){
			$ok++;
			$msg["departement"]="Le département doit être dans la liste proposée.";
		} elseif ($_POST["departement"] == "" && $joueur->departement() != "") {
			$ok++;
			$msg["departement"]="Tu ne peux pas effacer ton département sans en renseigner un autre à la place.";
		}
		if ($_POST["college"] != "" && !$joueur->collegeValide($CollegeList, $_POST["departement"], $_POST["college"]) && $_POST["college"] != "Ecole à la maison")	{
			$ok++;
			$msg["college"]="Le collège doit être dans la liste proposée.";
		} elseif ($_POST["college"] == "" && $joueur->college() != "") {
			$ok++;
			$msg["college"]="Tu ne peux pas effacer ton collège sans en renseigner un autre à la place.";
		}

		//S'il y a au moins une erreur
		if($ok>0)
		{
			include_once("header.php");
			include_once("parametres_view.php");
			include_once("footer_view.php");
		}
		//S'il n'y a pas d'erreur
		else
		{
			if($joueur->classe() == "Prof"){
				$_POST["email_parent"] = "";
			}
			if($_POST["email"] != $joueur->email()){
				$joueur->setEmail_confirme(0);
				if($_POST["email"] != ""){
					$joueur->setEmail($_POST["email"]);
					$joueur->sendEmailActivationLink();
				}
			}
			$joueur->hydrate(array(
	      'nom' => $_POST["nom"],
				'prenom' => $_POST["prenom"],
				'pseudo' => $_POST["pseudo"],
				'email' => $_POST["email"],
				'email_parent' => $_POST["email_parent"],
				'avatar_entier' => $_POST["avatar_entier"],
				'avatar_tete' => $_POST["avatar_tete"],
				'departement' => $_POST["departement"],
				'college' => $_POST["college"],
    	));
			if($_POST["mdp_new"] != "")
			{
				$joueur-> setMdp(sha1($_POST["mdp_new"])); //On met à jour seulement si un nouveau mdp a été soumis.
			}
			$joueur->update_portrait();
			$manager->update($joueur);
			$msg_conf = "Bien compris, les modifications ont été prises en compte !";
			include_once("header.php");
			include_once("parametres_view.php");
			include_once("footer_view.php");
		}
}
else
{
	$_POST["nom"] = $joueur->nom();
	$_POST["prenom"] = $joueur->prenom();
	$_POST["pseudo"] = $joueur->pseudo();
	$_POST["mdp"] = "";
	$_POST["mdp_new"] = "";
	$_POST["mdp2_new"] = "";
	$_POST["email"] = $joueur->email();
	$_POST["email_parent"] = $joueur->email_parent();
	$_POST["avatar_tete"] = $joueur->avatar_tete();;
	$_POST["avatar_entier"] = $joueur->avatar_entier();
	$_POST["departement"] = $joueur->departement();
	$_POST["college"] = $joueur->college();
	include_once("header.php");
	include_once("parametres_view.php");
	include_once("footer_view.php");
}
