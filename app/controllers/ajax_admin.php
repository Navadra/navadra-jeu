<?php
require("include_path.php");

$joueur = $_SESSION["joueur"];
if (!isset( $joueur )) {
	header("Location: https://jeu.navadra.com");
	exit;
}

$joueur = $manager->get($joueur->id()); //Permet de prendre en comptes les actions des autres joueurs

if(isset($_POST["getPlayerList"])){
	echo json_encode( $manager->liste_pseudo() );
}

if(isset($_POST["player"]) && isset($_POST["challenges"]) && isset($_POST["soloMonsters"])){
	$player = $manager->get($_POST["player"]);
	$challenges = (int) $_POST["challenges"];
	$soloMonsters = (int) $_POST["soloMonsters"];
	$bigMonsters = (int) $_POST["bigMonsters"];
	$enormousMonsters = (int) $_POST["enormousMonsters"];
	$legendaryMonsters = (int) $_POST["legendaryMonsters"];
	if($challenges > 0){
		$player->setStock_challenges($player->stock_challenges() + $challenges);
		$manager->update($player);
	}
	if($soloMonsters > 0){
		for($i=1; $i <= $soloMonsters; $i ++)	{
			$monstres_manager->nouveau_monstre_solo($player, $player->niveau());
		}
	}
	if($bigMonsters > 0){
		for($i=1; $i <= $bigMonsters; $i ++)	{
			$monstres_manager->apparition_monstre_multi($player, $player->niveau(), 2.5);
		}
	}
	if($enormousMonsters > 0){
		for($i=1; $i <= $enormousMonsters; $i ++)	{
			$monstres_manager->apparition_monstre_multi($player, $player->niveau(), 4.5);
		}
	}
	if($legendaryMonsters > 0){
		for($i=1; $i <= $legendaryMonsters; $i ++)	{
			$monstres_manager->apparition_monstre_multi($player, $player->niveau(), 8);
		}
	}
	if($soloMonsters + $bigMonsters + $enormousMonsters + $legendaryMonsters > 0){
		$player->setNb_combats($player->nb_combats() + $soloMonsters + $bigMonsters + $enormousMonsters + $legendaryMonsters);
		$manager->update($player);
	}
}

if(isset($_POST["deletePlayer"])){
	$player = $manager->get($_POST["deletePlayer"]);
	$exercises_manager->delete_all($player);
	$flow_manager->delete_all($player);
	$scores_manager->delete_all($player);
	$abonnements_manager->delete_all($player);
	$achievement_manager->delete_all($player);
	$ameliorations_manager->delete_all($player);
	$challenges_manager->delete_all($player);
	$codes_manager->delete_all($player);
	$monstres_manager->delete_all($player);
	$messages_manager->delete_all($player);
	$conversations_manager->delete_all($player);
	$impressions_manager->delete_all($player);
	$sorts_manager->delete_all($player);
	$titles_manager->delete_all($player);
	$manager->delete($player);
}

if(isset($_POST["debugFights"])){
	$fights = $combats_manager->get_bugged();
	$nbFights = 0;
	if(is_array($fights)){
		foreach($fights as $fight){
			$flow_manager->deleteFlowsFight($fight->id());
			$combats_manager->delete($fight);
			$nbFights ++;
		}
	}
	echo $nbFights;
}

if(isset($_POST["updatePlayer"])){
	$players = $manager->liste_joueurs_classement();
	foreach ($players as $p){
		$p->update_portrait();
		$manager->update($joueur);
	}
}

if(isset($_POST["update_portrait"])){
	$players = $manager->liste_pseudo();
	foreach ($players as $p){
		$p->update_portrait();
		$manager->update($joueur);
	}
}

