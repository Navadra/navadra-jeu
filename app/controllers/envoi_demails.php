<?php
require("include_path.php");

if(isset($_POST["descriptif_bug"]) && isset($_POST["page_courante"])) //Script pour envoyer un nouveau bug par email
{
	$joueur = $manager->get((int) $_POST["playerId"]);
	setlocale(LC_TIME, 'fr_FR');
	date_default_timezone_set('Europe/Paris');
	$date = strftime('%Y-%m-%d_at_%Hh_%Mmin_%Ss',time());
	$save = str_replace('data:image/png;base64,', '', $_POST['image'] );
	$file_name = $date.'_by_'.$joueur->prenom().'_'.$joueur->nom().'.png';
	file_put_contents( '../../screenshots/'.$file_name, base64_decode( $save ) );

	$joueur->send_email("34476", "Navadra", "Nouveau bug signalé par ".$joueur->pseudo(), "debug@navadra.com", $params = '{ "user": "'.$joueur->prenom().' '.$joueur->nom().' ('.$joueur->email().')", "page": "'.$_POST["page_courante"].'", "description": "'.$_POST["descriptif_bug"].'", "challenge": "'.$_POST["challenge"].'", "answerUser": "'.$_POST["answerUser"].'", "answerReal": "'.$_POST["answerReal"].'", "screenshot": "http://jeu.navadra.com/screenshots/'.$file_name.'"  }');

}

if(isset($_POST["bugChallenge"]) && isset($_POST["situation"]) && isset($_POST["playerId"])) //Send a bug related to a challenge
{
	$joueur = $manager->get((int) $_POST["playerId"]);

	$joueur->send_email("34748", "Navadra", "Challenge buggé signalé par ".$joueur->pseudo(), "debug@navadra.com", $params = '{ "user": "'.$joueur->prenom().' '.$joueur->nom().' ('.$joueur->email().')", "idChallenge": "'.$_POST["bugChallenge"].'", "situation": "'.$_POST["situation"].'", "details": "'.$_POST["error_info"].'" }');

}
