<?php
//SCRIPT TO BE EXECUTED EACH DAY AT 16h00
require("include_path.php");

setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Paris');


//error_log ( " AFTER PAYMENT UPDATE METHOD " );
$navadra_secret = 'fdsg645,;68r4x6fg74,fh6;$%:86,46l8np74!68:7tb4rd,687p:to;u68-(7-_e4-jonk4-';
if ( isset($argv) || isset($argv[1]) ) {
  $received = $argument1 = $argv[1];
}
if (!isset($received) || trim($received) === '') {
  $received = $_GET["secret"];
}

if ( $navadra_secret === $received ) {

  //Send emails to players who have not finished their tutorials
  $players = $manager->tutorial_uncomplete();
  if($players){
    foreach($players as $player){
      if ($player->email() !== '') {
        if($player->days_since_inscription() == 3 && !$player->sameEmail() && $player->classe() != "Prof"){
          $player->send_email("38674", $player->tuteur(), "Ton entraînement n'attend que toi !", $player->email(), $params = '{ "Pseudo": "'.$player->pseudo().'", "Tuteur": "'.$player->tuteur().'" }');
        } elseif($player->days_since_inscription() == 14 && !$player->sameEmail() && $player->classe() != "Prof"){
          $player->send_email("38681", "Team", "Ton avis sur Navadra", $player->email(), $params = '{ "Prenom": "'.$player->pseudo().'" }');
        } elseif($player->days_since_inscription() == 3 && $player->classe() == "Prof"){
          $player->send_email("95946", "Team", "Comment utiliser Navadra dans votre classe", $player->email(), $params = '{ "e": "'.$player->feminine("e").'" }');
        }
      }
    }
  }

  //Send emails to players who have finished their tutorial
  $players = $manager->tutorial_complete();
  if($players){
    foreach($players as $player){
      if ($player->email() !== '') {
        $mailSent = false;
        $subscription = $abonnements_manager->get_last_by_player( $player->id() );
        if($subscription != null){
					/*
          $daysLeft = ceil( (strtotime($subscription->getDt_expiration()) - time()) / (60*60*24) );
          $mailSent = true;
          if($daysLeft == 7){
            if(!$player->sameEmail()){
              $player->send_email("38919", "Navadra", "Ton Pass Navadra expire dans 7 jours !", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');
            }
            $player->send_email("79839", "Navadra", "Le Pass Navadra de ".$player->prenom()." expire dans 7 jours.", $player->email_parent(), $params = '{ "Prenom": "'.$player->prenom().'" }');
          } elseif($daysLeft == 3){
            if(!$player->sameEmail()){
              $player->send_email("38920", "Navadra", "Ton Pass Navadra expire dans 3 jours !", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');
            }
            $player->send_email("79840", "Navadra", "Le Pass Navadra de ".$player->prenom()." expire dans 3 jours.", $player->email_parent(), $params = '{ "Prenom": "'.$player->prenom().'" }');
          } elseif($daysLeft == 0){
            if(!$player->sameEmail()){
              $player->send_email("38921", "Navadra", "Ton Pass Navadra vient d'expirer !", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');
            }
            $player->send_email("79798", "Navadra", "Le Pass Navadra de ".$player->prenom()." vient d'expirer.", $player->email_parent(), $params = '{ "Prenom": "'.$player->prenom().'" }');
          } elseif($daysLeft == -6){
            if(!$player->sameEmail()){
              $player->send_email("38957", "Navadra", "Tu nous manques !", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');
            }
            $player->send_email("79841", "Navadra", "Pensez à renouveler le Pass Navadra de ".$player->prenom().".", $player->email_parent(), $params = '{ "Prenom": "'.$player->prenom().'" }');
          } else {
            $mailSent = false;
          } */
        }
        if($player->days_since_last_connexion() == 7 && $mailSent == false && !$player->sameEmail() && $player->classe() != "Prof"){
          $player->send_email("38965", "Team", "Navadra a besoin de toi !", $player->email(), $params = '{ "Pseudo": "'.$player->pseudo().'" }');
        } elseif($player->days_since_last_connexion() == 14 && $mailSent == false && !$player->sameEmail() && $player->classe() != "Prof"){
          if($player->prenom()!=""){
            $firstname = $player->prenom();
          } else {
            $firstname = $player->pseudo();
          }
          $player->send_email("38968", "Team", "Où est-tu ".$player->prenom()." ?", $player->email(), $params = '{ "Prenom": "'.$firstname.'" }');
        } elseif($player->days_since_last_connexion() == 21 && $mailSent == false && !$player->sameEmail() && $player->classe() != "Prof"){
          if($player->prenom()!=""){
            $firstname = $player->prenom();
          } else {
            $firstname = $player->pseudo();
          }
          $player->send_email("38973", "Team", "Ton avis sur Navadra", $player->email(), $params = '{ "Prenom": "'.$firstname.'" }');
        } elseif($player->days_since_inscription() == 3 && $player->classe() == "Prof"){
          $player->send_email("95946", "Team", "Comment utiliser Navadra dans votre classe", $player->email(), $params = '{ "e": "'.$player->feminine("e").'" }');
        }
      }
    }
  }


}
else {
  // echo 42 on error
  echo json_encode("ko");
}