if(isset($_GET["send_message"])){
	//Envoi un msg à tous les joueurs
	$joueurs = $manager->liste_joueurs_classement();
	//$joueurs = $manager->studentsList();
	$expediteur = $manager->get(47);
	$dateLimite = strtotime("2016-10-25 16:47:59");
	foreach ($joueurs as $joueur_cible){
		if(strtotime($joueur_cible->date_inscription()) < $dateLimite){
			$contenu = "Salut ".$joueur_cible->prenom()." !<br>Avec la sortie de la Bêta Publique, nous avons été obligés de supprimer beaucoup de comptes mais nous avons gardé le tiens parce que ta progression sur Navadra était impressionnante.";
			if($joueur_cible->classe()=="Autre" || $joueur_cible->classe()=="Prof"){
				$contenu.= "<br>Nous t'avons cependant remis au niveau 1, le système de progression et d'achat de sorts ayant significativement changé. Mais te connaissant, on est convaincus que tu vas vite retrouver ta gloire d'antant ;).";
			} else {
				$contenu.= "<br>En revanche, maintenant Navadra est devenu payant au dessus du niveau 5. Nous tenons vraiment à comprendre les attentes de nos joueurs aussi nous t'offrons 6 mois d'abonnement gratuit si tu acceptes de nous recontrer (avec l'accord de tes parents) pour nous expliquer ce qui t'as plu et ce qui t'as moins plu dans Navadra.<br>Ca t'intéresserait ?";
			}
			$contenu.= "<br>En attendant de te recroiser dans le jeu, toute l'équipe de Navadra te souhaite de bien t'amuser !";
			if($joueur_cible->id() != $expediteur->id()){
				//Si il existe déjà une conversation entre ces joueurs
				if($conversations_manager->conversation_existante($expediteur->id(), $joueur_cible->id())){
					$conversation = $conversations_manager->get($expediteur->id(), $joueur_cible->id());
					$message = new Message(array("id_conversation" => $conversation->id(), "expediteur" => $expediteur->id(), "destinataire" => $joueur_cible->id(), "contenu" => $contenu));
					$messages_manager -> add($message);
				} else { //Sinon on créer une nouvelle conversation
					$conversation = new Conversation(array("joueur1" => $expediteur->id(), "joueur2" => $joueur_cible->id()));
					$conversations_manager -> add($conversation);
					$message = new Message(array("id_conversation" => $conversation->id(), "expediteur" => $expediteur->id(), "destinataire" => $joueur_cible->id(), "contenu" => $contenu));
					$messages_manager -> add($message);
				}
				$conversation->setDate_dernier_msg($message->date_envoi());
			$conversations_manager->update($conversation);
			}
		}
	}
}
/*
if(isset($_GET["sendEmailsTest"])){
	$player = $joueur;
	$player->send_email("34640", "Jérémie", "Navadra, l'aventure commence ici !", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');

	$mdp_prov=rand(100000, 999999);
	$player->send_email("34470", "Navadra", "Navadra - Mot de passe provisoire", $player->email(), $params = '{ "pseudo": "'.$player->pseudo().'", "pass": "'.$mdp_prov.'" }');

	$player->send_email("38674", $player->tuteur(), "Ton entraînement n'attend que toi !", $player->email(), $params = '{ "Pseudo": "'.$player->pseudo().'", "Tuteur": "'.$player->tuteur().'" }');
	$player->send_email("38681", "Jérémie", "Ton avis sur Navadra", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');

	$player->send_email("38919", "Navadra", "Ton abonnement à Navadra expire dans 7 jours !", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');
	$player->send_email("38920", "Navadra", "Ton abonnement à Navadra expire dans 3 jours !", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');
	$player->send_email("38921", "Navadra", "Ton abonnement à Navadra vient d'expirer !", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');
	$player->send_email("38957", "Navadra", "Tu nous manques !", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');

	$player->send_email("38965", "Team", "Navadra a besoin de toi !", $player->email(), $params = '{ "Pseudo": "'.$player->pseudo().'" }');
	$player->send_email("38968", "Jérémie", "Où est-tu ".$player->prenom()." ?", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');
	$player->send_email("38973", "Jérémie", "Ton avis sur Navadra", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'" }');

	$inscription = $player->convertDate($player->date_inscription());
	$countChallenges = $challenges_manager->countTries($player);
	$link = "https://jeu.navadra.com/app/controllers/suivi_joueur.php?link=".$player->suivi_link();
	if($player->echeance_email_parent() != Joueur::EMAIL_PARENT_OK){
		$EmailParent = "Nous n'avons pas pu envoyer d'email à tes parents parce que tu ne l'as toujours pas renseigné. Connecte-toi sur Navadra et va dans tes paramètres. Renseigne ensuite l'email d'un de tes parents pour leur envoyer un lien te permettant de continuer l'aventure en illimité.";
	} else {
		$EmailParent = "Nous avons envoyé un email récapitulatif à l’un de tes parents (".$player->email_parent().") pour l’inviter à prendre un abonnement. Si tes parents n’ont toujours rien reçu, c’est probablement que l’adresse email n’est pas la bonne. Rends-toi alors dans tes paramètres de Navadra pour la modifier.";
	}
	$player->send_email("38704", "Michel", "Votre enfant et Navadra", $player->email_parent(), $params = '{ "Prenom": "'.$player->prenom().'", "DateInscription": "'.$inscription.'", "NbDefis": "'.$countChallenges.'", "Link": "'.$link.'" }');
	$player->send_email("38691", "Navadra", "Niveau 5 atteint ! - Fin de l'accès illimité", $player->email(), $params = '{ "Prenom": "'.$player->prenom().'", "Tuteur": "'.$player->tuteur().'", "EmailParent": "'.$EmailParent.'", "Link": "'.$link.'" }');
} */


