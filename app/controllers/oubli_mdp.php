<?php
require("include_path.php");

if(isset($_POST["email"])){
	if(!$manager->exists($_POST["email"])) {
		$msg_err = "Cette adresse email n'existe pas, vérifie ta saisie !";
	}	else	{
			$joueur = $manager->get($_POST["email"]);
			$mdp_prov=rand(100000, 999999);
			$joueur->setMdp(sha1($mdp_prov));
			$manager->update($joueur);
			//Envoi mail avec mdp_prov
			$joueur->send_email("34470", "Navadra", "Navadra - Mot de passe provisoire", $joueur->email(), $params = '{ "pseudo": "'.$joueur->pseudo().'", "pass": "'.$mdp_prov.'" }');

			$msg_conf = "Tu as reçu un mot de passe tout neuf par email.<br>N'hésite pas à le changer rapidement, il n'a pas l'air facile à retenir...";
	}
	include_once("header.php");
	include_once("oubli_mdp_view.php");
	include_once("footer_deco_view.php");

}
else
{
	$_POST["email"] = "";
	include_once("header.php");
	include_once("oubli_mdp_view.php");
	include_once("footer_deco_view.php");
}
