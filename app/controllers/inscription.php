<?php
require("include_path.php");
$server_add = "";
$server = "";
if( isset($_SERVER["SERVER_ADDR"])) $server_add = $_SERVER["SERVER_ADDR"];
if( isset($_SERVER["SERVER_NAME"])) $server = $_SERVER["SERVER_NAME"];
if($server != "localhost" && $server_add != "localhost" && $server != "127.0.0.1" && $server_add != "127.0.0.1"){
        $rootServer = "https://jeu.navadra.com";
} else {
        $rootServer = "http://localhost";
}

//Si l'utilisateur a soumis une réponse
if (isset($_POST["pseudo"])) {
  //On crée un nouveau joueur
  $joueur = new Joueur(array(
      "nom" => "",
      "prenom" => "",
      "pseudo" => $_POST["pseudo"],
      "mdp" => $_POST["mdp"],
      "email" => $_POST["email"],
      "sexe" => $_POST["sexe"],
      "classe" => $_POST["classe"],
      "avatar_entier" => $_POST["avatar_entier"],
      "avatar_tete" => $_POST["avatar_tete"],
      "departement" => "",
      "college" => ""
  ));

  $email_on = strcmp( $_POST["noEmail"], "on") == 0;
  if ($email_on) {
    error_log("OK : " . $email_on);
  } else {
    error_log("KO : " . $email_on);
  }
  //On effectue les contrôles sur les données utilisateur
  $ok = 0;
  $msg = array();
  if (!$joueur->pseudoValide($joueur->pseudo())) {
    $ok++;
    $msg["pseudo"] = "Entre 3 et 15 caractères : lettres, chiffres, espace et certains caractères spéciaux (' -@_).";
  } elseif ($manager->exists($joueur->pseudo())) {
    $ok++;
    $msg["pseudo"] = "Désolé, ce pseudo est déjà pris !";
  }
  if (!$joueur->mdpValide($joueur->mdp())) {
    $ok++;
    $msg["mdp"] = "Entre 6 et 30 caractères : lettres, chiffres et ponctuation (.!?_-).";
  }
  if ($joueur->sexe() != "gars" && $joueur->sexe() != "fille") {
    $ok++;
    $msg["sexe"] = "Tu es un gars ou une fille ?";
  }
  if ($joueur->classe() != "6°" && $joueur->classe() != "5°" && $joueur->classe() != "4°" && $joueur->classe() != "3°" && $joueur->classe() != "Prof" && $joueur->classe() != "Autre") {
    $ok++;
    $msg["classe"] = "En quelle classe es-tu ?";
  }
  if ( !$email_on && !$joueur->emailValide($joueur->email())) {
    $ok++;
    $msg["email"] = "Cette adresse email n'a pas l'air bonne :(";
  }
  elseif (! $email_on && $manager->exists($joueur->email())) {
    $ok++;
    if(isset($_GET["id"])){
      $msg["pseudo"] = "Il existe déjà un compte avec ton adresse email, essaie de te connecter !";
    } else {
      $msg["email"] = "Il existe déjà un compte avec cette adresse email, essaie de te connecter !";
    }
  }

  if ($ok > 0) {
                $parametersForm = "?".$_SERVER["QUERY_STRING"];
                $codeSponsor = $_POST["codeSponsor"];
                $codeClassroom = $_POST["codeClassroom"];
    if ($email_on) {
      $checkedNoEmail = "checked='checked'";
    } else {
      $checkedNoEmail = "";
    }
    include_once("header.php");
    include_once("inscription_view.php");
    include_once("footer_deco_view.php");
  } //S'il n'y a pas d'erreur
  else {
                $manager->add($joueur);
    $_SESSION["joueur"] = $joueur;
    //Fill the existing code used by the player
    if ($codes_manager->exists($_POST["codeSponsor"]) && $codes_manager->usable($_POST["codeSponsor"]) == "ok" && $joueur->classe() != "Prof") {
      $codeSponsor = $codes_manager->get_code($_POST["codeSponsor"]);
      $codeSponsor->addInvitee($joueur);
      $codes_manager->update($codeSponsor);
    }
                if (isset($_GET["id"])) {
                        $_GET["id"] = str_replace(" ", "+", $_GET["id"]);
                        $emailPlayer = $joueur->decrypt($_GET["id"]);
                        if($joueur->emailValide($emailPlayer)){
                                $joueur->setEmail_confirme(1);
                        }
                }
                if ($classrooms_manager->exists( $_POST["codeClassroom"] )) {
      $classroom = $classrooms_manager->getByCode( $_POST["codeClassroom"] );
      $usable = $classroom->notFull();
                        if($usable == "ok"){
              $classroom->addStudent($joueur);
              $classrooms_manager->update($classroom);
              $teacher = $manager->get($classroom->idTeacher());
              $joueur->setCollege($teacher->college());
              $joueur->setDepartement($teacher->departement());
              $joueur->setClasse($classroom->level());
                        }
    }
                $manager->update($joueur);
    //Create a new code as a sponsor if not an admin
    if (!$joueur->admin()) {
      $code = $codes_manager->generateCode($joueur, "inscription", 3, "Généré automatiquement à l'inscription");
      $codes_manager->add($code);
    }
    if ($joueur->classe() == "Prof") {
      //$joueur->send_email("84518", "Navadra", "Nouveau Prof inscrit : " . $joueur->email(), "espritdenavadra@navadra.com", $params = '{ "Email": "' . $joueur->email() . '", "Pseudo": "' . $joueur->pseudo() . '" }');
    }
    //Give a title Beta testeur to the player
    $title = new Title(array(
        "player_id" => $joueur->id(),
        "name" => "Bêta-testeur"
    ));
    $titles_manager->add($title);
    header("Location:../../index.php");
  }
} else {

  setlocale(LC_TIME, "fr_FR");
  date_default_timezone_set("Europe/Paris");
        $parametersForm = "?";
        $fictive_player = new Joueur (array("Pseudo" => "Fictif"));
        if (isset($_GET["token"])) {
                $_GET["token"] = str_replace(" ", "+", $_GET["token"]);
                $codeSponsor = $fictive_player->decrypt($_GET["token"]);
                if (!$codes_manager->exists($codeSponsor)) {
      unset($codeSponsor);
    } elseif ($codes_manager->usable($codeSponsor) == "ok") {
                        $codeSponsorObject = $codes_manager->get_code($codeSponsor);
                        $parametersForm .= "token=".$codeSponsor;
    } elseif ($codes_manager->usable($codeSponsor) != "ok") {
      unset($codeSponsor);
    }
  }
        if (isset($codeSponsorObject) && $codeSponsorObject->category() == "Prof") {
    $_POST["classe"] = "Prof";
  } else {
    $_POST["classe"] = "";
  }
        if (isset($_GET["id"])) {
                $_GET["id"] = str_replace(" ", "+", $_GET["id"]);
                $emailPlayer = $fictive_player->decrypt($_GET["id"]);
                if($parametersForm == "?"){
                        $parametersForm .= "id=".$_GET["id"];
                } else {
                        $parametersForm .= "&id=".$_GET["id"];
                }
        } else {
                $emailPlayer = "";
        }
        if (isset($_GET["class"])) {
    $codeClassroom = $_GET["class"];
                if (!$classrooms_manager->exists($codeClassroom)) {
      unset($codeClassroom);
    } elseif ($classrooms_manager->exists($codeClassroom)) {
      $classroom = $classrooms_manager->getByCode($codeClassroom);
      $usable = $classroom->notFull();
                        if($usable == "ok"){
                                unset($codeClassroom);
                        } else {
                                if($parametersForm == "?"){
                                        $parametersForm .= "class=".$_GET["class"];
                                } else {
                                        $parametersForm .= "&class=".$_GET["class"];
                                }
                        }
    }
  }
  $_POST["pseudo"] = "";
  $_POST["mdp"] = "";
  $_POST["email"] = $emailPlayer;
  $_POST["noEmail"] = "";
  $_POST["sexe"] = "";
  $_POST["avatar_entier"] = "/webroot/img/avatars/fille_1_blond_bleu_occ.png";
  $_POST["avatar_tete"] = "/webroot/img/avatars/tete_fille_1_blond_bleu_occ.png";
  $checkedNoEmail = "";
  include_once("header.php");
  include_once("inscription_view.php");
  include_once("footer_deco_view.php");
}