//Génération liste emails
if(isset($_GET["emails"])){
	$joueurs = $manager->liste_email();
	foreach ($joueurs as $joueur_cible)	{
		if($joueur_cible->id() != $joueur->id()){
			echo $joueur_cible->email().",";
		}
	}
}

if(isset($_POST["getDashboard"])){
	$periodStart = $_POST["periodStart"]." 00:00:00";
	$periodEnd = $_POST["periodEnd"]." 23:59:59";
	if($_POST["getDashboard"] == "playersPerDay"){
		$data = $exercises_manager->playersActivity($periodStart, $periodEnd);
		$playersPerDay = array();
    foreach($data as $d) {
			if(isset($playersPerDay[$d["dateEx"]])){
				$playersPerDay[$d["dateEx"]] ++;
			} else {
				$playersPerDay[$d["dateEx"]] = 1;
			}
    }
		echo json_encode($playersPerDay);
	} elseif($_POST["getDashboard"] == "addiction"){
		$data = $exercises_manager->playersActivity($periodStart, $periodEnd);
		$logsPerPlayer = array();
    foreach($data as $d) {
			if(isset($logsPerPlayer[$d["playerId"]])){
				$logsPerPlayer[$d["playerId"]] ++;
			} else {
				$logsPerPlayer[$d["playerId"]] = 1;
			}
    }
		$maxLogs = max($logsPerPlayer);
		$logsPerAddiction = array();
		$countValues = array_count_values($logsPerPlayer);
		for($i=1;$i<=$maxLogs;$i++){
			if(isset($countValues[$i])){
				$logsPerAddiction[] = $countValues[$i];
			} else {
				$logsPerAddiction[] = 0;
			}
		}
		echo json_encode($logsPerAddiction);
	} elseif($_POST["getDashboard"] == "retention"){
		$data = $exercises_manager->playersActivityBySignUp($periodStart, $periodEnd);
		$logsPerPlayer = array();
		foreach($data as $d) {
			$daysDiff = round((strtotime($d["dateEx"]) - strtotime($d["dateSignUp"])) / (60*60*24));
			if(!isset($logsPerPlayer[$d["playerId"]])){
				$logsPerPlayer[$d["playerId"]] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			}
			if($daysDiff == 1 && $logsPerPlayer[$d["playerId"]][0] == 0){
				$logsPerPlayer[$d["playerId"]][0] ++;
			}
			if($daysDiff >= 2 && $daysDiff <= 3 && $logsPerPlayer[$d["playerId"]][1] == 0){
				$logsPerPlayer[$d["playerId"]][1] ++;
			}
			if($daysDiff >= 4 && $daysDiff <= 7 && $logsPerPlayer[$d["playerId"]][2] == 0){
				$logsPerPlayer[$d["playerId"]][2] ++;
			}
			for($i=1;$i<=7;$i++){
				if($daysDiff > 7*$i && $daysDiff <= 7*($i + 1) && $logsPerPlayer[$d["playerId"]][$i+2] == 0){
					$logsPerPlayer[$d["playerId"]][$i+2] ++;
				}
			}
			if($daysDiff > 7*8 && $daysDiff <= 31*3 && $logsPerPlayer[$d["playerId"]][10] == 0){
				$logsPerPlayer[$d["playerId"]][10] ++;
			}
			if($daysDiff > 31*3 && $logsPerPlayer[$d["playerId"]][11] == 0){
				$logsPerPlayer[$d["playerId"]][11] ++;
			}
		}
		$nbPlayers = count($logsPerPlayer);
		$retention = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		foreach($logsPerPlayer as $p) {
			$retention = array_map(function () {
	    	return array_sum(func_get_args());
			}, $p, $retention);
		}
		foreach($retention as $i => $r){
			$retention[$i] = round($r/$nbPlayers * 100,1);
		}
		array_unshift($retention , 100);
		array_unshift($retention , $nbPlayers);
		echo json_encode($retention);
	} elseif($_POST["getDashboard"] == "userNetFlow"){
		/* FORMER ANALYSIS
		$data = $manager->WonBetween($periodStart, $periodEnd);
		$nbPlayersWon = count($data);
		$data = $manager->WonButNotKeptBetween($periodStart, $periodEnd);
		$nbPlayersWonButNotKept = count($data);
		$data = $manager->LostBetween($periodStart, $periodEnd);
		$nbPlayersLost = count($data);
		$nbPlayers = $nbPlayersWon + $nbPlayersWonButNotKept + $nbPlayersLost;
		$userNetFlow = array($nbPlayers, round($nbPlayersWon/$nbPlayers*100, 1), round($nbPlayersWonButNotKept/$nbPlayers*100, 1), round($nbPlayersLost/$nbPlayers*100, 1));
		echo json_encode($userNetFlow); */
		$maxInactivityPeriod = $manager->maxSessionInterval();
		$activeUsers = $manager->activeUsers($periodStart, $maxInactivityPeriod);
		$churn = $manager->churn($periodStart, $periodEnd, $maxInactivityPeriod);
		$data = $manager->SignUpBetween($periodStart, $periodEnd);
		$nbPlayers = count($data);
		$activated = 0;
		foreach($data as $d) {
			if($d["playerTutorial"] == "fini"){
				$activated ++;
			}
		}
		echo json_encode(array($maxInactivityPeriod, $activeUsers, -$churn, $activated));
	} elseif($_POST["getDashboard"] == "conversion"){
		$data = $manager->SignUpBetween($periodStart, $periodEnd);
		$nbPlayers = count($data);
		$reconnected = $limitReached = $passBought = 0;
		foreach($data as $d) {
			if(substr($d["playerLastLog"], 0, 10) != substr($d["playerSignUp"], 0, 10)){
				$reconnected ++;
			}
			if((int) $d["playerLevel"] >= 5){
				$limitReached ++;
			}
			if($d["playerId"] != null){
				$passBought ++;
			}
		}
		$conversion = array($nbPlayers, 100, round($reconnected/$nbPlayers*100, 1), round($limitReached/$nbPlayers*100, 1), round($passBought/$nbPlayers*100, 1));
		echo json_encode($conversion);
	} elseif($_POST["getDashboard"] == "AARRR"){
		$data = $manager->SignUpBetween($periodStart, $periodEnd);
		$nbPlayers = count($data);
		$activated = 0;
		foreach($data as $d) {
			if($d["playerTutorial"] == "fini"){
				$activated ++;
			}
		}
		$activeUsers = $manager->activeUsers($periodStart, $periodEnd);
		$sponsors = $manager->playersWhoFirstSponsoredBetween($periodStart, $periodEnd);
		$purchased = $abonnements_manager->purchasedBetween($periodStart, $periodEnd);
		$aarrrData = array($nbPlayers, $activated, $activeUsers, $sponsors, $purchased);
		echo json_encode($aarrrData);
	} elseif($_POST["getDashboard"] == "acquisition"){
		$data = $manager->acquisitionSplit($periodStart, $periodEnd);
		$results = array();
		$results["classroom"] = $results["facebook"] = $results["twitter"] = $results["sponsor"] = $results["noInvitation"] = $results["others"] = 0;
		foreach($data as $d) {
			if($d["categorySponsor"] == "inscription"){
				$results["sponsor"] ++;
			} elseif($d["categorySponsor"] == "Classe d'un prof" || $d["idTeacher"] != null){
				$results["classroom"] ++;
			} elseif($d["categorySponsor"] == null){
				$results["noInvitation"] ++;
			} elseif($d["categorySponsor"] == "Facebook"){
				$results["facebook"] ++;
			} elseif($d["categorySponsor"] == "Twitter"){
				$results["twitter"] ++;
			} else {
				$results["others"] ++;
			}
		}
		echo json_encode(array($results["classroom"], $results["facebook"], $results["twitter"], $results["sponsor"], $results["noInvitation"], $results["others"]));
	}
}

if(isset($_POST["classroomId"]) && isset($_POST["classroomName"]) && isset($_POST["classroomLevel"]) && isset($_POST["maxStudents"])){
	$classroomId = (int) $_POST["classroomId"];
	if($classrooms_manager->exists($classroomId)) {
		$classroom = $classrooms_manager->getById($classroomId);
		$classroom->hydrate(array(
			"name" => $_POST["classroomName"],
			"level" => $_POST["classroomLevel"],
			"maxStudents" => (int) $_POST["maxStudents"]
		));
		$classrooms_manager->update($classroom);
	} else {
		$classroom = new Classroom(array(
			"idTeacher" => $joueur->id(),
			"name" => $_POST["classroomName"],
			"level" => $_POST["classroomLevel"],
			"maxStudents" => (int) $_POST["maxStudents"]
		));
		$classrooms_manager->add($classroom);
	}
	$classrooms = $classrooms_manager->getClassroomsTeacher($joueur);
	$classroomsArray = array();
	foreach($classrooms as $c) {
		$arrayTemp = array(
			"id" => $c->id(),
			"idTeacher" => $c->idTeacher(),
			"idStudents" => $c->idStudents(),
			"name" => $c->name(),
			"level" => $c->level(),
			"nbStudents" => $c->nbStudents(),
			"maxStudents" => $c->maxStudents(),
			"code" => $c->code(),
			"dateCreation" => $c->dateCreation()
		);
		$classroomsArray [] = $arrayTemp;
	}
	echo json_encode($classroomsArray);
}

if(isset($_POST["deleteClass"])){
	$classroom = $classrooms_manager->getById((int) $_POST["deleteClass"]);
	$timeslots_manager->deleteClass($classroom);
	$classrooms_manager->delete($classroom);
	$classrooms = $classrooms_manager->getClassroomsTeacher($joueur);
	$classroomsArray = array();
	foreach($classrooms as $c) {
		$arrayTemp = array(
			"id" => $c->id(),
			"idTeacher" => $c->idTeacher(),
			"idStudents" => $c->idStudents(),
			"name" => $c->name(),
			"level" => $c->level(),
			"nbStudents" => $c->nbStudents(),
			"maxStudents" => $c->maxStudents(),
			"code" => $c->code(),
			"dateCreation" => $c->dateCreation()
		);
		$classroomsArray [] = $arrayTemp;
	}
	echo json_encode($classroomsArray);
}

if(isset($_POST["classroomJoinCode"])){
	if($classrooms_manager->exists($_POST["classroomJoinCode"])){
		$classroom = $classrooms_manager->getByCode($_POST["classroomJoinCode"]);
		if($classroom->nbStudents() < $classroom->maxStudents()){
			$classroom->addStudent($joueur);
			$classrooms_manager->update($classroom);
			$teacher = $manager->get($classroom->idTeacher());
			$joueur->setCollege($teacher->college());
			$joueur->setDepartement($teacher->departement());
			$joueur->setClasse($classroom->level());
			$manager->update($joueur);
			$teacherTemp = $manager->get($classroom->idTeacher());
			echo('<div class="ib l100 mh1 p4 g pfun">'.$classroom->name().' du Collège '.$joueur->college().' ('.$joueur->departement().')</div>');
			echo('<div class="ib l100 p3 g pfun"><a href="/app/controllers/profil.php?id='.$teacherTemp->id().'"><img class="l8 team_portraits" title="Professeur : '.$teacherTemp->pseudo().' - Niv.'.$teacherTemp->niveau().'" src="'.$teacherTemp->full_portrait().'" /></a></div>');
			echo('<div class="ib l100 p3 g pfun mh1 mb1">Joueurs de ta classe</div>');
			echo('<div class="bordure l95 centre align_centre">');
			    echo('<div class="entetes_scroll">');
			    	echo('<div class="ligne_scroll p1">');
			            echo('<span class="l10">Portrait</span>');
			            echo('<span class="l23">Pseudo</span>');
									echo('<span class="l23">Prénom</span>');
			            echo('<span class="l17">Niveau</span>');
			            echo('<span class="l17">Prestige</span>');
			      echo('</div>');
			    echo('</div>');
			    echo('<div id="listClassrooms" class="corps_scroll align_centre scroll_moyenPlus p2">');
					foreach($classroom->idStudents() as $idStudent) {
						$studentTemp = $manager->get($idStudent);
						echo('<a href="/app/controllers/profil.php?id='.$idStudent.'">');
						echo('<div class="ligne_scroll students">');
						 echo('<span class="l10"><img class="l60 team_portraits" title="'.$studentTemp->pseudo().' - Niv.'.$studentTemp->niveau().'" src="'.$studentTemp->full_portrait().'" /></span>');
						 echo('<span class="l23">'.$studentTemp->pseudo().'</span>');
						 echo('<span class="l23">'.$studentTemp->prenom().'</span>');
						 echo('<span class="l17">'.$studentTemp->niveau().'</span>');
						 echo('<span class="l17">'.$studentTemp->prestige().'</span>');
					 echo('</div>');
				 	 echo('</a>');
				   }
			    echo('</div>');
			echo('</div>');
		} else {
			echo "TooManySubscriptions";
		}
	} else {
		echo "DoesNotExist";
	}
}

if(isset($_POST["timeslotId"]) && isset($_POST["idClassroom"]) && isset($_POST["startTime"]) && isset($_POST["endTime"]) && isset($_POST["notion1"]) && isset($_POST["notion2"]) && isset($_POST["notion3"]) ){
	$timeslotId = (int) $_POST["timeslotId"];
	if($timeslots_manager->exists($timeslotId)) {
		$timeslot = $timeslots_manager->getById($timeslotId);
		$timeslot->hydrate(array(
			"idClassroom" => (int) $_POST["idClassroom"],
			"startTime" => $_POST["startTime"],
			"endTime" => $_POST["endTime"],
			"notion1" => $_POST["notion1"],
			"notion2" => $_POST["notion2"],
			"notion3" => $_POST["notion3"]
		));
		$timeslots_manager->update($timeslot);
		$subject = "Créneau Navadra modifié";
	} else {
		$timeslot = new Timeslot(array(
			"idTeacher" => $joueur->id(),
			"idClassroom" => (int) $_POST["idClassroom"],
			"startTime" => $_POST["startTime"],
			"endTime" => $_POST["endTime"],
			"notion1" => $_POST["notion1"],
			"notion2" => $_POST["notion2"],
			"notion3" => $_POST["notion3"]
		));
		$timeslots_manager->add($timeslot);
		$subject = "Nouveau créneau Navadra";
	}
	$classroom = $classrooms_manager->getByCode($_POST["idClassroom"]);
	$day = "Le ".strftime('%d/%m/%Y', strtotime($timeslot->startTime()));
	$hour = "De ".strftime('%H:%M', strtotime($timeslot->startTime()))." à ".strftime('%H:%M', strtotime($timeslot->endTime()));
	//$joueur->send_email("89010", "Navadra", $subject, "team@navadra.com", $params = '{ "Sujet": "'.$subject.'", "Email": "'.$joueur->email().'", "Pseudo": "'.$joueur->pseudo().'", "College": "'.$joueur->college().'", "Departement": "'.$joueur->departement().'", "Jour": "'.$day.'", "Horaire": "'.$hour.'", "Classe": "'.$classroom->name().'" }');
	$timeslots = $timeslots_manager->getTeacherNotPast($joueur);
	$timeslotsArray = array();
	foreach($timeslots as $t) {
		$day = strftime('%d/%m/%Y', strtotime($t->startTime()));
		$hour = "De ".strftime('%H:%M', strtotime($t->startTime()))." à ".strftime('%H:%M', strtotime($t->endTime()));
		$challenge1 = new Challenge(array("name" => $t->notion1()));
		$challenge1Name = $challenge1->notion();
		$challenge2 = new Challenge(array("name" => $t->notion2()));
		$challenge2Name = $challenge2->notion();
		$challenge3 = new Challenge(array("name" => $t->notion3()));
		$challenge3Name = $challenge3->notion();
		$arrayTemp = array(
			"id" => $t->id(),
			"idTeacher" => $t->idTeacher(),
			"idClassroom" => $t->idClassroom(),
			"day" => $day,
			"hour" => $hour,
			"notion1" => $challenge1Name,
			"notion2" => $challenge2Name,
			"notion3" => $challenge3Name,
			"dateCreation" => $t->dateCreation()
		);
		$timeslotsArray [] = $arrayTemp;
	}
	echo json_encode($timeslotsArray);
}

if(isset($_POST["deleteTimeslot"])){
	$timeslot = $timeslots_manager->getById((int) $_POST["deleteTimeslot"]);
	$timeslots_manager->delete($timeslot);
	$timeslots = $timeslots_manager->getTeacherNotPast($joueur);
	$timeslotsArray = array();
	foreach($timeslots as $t) {
		$day = strftime('%d/%m/%Y', strtotime($t->startTime()));
		$hour = "De ".strftime('%H:%M', strtotime($t->startTime()))." à ".strftime('%H:%M', strtotime($t->endTime()));
		$challenge1 = new Challenge(array("name" => $t->notion1()));
		$challenge1Name = $challenge1->notion();
		$challenge2 = new Challenge(array("name" => $t->notion2()));
		$challenge2Name = $challenge2->notion();
		$challenge3 = new Challenge(array("name" => $t->notion3()));
		$challenge3Name = $challenge3->notion();
		$arrayTemp = array(
			"id" => $t->id(),
			"idTeacher" => $t->idTeacher(),
			"idClassroom" => $t->idClassroom(),
			"day" => $day,
			"hour" => $hour,
			"notion1" => $challenge1Name,
			"notion2" => $challenge2Name,
			"notion3" => $challenge3Name,
			"dateCreation" => $t->dateCreation()
		);
		$timeslotsArray [] = $arrayTemp;
	}
	echo json_encode($timeslotsArray);
}

if(isset($_POST["progressClassroom"])){
	if($classrooms_manager->exists((int) $_POST["progressClassroom"])){
		$classroom = $classrooms_manager->getById((int) $_POST["progressClassroom"]);
		$challengesProgress = $classrooms_manager->classroomProgressByChallenge($classroom);
		$challenges = $challenges_manager->get_challenges_by_element($joueur);
		$challenges = array_merge($challenges[0], $challenges[1], $challenges[2], $challenges[3]);
		$students = $classrooms_manager->classroomStudents($classroom);
		echo('<table id="tableProgress">');
			echo('<thead>');
				echo('<tr>');
					echo('<th class="titles" title="Si l\'élève n\'a pas renseigné son nom et son prénom, son pseudo figure à la place.">Elèves</th>');
						foreach($challenges as $challenge) {
							echo('<th class="titles" title="'.$challenge->title_element().'" element="'.$challenge->getElement().'" notion="'.$challenge->getName().'">'.$challenge->notion().'</th>');
						}
				echo('</tr>');
			echo('</thead>');
			echo('<tbody>');
				foreach($students as $student){
					$student = explode(";", $student);
					$idStudent = $student[0];
					$student = $student[1];
					echo('<tr id="student'.$idStudent.'">');
						echo ('<td class="nameStudent"><img dataName="'.$student.'" dataId="'.$idStudent.'" class="ib img_20 md2 titles deleteStudent" title="Supprimer l\'élève de cette classe" src="/webroot/img/icones/refuser.png" /><span class="titles ib" title="Si l\'élève n\'a pas renseigné son nom et son prénom, son pseudo figure à la place.">'.$student.'</span></td>');
					foreach($challenges as $challenge) {
						if(isset($challengesProgress[$student."_".$challenge->getName()])){
							$data = explode("_", $challengesProgress[$student."_".$challenge->getName()]);
							if($data[2] > 0){
								echo('<td class="mastery'.$data[0].' titles" title="Pratiqué '.$data[2].' fois (dernière fois il y a '.$data[1].')"><img class="img_20" src="/webroot/img/icones/notebook.png" /> '.$data[2].' - <img class="img_20" src="/webroot/img/icones/calendar.png" /> '.$data[1].'</td>');
							} else {
								echo('<td class="mastery0 titles" title="Pas encore pratiqué."> - </td>');
							}
						} else {
							echo('<td class="mastery0 titles" title="Pas encore pratiqué."> - </td>');
						}
					}
				echo('</tr>');
				}
			echo('</tbody>');
		echo('</table>');
	}
}

if(isset($_POST["deleteStudent"])){
	if($manager->exists((int) $_POST["deleteStudent"])){
		$student = $manager->get((int) $_POST["deleteStudent"]);
		$classroom = $classrooms_manager->getClassroomStudent($student);
		$classroom->removeStudent($student);
		$classrooms_manager->update($classroom);
	}
}

if(isset($_POST["newCollegeName"]) && isset($_POST["newCollegeDepartement"])){
	$q = $db_RO->prepare('SELECT * FROM colleges');
	$q->execute();
	$q1 = $db_RW->prepare('INSERT INTO colleges (departement, nom) VALUES(:departement, :nom)');
	$q1->execute(array(
		'departement' => $_POST["newCollegeDepartement"],
		'nom' => $_POST["newCollegeName"]
	));
	echo "collegeAdded";
}



//For DEMO purpose only
/*
if($server == "localhost" && $joueur->id() == 48){

	$levelMath = (int)$_GET["level"];
	switch($levelMath){
		case 1 :
			echo "<br><br>Niveau Mathematique a 1. C'est parti !";
			break;
		case 2 :
			echo "<br><br>Niveau Mathematique a 2. Ca va commencer a piquer.";
			break;
		case 3 :
			echo "<br><br>Niveau Mathematique a 3. Tu n'as peur de rien toi !";
			break;
		case 4 :
			echo "<br><br>Niveau Mathematique a 4. Niveau 4 serieux ? Bon courage...";
			break;
		case 5 :
			echo "<br><br>Niveau Mathematique a 5. On n'a encore jamais vu quelqu'un survivre a un monstre au niveau 5, hehe...";
			break;
		default :
			echo "<br><br>Erreur : Niveau Mathematique inconnu au bataillon !";
			break;
	}

	//FIRE
	$challenge = $challenges_manager->get_by_id(117);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);
	$challenge = $challenges_manager->get_by_id(118);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);
	$challenge = $challenges_manager->get_by_id(119);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);

	//WATER
	$challenge = $challenges_manager->get_by_id(114);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);
	$challenge = $challenges_manager->get_by_id(115);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);
	$challenge = $challenges_manager->get_by_id(116);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);

	//WIND
	$challenge = $challenges_manager->get_by_id(95);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);
	$challenge = $challenges_manager->get_by_id(107);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);
	$challenge = $challenges_manager->get_by_id(108);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);

	//EARTH
	$challenge = $challenges_manager->get_by_id(111);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);
	$challenge = $challenges_manager->get_by_id(112);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);
	$challenge = $challenges_manager->get_by_id(113);
	$challenge->setCurrent_level($levelMath);
	$challenge->setInitial_level($levelMath);
	$challenges_manager->save_or_update($challenge);


	$challenges_manager->delete_challenges($joueur);

	$monstres_manager->delete_all_today($joueur);

	$monstersRequired = 3;
	$levelMonsters = $joueur->niveau() +1;
	for($i=1; $i <= $monstersRequired; $i ++){
		$monstres_manager->nouveau_monstre_solo($joueur, $levelMonsters);
	}

	//Reset caracs
	$joueur->setNiveau(1);
	$joueur->setPyrs_feu(0);
	$joueur->setPyrs_eau(0);
	$joueur->setPyrs_vent(0);
	$joueur->setPyrs_terre(0);
	$joueur->setXp(82);
	$joueur->setPrestige(17);
	$joueur->setStock_challenges(3);
	$joueur->setNb_combats(5);
	$joueur->setDernier_log("2016-10-11 15:23:01");
	$manager->update($joueur);
} */
