<?php
require("include_path.php");

$joueur = $_SESSION["joueur"];
if (!isset($joueur)) {
	$fictive_player = new Joueur (array("Pseudo" => "Fictif"));
	header("Location: ".$fictive_player->rootLink());
	exit;
}

$joueur = $manager->get($joueur->id()); //Permet de prendre en comptes les actions des autres joueurs

//$etapes_tuto = ["cinematique_0", "index_1", "combattre_2", "index_3", "accueil_defi_4", "fin_defi_5", "grimoire_6", "grimoire_7", "index_8", "prepa_combats_9", "combats_decks_10", "combattre_11", "index_12", "fini"];

if(isset($_POST["id_histoire"])) //Script pour mettre à jour les histoires vues du joueur
{
	$histoires_vues = $joueur->histoires_vues();
	if($histoires_vues == "")
		{$histoires_vues = array($_POST["id_histoire"]);}
	elseif(!in_array($_POST["id_histoire"], $histoires_vues)) //Si l'histoire n'est pas déjà dans les histoires vues du joueur
		{$histoires_vues[] = $_POST["id_histoire"];}
	$joueur->setHistoires_vues($histoires_vues);
	$manager->update($joueur);
}

if(isset($_POST["id_contact"])) //Script pour ajouter un contact
{
	$contacts = $joueur->contacts();
	if($contacts == "")
	{
		$contacts = array($_POST["id_contact"]);
	}
	else
	{
		if(array_search($_POST["id_contact"], $contacts) === false) //évite les ajouts en double
		{
			$contacts[] = $_POST["id_contact"];
		}
	}
	$joueur->setContacts($contacts);

  // ***********************************************
	// ***** ACHIEVEMENTS
  $contact_nbr = sizeof($contacts);
  // TODO later (when we will reset all players): Replace '>=' with '=='
  if ( $contact_nbr >=20 ) $achievement_manager->add( $joueur->id(), 48 );
  else if ( $contact_nbr >=15 ) $achievement_manager->add( $joueur->id(), 47 );
  else if ( $contact_nbr >=10 ) $achievement_manager->add( $joueur->id(), 46 );
  else if ( $contact_nbr >=5 ) $achievement_manager->add( $joueur->id(), 45 );
  else if ( $contact_nbr >=3 ) $achievement_manager->add( $joueur->id(), 44 );

	$manager->update($joueur);
}

if(isset($_POST["id_contact_suppr"])) //Script pour supprimer un contact
{
	$contacts = $joueur->contacts();
	array_splice($contacts, array_search($_POST["id_contact_suppr"], $contacts), 1);
	$joueur->setContacts($contacts);
	$manager->update($joueur);
}

if(isset($_POST["id_conv"])) //Script pour considérer une conversation comme lue
{
	$messages_manager->update($_POST["id_conv"], $joueur);
}

if(isset($_POST["id_monstre"]) && isset($_POST["id_invited_players"]))
{
	$combat = $combats_manager->get($joueur->id(), (int)$_POST["id_monstre"]);
	$monstre = $monstres_manager->get_id((int)$_POST["id_monstre"]);
	$invited_players = json_decode($_POST["id_invited_players"], true); //True to convert into associative array
	if(sizeof($combat->ordre()) < 12 && $combat->deroulement() == "") //Si il y a moins de 12 contacts et que le combat n'a pas déjà commencé
	{
		$id_invites = $combat->id_invites();
		$ordre = $combat->ordre();
		$date_invitations = $combat->date_invitations();
		//We remove players not selected
		foreach($ordre as $existing_player){
			if($existing_player != $joueur->id() && !in_array($existing_player, $invited_players)){
				array_splice($ordre, array_search($existing_player, $ordre), 1);
				array_splice($id_invites, array_search($existing_player, $id_invites), 1);
				unset($date_invitations[$existing_player]);
			}
		}
		//We add new players
		foreach($invited_players as $player_id){
			if(!in_array($player_id, $ordre)){
				$ordre[] = (int) $player_id;
				if($id_invites == ""){
					$id_invites = array((int) $player_id);
				} else {
					$id_invites[] = (int) $player_id;
				}
				setlocale(LC_TIME, 'fr_FR');
				date_default_timezone_set('Europe/Paris');
				$date = strftime('%Y-%m-%d %H:%M:%S',time());
				if($date_invitations == ""){
					$date_invitations = array((int) $player_id => $date);
				} else {
					$date_invitations[(int) $player_id] =  $date;
				}
			}
		}
		$combat->setId_invites($id_invites);
		$combat->setOrdre($ordre);
		$combat->setDate_invitations($date_invitations);

		$combats_manager->update($combat);


        foreach($ordre as $id_player){
			$combattant = $manager->get($id_player);
            echo '<div class="ligne_scroll" id="'.$combattant->id().'">';
            echo '<span class="l30">'.$combattant->pseudo().'</span>';
            echo '<span class="l15"><img class="neutre img_20" src="'.$combattant->element_img().'"/></span>';
            echo '<span class="l15">'.$combattant->niveau().'</span>';
            if(in_array($combattant->id(), $combat->id_prets())){
				echo('<span class="l15"><img id="etat_'.$combattant->id().'" class="neutre pret img_20" src="/webroot/img/icones/check.png"/></span>');
			} else {
				echo('<span class="l15"><img id="etat_'.$combattant->id().'" class="neutre pret img_20" src="/webroot/img/icones/sablier2.png"/></span>');
			}
			if($joueur->id() == $combat->id_orga() && $joueur->id() != $combattant->id() && $combat->deroulement() == ""){
				echo('<span class="l20"><img id="suppr_'.$combattant->id().'" class="suppr img_20" src="/webroot/img/icones/faux.png"/></span>');
			} elseif($joueur->id() == $combat->id_orga() && $joueur->id() == $combattant->id()) {
				echo('<span class="l20"></span>');
			} else {
				if(in_array($combattant->id(), $combat->id_prets())) {
					 echo('<span class="l20"><img id="participe_'.$combattant->id().'" class="neutre img_20" src="/webroot/img/icones/check.png"/></span>');
				} elseif($combattant->id() != $joueur->id()) {
					 echo('<span class="l20"><img id="participe_'.$combattant->id().'" class="neutre img_20" src="/webroot/img/icones/sablier2.png"/></span>');
				} else {
					 echo('<span class="ib l20"><img id="accepter_'.$combattant->id().'" class="cliquable accepter img_20" src="/webroot/img/icones/check.png"/><img id="refuser_'.$combattant->id().'" class="cliquable refuser img_20" src="/webroot/img/icones/refuser.png"/></span>');
				}
			}
            echo('<span id="sexe_'.$combattant->id().'" class="cache">'.$combattant->sexe().'</span>');
            echo('</div>');
       }
		 $timeSlot = $classrooms_manager->hasTimeSlot($joueur);
	   echo('<span id="prestigeEarned" class="cache">'.$monstre->gain_prestige(sizeof($ordre), $timeSlot).'</span>');
	   echo('<span id="prestigeLost" class="cache">'.$monstre->perte_prestige(sizeof($ordre), $timeSlot).'</span>');
	}
}

if(isset($_POST["id_monstre"]) && isset($_POST["id_combattant_suppr"])) //Script pour supprimer un combattant d'un combat
{
	$combat = $combats_manager->get($joueur->id(), (int)$_POST["id_monstre"]);
	if($combat->deroulement() == "") //Si et seulement si le combat n'a pas déjà commencé
	{
		$id_invites = $combat->id_invites();
		array_splice($id_invites , array_search((int) $_POST["id_combattant_suppr"], $id_invites, true), 1);
		$combat->setId_invites($id_invites);

		$ordre = $combat->ordre();
		array_splice($ordre , array_search((int) $_POST["id_combattant_suppr"], $ordre, true), 1);
		$combat->setOrdre($ordre);

		$date_invitations = $combat->date_invitations();
		unset($date_invitations[(int) $_POST["id_combattant_suppr"]]);
		$combat->setDate_invitations($date_invitations);

		$id_prets = $combat->id_prets();
		if(in_array((int)$_POST["id_combattant_suppr"], $id_prets))
		{
			array_splice($id_prets, array_search((int)$_POST["id_combattant_suppr"], $id_prets, true), 1);
			$combat->setId_prets($id_prets);
		}

		$combats_manager->update($combat);

		$monstre = $monstres_manager->get_id((int)$_POST["id_monstre"]);
		$timeslot = $classrooms_manager->hasTimeSlot($joueur);
		echo($monstre->gain_prestige(sizeof($ordre), $timeslot).",".$monstre->perte_prestige(sizeof($ordre), $timeslot)); //Permet d'afficher ça dans la vue
	}
}

if(isset($_POST["id_monstre"]) && isset($_POST["id_combattant_monter"])) //Script pour monter d'un cran l'ordre d'un invité
{
	$combat = $combats_manager->get($joueur->id(), (int)$_POST["id_monstre"]);
	if($combat->deroulement() == "") //Si le combat n'a pas déjà commencé
	{
		$ordre = $combat->ordre();
		$position = array_search((int) $_POST["id_combattant_monter"], $ordre, true);
		if($position > 0)
		{
			$position--;
			$nouvel_ordre = array();
			for($i=0;$i<$position;$i++) //On recopie le début du tableau
			{
				$nouvel_ordre[] = $ordre[$i];
			}
			$nouvel_ordre[] = $ordre[$position+1]; //On monte d'un cran la valeur d'intérêt
			$nouvel_ordre[] = $ordre[$position]; //On descend d'un cran l'ancienne valeur
			for($i=$position+2;$i<sizeof($ordre);$i++) //On recopie la fin du tableau
			{
				$nouvel_ordre[] = $ordre[$i];
			}
		}
		$combat->setOrdre($nouvel_ordre);
		$combats_manager->update($combat);
		if($joueur->id() == $combat->prochain_a_jouer())
		{
			echo "oui";
		}
	}
}

if(isset($_POST["id_monstre"]) && isset($_POST["id_combattant_descendre"])) //Script pour descendre d'un cran l'ordre d'un invité
{
	$combat = $combats_manager->get($joueur->id(), (int)$_POST["id_monstre"]);
	if($combat->deroulement() == "") //Si le combat n'a pas déjà commencé
	{
		$ordre = $combat->ordre();
		$position = array_search((int) $_POST["id_combattant_descendre"], $ordre, true);
		if($position < sizeof($ordre) - 1)
		{
			$position++;
			$nouvel_ordre = array();
			for($i=0;$i<=$position-2;$i++) //On recopie le début du tableau
			{
				$nouvel_ordre[] = $ordre[$i];
			}
			$nouvel_ordre[] = $ordre[$position]; //On monte d'un cran la valeur d'intérêt
			$nouvel_ordre[] = $ordre[$position-1]; //On descend d'un cran l'ancienne valeur
			for($i=$position+1;$i<sizeof($ordre);$i++) //On recopie la fin du tableau
			{
				$nouvel_ordre[] = $ordre[$i];
			}
		}
		$combat->setOrdre($nouvel_ordre);
		$combats_manager->update($combat);
		if($joueur->id() == $combat->prochain_a_jouer())
		{
			echo "oui";
		}
	}
}

if(isset($_POST["id_participer"])) //Script pour qu'un invité participe à un combat
{
	$combat = $combats_manager->get_id((int)$_POST["id_participer"]);
	if( in_array($joueur->id(), $combat->id_invites()) ){
		$prets = $combat->id_prets();
		$prets[] = $joueur->id();
		$combat->setId_prets($prets);

		$monstre = $monstres_manager->get_id($combat->id_monstre());
		if($monstre->nb_chasseurs() > 1){ //Gives more time if one player of the fight answers
			setlocale(LC_TIME, 'fr_FR');
			date_default_timezone_set('Europe/Paris');
			$creation_date = min(time(), strtotime($monstre->date_creation()) + 24*60*60);
			$creation_date = strftime('%Y-%m-%d %H:%M:%S',$creation_date);
			$monstre->setDate_creation($creation_date);
			$monstres_manager->update($monstre);
		}

		$combats_manager->update($combat);
		if( $combat->tous_prets() && $joueur->id() == $combat->prochain_a_jouer() ){
			echo "yourTurn";
		} elseif( $combat->tous_prets() && $joueur->id() != $combat->prochain_a_jouer() ){
			echo "prets";
		}
	}
}

if(isset($_POST["id_refuser"])){ //Script pour qu'un invité refuse un combat
	$combat = $combats_manager->get_id((int)$_POST["id_refuser"]);
	if( in_array($joueur->id(), $combat->id_invites()) ){
		$id_invites = $combat->id_invites();
		array_splice($id_invites , array_search($joueur->id(), $id_invites, true), 1);
		$combat->setId_invites($id_invites);

		$ordre = $combat->ordre();
		array_splice($ordre , array_search($joueur->id(), $ordre, true), 1);
		$combat->setOrdre($ordre);

		$date_invitations = $combat->date_invitations();
		unset($date_invitations[$joueur->id()]);
		$combat->setDate_invitations($date_invitations);

		$combats_manager->update($combat);
		if($combat->tous_prets())
			{echo "prets";}
	}
}

if(isset($_POST["id_monstre_participer"])) //Autre script pour qu'un invité participe à un combat
{
		$combat = $combats_manager->get($joueur->id(), (int)$_POST["id_monstre_participer"]);

		$prets = $combat->id_prets();
		$prets[] = $joueur->id();
		$combat->setId_prets($prets);

		$monstre = $monstres_manager->get_id($combat->id_monstre());
		if($monstre->nb_chasseurs() > 1){ //Gives more time if one player of the fight answers
			setlocale(LC_TIME, 'fr_FR');
			date_default_timezone_set('Europe/Paris');
			$creation_date = min(time(), strtotime($monstre->date_creation()) + 24*60*60);
			$creation_date = strftime('%Y-%m-%d %H:%M:%S',$creation_date);
			$monstre->setDate_creation($creation_date);
			$monstres_manager->update($monstre);
		}

		$combats_manager->update($combat);
}

if(isset($_POST["id_monstre_refuser"])) //Autre script pour qu'un invité refuse un combat
{
	$combat = $combats_manager->get($joueur->id(), (int)$_POST["id_monstre_refuser"]);

	$id_invites = $combat->id_invites();
	array_splice($id_invites , array_search($joueur->id(), $id_invites, true), 1);
	$combat->setId_invites($id_invites);

	$ordre = $combat->ordre();
	array_splice($ordre , array_search($joueur->id(), $ordre, true), 1);
	$combat->setOrdre($ordre);

	$date_invitations = $combat->date_invitations();
	unset($date_invitations[$joueur->id()]);
	$combat->setDate_invitations($date_invitations);

	$combats_manager->update($combat);
}

if(isset($_POST["cinematique_vue"])) //Script pour considérer la cinématique d'intro comme vue
{
	$joueur->setTuto("index_1");
	if($_POST["cinematique_vue"] == "oui") //Seulement si le joueur a attendu la fin de la vidéo
	{
		$joueur->setHistoires_vues(array("0_".$joueur->element()));
	}
	$manager->update($joueur);
}

//if(isset($_POST["prochaine_etape_tuto"])) //Script pour passer à la prochaine étape du tuto
//{
////	$joueur->setTuto($_POST["prochaine_etape_tuto"]);
//
//	$num_etape_actuelle = array_search( $joueur->tuto() , $etapes_tuto); // Index of the current tuto
//	$prochaine_etape = $etapes_tuto[$num_etape_actuelle + 1];
//	$joueur->setTuto($prochaine_etape);
//
//	if($prochaine_etape == "fini") {
//		setlocale(LC_TIME, 'fr_FR');
//		date_default_timezone_set('Europe/Paris');
//		$date = strftime('%Y-%m-%d %H:%M:%S',time());
//		$joueur->setDate_fin_tuto($date);
//		$joueur->setStock_challenges(2); //2 other challenges
//	  $sum_challenges = 2; //Actualization for the view
//		$joueur->send_email("34640", "Jérémie", "Navadra, l'aventure commence ici !", $joueur->email(), $params = '{ "Prenom": "'.$joueur->prenom().'" }');
//	}
//
//	$manager->update($joueur);
//}

if(isset($_POST["tour"]) && isset($_POST["reussite"])) //Script pour récupérer les caractéristiques des sorts du joueur pour un tour et un nb de réussite donné
{
	$tour = (int) $_POST["tour"];
	$reussite = json_decode($_POST["reussite"]);
	$element_monstre = $_POST["element_monstre"];
	$sorts = $sorts_manager->get($joueur);
	$sorts_json = array();
	$i = 0;
	foreach($sorts as $sort)
	{
		$sorts_json[] = array(
			"num" => $sort->num(),
			"nom" => $sort->nom(),
			"niveau" => $sort->niveau(),
			"element" => $sort->element1(),
			"effet" => $sort->effet_elem($joueur, $tour, $reussite[$i], $element_monstre)
			);
		$i++;
	}
	echo json_encode($sorts_json);
}

if(isset($_POST["commencer_combat"])) //Script pour commencer un combat
{
	$combat = $combats_manager->get_id((int)$_POST["commencer_combat"]);
	$_SESSION["combat_en_cours"] = $combat->id();
	//On effectue la copie du joueur actif
	$chasseurs = $combat->copies_joueurs();
	$copie_joueur = clone $joueur; //On enlève les champs avec caractères spéciaux pour éviter les problèmes de serialization
	$copie_joueur->setNom("");
	$copie_joueur->setPrenom("");
	$copie_joueur->setCollege("");
	if($chasseurs == "") {
		$chasseurs = array($copie_joueur);
	}
	else {
		$chasseurs[] = $copie_joueur;
	}
	$combat->setCopies_joueurs($chasseurs);

	//La copie du monstre a déjà été effectuée donc on la récupère et on supprime le monstre s'il était encore présent
	$monstre = $combat->copie_monstre();
	if($combat->nb_chasseurs_ko() == $combat->nb_chasseurs() - 1  && $monstres_manager->exists($monstre->id())) {
		$monstre = $monstres_manager->get_id($combat->id_monstre());
		$monstre->setDead(1);
		$monstres_manager->update($monstre);
	}

	$vu = $combat->combat_vu($joueur); //On considère le combat comme vu pour ce joueur
	$joueur->setNb_combats($joueur->nb_combats() - 1); //On diminue le nbre de combats autorisés pour aujourd'hui

	$manager->update($joueur);
	$combats_manager->update($combat);
}

if(isset($_POST["combat_en_cours"])) //Script pour récupérer le joueur suivant d'un combat en cours
{
	$id_combat = (int) $_POST["combat_en_cours"];
	$combat = $combats_manager->get_id($id_combat);
	$nb_chasseurs_ko = $combat->nb_chasseurs_ko();
	$ordre_joueur = $combat->ordre();
	$id_prochain_joueur = $ordre_joueur[$nb_chasseurs_ko];
	$prochain_joueur = $manager->get($id_prochain_joueur);
	$monstre = $combat->copie_monstre();
	/* Pas besoin de toutes ces infos mais pourrait resservir donc pas supprimer
	//On encode en utf8 les champs de texte pour ne pas faire planter encodage json
	$prochain_joueur->setNom(utf8_encode($prochain_joueur->nom()));
	$prochain_joueur->setPrenom(utf8_encode($prochain_joueur->prenom()));
	$prochain_joueur->setPseudo(utf8_encode($prochain_joueur->pseudo()));
	$prochain_joueur->setMdp(utf8_encode($prochain_joueur->mdp()));
	$prochain_joueur->setEmail(utf8_encode($prochain_joueur->email()));
	$prochain_joueur->setEmail_parent(utf8_encode($prochain_joueur->email_parent()));
	$prochain_joueur->setSexe(utf8_encode($prochain_joueur->sexe()));
	$prochain_joueur->setAvatar_tete(utf8_encode($prochain_joueur->avatar_tete()));
	$prochain_joueur->setAvatar_entier(utf8_encode($prochain_joueur->avatar_entier()));
	$prochain_joueur->setCollege(utf8_encode($prochain_joueur->college()));
	$prochain_joueur->setTuteur(utf8_encode($prochain_joueur->tuteur()));
	$prochain_joueur->setImg_tuteur(utf8_encode($prochain_joueur->img_tuteur()));
	$prochain_joueur->setDernier_log(utf8_encode($prochain_joueur->dernier_log()));
	$prochain_joueur->setPosition("");
	$prochain_joueur->setContacts("");
	$prochain_joueur->setHistoires_vues("");
	$prochain_joueur->setDate_inscription(utf8_encode($prochain_joueur->date_inscription()));
	$prochain_joueur->setTuto(utf8_encode($prochain_joueur->tuto()));
	$prochain_joueur->setDate_fin_tuto(utf8_encode($prochain_joueur->date_fin_tuto()));
	$prochain_joueur->setPreference_pyrs(utf8_encode($prochain_joueur->preference_pyrs()));
	echo $prochain_joueur->jsonSerialize();
	*/
	$caracs_joueur_suivant = array();
	$caracs_joueur_suivant["pseudo"] = $prochain_joueur->pseudo();
	$caracs_joueur_suivant["niveau"] = $prochain_joueur->niveau();
	$caracs_joueur_suivant["element"] = $prochain_joueur->element();
	$caracs_joueur_suivant["profil_elem"] = $prochain_joueur->profil_elem_decompose();
	$caracs_joueur_suivant["pm_joueur"] = round($prochain_joueur->facteur_elem_atq($monstre->element())/100 * $prochain_joueur->pm());
	$caracs_joueur_suivant["endu_joueur"] = $prochain_joueur->endu();
	$caracs_joueur_suivant["img_joueur"] = $prochain_joueur->avatar_entier();
	$caracs_joueur_suivant["img_tete_joueur"] = $prochain_joueur->full_portrait();
	$caracs_joueur_suivant["pm_monstre"] = round($prochain_joueur->facteur_elem_def($monstre->element())/100 * $monstre->pm());
	echo json_encode($caracs_joueur_suivant);
}

if(isset($_POST["combat_fini"])) //Script pour récupérer le joueur suivant lors d'un combat déjà passé
{
	$id_combat = (int) $_POST["combat_fini"];
	$combat = $combats_manager->get_id($id_combat);
	$nb_chasseurs_ko = (int) $_POST["nb_ko"];
	$chasseurs = $combat->copies_joueurs();
	$prochain_joueur = $chasseurs[$nb_chasseurs_ko];
	$monstre = $combat->copie_monstre();
	$caracs_joueur_suivant = array();
	$caracs_joueur_suivant["pseudo"] = $prochain_joueur->pseudo();
	$caracs_joueur_suivant["niveau"] = $prochain_joueur->niveau();
	$caracs_joueur_suivant["element"] = $prochain_joueur->element();
	$caracs_joueur_suivant["profil_elem"] = $prochain_joueur->profil_elem_decompose();
	$caracs_joueur_suivant["pm_joueur"] = round($prochain_joueur->facteur_elem_atq($monstre->element())/100 * $prochain_joueur->pm());
	$caracs_joueur_suivant["endu_joueur"] = $prochain_joueur->endu();
	$caracs_joueur_suivant["img_joueur"] = $prochain_joueur->avatar_entier();
	$caracs_joueur_suivant["img_tete_joueur"] = $prochain_joueur->avatar_tete();
	$caracs_joueur_suivant["pm_monstre"] = round($prochain_joueur->facteur_elem_def($monstre->element())/100 * $monstre->pm());
	echo json_encode($caracs_joueur_suivant);
}

if(isset($_POST["deroulement_combat"]) && isset($_POST["endu_monstre"])) //Script pour enregistrer le déroulement à chaque étape du combat
{
	$combat = $combats_manager->get_id((int)$_POST["id"]);
	$_SESSION["combat_en_cours"] = $combat->id();
	//Normalement ces étapes ont déjà été faites au commencement du combat mais on vérifie que ce soit bien le cas
	if($combat->nb_chasseurs_ko() == $combat->nb_chasseurs() - 1 && $monstres_manager->exists($combat->id_monstre()))	{
		//On effectue la copie du joueur actif
		$chasseurs = $combat->copies_joueurs();
		$copie_joueur = clone $joueur; //On enlève les champs avec caractères spéciaux pour éviter les problèmes de serialization
		$copie_joueur->setNom("");
		$copie_joueur->setPrenom("");
		$copie_joueur->setCollege("");
		if($chasseurs == "")
		{
			$chasseurs = array($copie_joueur);
		}
		else
		{
			$chasseurs[] = $copie_joueur;
		}
		$combat->setCopies_joueurs($chasseurs);

		$monstre = $monstres_manager->get_id($combat->id_monstre());
		$monstre->setDead(1);
		$monstres_manager->update($monstre);
		$vu = $combat->combat_vu($joueur); //On considère le combat comme vu pour ce joueur
		$joueur->setNb_combats($joueur->nb_combats() - 1); //On diminue le nbre de combats autorisés pour aujourd'hui
		$manager->update($joueur);
	}

	$_SESSION["deroulement_combat"] = json_decode($_POST["deroulement_combat"]); //Variable de session pour éviter les requêtes ajax n'aboutissant pas
	$_SESSION["endu_monstre"] = (int) $_POST["endu_monstre"]; //Variable de session pour éviter les requêtes ajax n'aboutissant pas

	if($combat->issue() == "")	{
		$monstre = $monstres_manager->get_id($combat->id_monstre());
		$deroulement = json_decode($_POST["deroulement_combat"]);
		$deroulement_txt = "";
		foreach($deroulement as $etape)
		{
			$deroulement_txt .= $etape.";";
		}
//		$issue = "";
//		if(strpos($deroulement_txt, "defaite"))
//		{
//			$issue = "defaite";
//		}
//		elseif(strpos($deroulement_txt, "victoire"))
//		{
//			$issue = "victoire";
//		}
		$issue = $_POST["end"];
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = strftime('%Y-%m-%d %H:%M:%S',time());
		$combat->setDate_combat($date);

		if($issue == "defaite" || $issue == "victoire")		{
			$deroulement_txt = substr($deroulement_txt, 0, strlen($deroulement_txt) - 1); //On enlève le point virgule final si le combat est fini
			if($issue == "defaite")
			{
				$combat->setIssue("defaite");
				if($joueur->tuto() == "combattre_2"){
					$combat->setPrestige(0);
				} else {
				$combat->setPrestige($monstre->perte_prestige($combat->nb_chasseurs(), $classrooms_manager->hasTimeSlot($joueur)));
			}
			}
			elseif($issue == "victoire")
			{
				$combat->setIssue("victoire");
				if($joueur->tuto() == "combattre_2"){
					$combat->setPrestige(0);
				} else {
				$combat->setPrestige($monstre->gain_prestige($combat->nb_chasseurs(), $classrooms_manager->hasTimeSlot($joueur)));
			}
			}
			foreach($combat->ordre() as $id_chasseur) //On met à jour le prestige des chasseurs
			{
				if($joueur->id() != $id_chasseur)
				{
					$chasseur = $manager->get($id_chasseur);
					$chasseur->setPrestige($chasseur->prestige() + $combat->prestige());
					$manager->update($chasseur);
				}
				else
				{
					$joueur->setPrestige($joueur->prestige() + $combat->prestige());
					$manager->update($joueur);
				}
			}
			$combat->setVu(""); //On réinitialise les vues pour que tout le monde aille le voir
			$combat->combat_vu($joueur);
		}

		$combat->setDeroulement($deroulement_txt);
		$combat->setEndu_monstre_restante((int) $_POST["endu_monstre"]);
		$combats_manager->update($combat);

		// ******
		// Record fight flow
//		error_log("\n\nRecord fight flow");
//		error_log("\n\n".json_decode( $_POST["fight_flow"], true ) );
		$fight_flow_array = json_decode($_POST["fight_flow"], true);

		for($i = 0; $i < count($fight_flow_array); ++$i) {
			$flow_manager->save_or_update(new Flow($fight_flow_array[$i]) );
		}
		// ******
		if($joueur->tuto() == "combattre_2"){
			$joueur->setTuto("index_3");
			$manager->update($joueur);
		} if($joueur->tuto() == "combattre_11")	{
			$joueur->setTuto("index_12");
			$manager->update($joueur);
		}

		// ***********************************************
		// ***** ACHIEVEMENTS
		// Won against XX monstres of an element
		$total = $flow_manager->fight_won_against_elem( $joueur, $monstre->element() );
    //error_log("********** Total of monster killed with element ".$monstre->element()." is ".$total);
		$achievement_manager->for_won_against_xx_monster_of_elem($joueur, $total, $monstre->element());

		if($issue == "victoire") {
			error_log("********** Victory is OK");
			$percent_life = (int) $_POST["percent_life"];
			error_log("********** Percent life ".$percent_life);
			if ($percent_life < 10) {
				//KLOUG : TOTEST $achievement_manager->add( $joueur->id(), 21 );
			}

			$used_heal = $_POST["used_heal"];
			//error_log("********** Used heal spells : ".$used_heal);
			if ($used_heal == "false") {
				$achievement_manager->add( $joueur->id(), 22 );
			}

			$spells_all_ok = $_POST["spells_all_ok"];
			//error_log("********** All spells succedded : ".$spells_all_ok);
			if ($spells_all_ok == "true") {
				$achievement_manager->add( $joueur->id(), 25 );
			}

			$spells_pm_percent = $_POST["spells_pm_percent"];
			error_log("********** Max PM percent hit : ".$spells_pm_percent);
			if ($spells_pm_percent >=200 ) $achievement_manager->add( $joueur->id(), 31 );
			else if ($spells_pm_percent >=150 ) $achievement_manager->add( $joueur->id(), 30 );
			else if ($spells_pm_percent >=100 ) $achievement_manager->add( $joueur->id(), 29 );
			else if ($spells_pm_percent >=75 ) $achievement_manager->add( $joueur->id(), 28 );
			else if ($spells_pm_percent >=50 ) $achievement_manager->add( $joueur->id(), 27 );
		}

		// ***********************************************



	}
}

if(isset($_POST["caracs_joueur"])) //Script pour récupérer les caracs du joueur en JS
{
	if($_POST["caracs_joueur"] == "get")
	{
		$caracs_joueur = array();
		$caracs_joueur["id"] = $joueur->id();
		$caracs_joueur["pseudo"] = $joueur->pseudo();
		$caracs_joueur["sexe"] = $joueur->sexe();
		$caracs_joueur["classe"] = $joueur->classe();
		$caracs_joueur["niveau"] = (int)$joueur->niveau();
		$caracs_joueur["xp"] = (int)$joueur->xp();
		$caracs_joueur["pyrs_feu"] = (int)$joueur->pyrs_feu();
		$caracs_joueur["pyrs_eau"] = (int)$joueur->pyrs_eau();
		$caracs_joueur["pyrs_vent"] = (int)$joueur->pyrs_vent();
		$caracs_joueur["pyrs_terre"] = (int)$joueur->pyrs_terre();
		$caracs_joueur["element"] = $joueur->element();
		$caracs_joueur["element_article"] = $joueur->element_article();
		$caracs_joueur["element_article2"] = $joueur->element_article2();
		$caracs_joueur["profil_elem"] = $joueur->profil_elem_decompose();
		$caracs_joueur["prestige"] = (int)$joueur->prestige();
		$caracs_joueur["pm_joueur"] = (int)$joueur->pm();
		$caracs_joueur["endu_joueur"] = (int)$joueur->endu();
		$caracs_joueur["nb_combats"] = (int)$joueur->nb_combats();
		$caracs_joueur["tuto"] = $joueur->tuto();
		$caracs_joueur["img_joueur"] = $joueur->avatar_entier();
		$caracs_joueur["img_tete_joueur"] = $joueur->avatar_tete();
		$caracs_joueur["tuteur"] = $joueur->tuteur();
		$caracs_joueur["unassignedChallenges"] = (int)$joueur->stock_challenges();
		$caracs_joueur["assignedChallenges"] = (int)$challenges_manager->get_assigned($joueur);
		$caracs_joueur["bulles_daide_vues"] = $joueur->bulles_daide_vues();
		$caracs_joueur["advanced_description"] = (int)$joueur->advanced_description();
		echo json_encode($caracs_joueur);
	}
}

if(isset($_POST["titre_amelioration"]) && isset($_POST["descriptif_amelioration"])) //Script pour enregistrer une nouvelle idée d'amélioration
{
	if(strlen($_POST["titre_amelioration"]) >= 10 && strlen($_POST["titre_amelioration"]) <= 50 && strlen($_POST["descriptif_amelioration"]) >= 20 && strlen($_POST["descriptif_amelioration"]) <= 500)
	{
		$amelioration = new Amelioration(array(
			"id_joueur" => $joueur->id(),
			"titre" => $_POST["titre_amelioration"],
			"descriptif" => $_POST["descriptif_amelioration"],
			"statut" => "en_cours"
			));
		$ameliorations_manager->add($amelioration);
	}
}

if(isset($_POST["amelioration_vue"])) //Script pour considérer une amélioration comme vue
{
	$amelioration = $ameliorations_manager->get((int)$_POST["amelioration_vue"]);
	if(!in_array($joueur->id(), $amelioration->vues())) //Si le joueur n'a pas déjà vu l'amélioration
	{
		$vues = $amelioration->vues();
		$vues[] = $joueur->id();
		$amelioration->setVues($vues);
		$ameliorations_manager->update($amelioration);
	}
}

if(isset($_POST["voter_amelioration"])) //Script pour voter pour une amélioration
{
	$amelioration = $ameliorations_manager->get((int)$_POST["voter_amelioration"]);
	if($amelioration->votants() == "")
		{$amelioration->setVotants(array());}
	if($joueur->id() != $amelioration->id_joueur() && !in_array($joueur->id(), $amelioration->votants())) //Si le joueur n'a pas proposé l'amélioration et n'a pas déjà voté
	{
		$votants = $amelioration->votants();
		$nb_votes = $amelioration->nb_votes();
		$votants[] = $joueur->id();
		$nb_votes ++;
		$amelioration->setVotants($votants);
		$amelioration->setNb_votes($nb_votes);
		$ameliorations_manager->update($amelioration);
	}
}

if(isset($_POST["enlever_vote_amelioration"])) //Script pour enlever le vote d'une amélioration
{
	$amelioration = $ameliorations_manager->get((int)$_POST["enlever_vote_amelioration"]);
	if($amelioration->votants() == "")
		{$amelioration->setVotants(array());}
	if(in_array($joueur->id(), $amelioration->votants())) //Si le joueur a pas déjà voté pour cette amélioration
	{
		$votants = $amelioration->votants();
		$nb_votes = $amelioration->nb_votes();
		array_splice($votants, array_search($joueur->id(), $votants), 1);
		$nb_votes --;
		$amelioration->setVotants($votants);
		$amelioration->setNb_votes($nb_votes);
		$ameliorations_manager->update($amelioration);
	}
}

if(isset($_POST["bulle_daide"])) //Script pour récupérer la bulle d'aide à afficher
{
	if((int)$_POST["bulle_daide"] != 0) //Si JS envoie un numéro non nul, c'est pour enregistrer la lecture d'une bulle d'aide
	{
		$bulles_joueur = $joueur->bulles_daide_vues();

		if($bulles_joueur == "") //Si le joueur n'a encore vu aucune bulle d'aide
		{
			$bulles_joueur = array();
		}

		$bulles_joueur[$_POST["adresse"]] = (int) $_POST["bulle_daide"];

		$joueur->setBulles_daide_vues($bulles_joueur);
		if(isset($bulles_joueur["classement"]) && isset($bulles_joueur["contacts"]) && isset($bulles_joueur["inviter_contacts"]) && isset($bulles_joueur["entrainement"]) && isset($bulles_joueur["grimoire"]) && isset($bulles_joueur["histoires"]) && isset($bulles_joueur["index"]) && isset($bulles_joueur["liste_combats"]) && isset($bulles_joueur["messages"]) && isset($bulles_joueur["parametres"]) && isset($bulles_joueur["prepa_combats"]) && isset($bulles_joueur["recherche"]))
		{
			if($bulles_joueur["classement"] == 3 && $bulles_joueur["contacts"] == 3 && $bulles_joueur["inviter_contacts"] == 2 && $bulles_joueur["entrainement"] == 1 && $bulles_joueur["grimoire"] == 1 && $bulles_joueur["histoires"] == 1 && $bulles_joueur["index"] == 8 && $bulles_joueur["liste_combats"] == 2 && $bulles_joueur["messages"] == 3 && $bulles_joueur["parametres"] == 1 && $bulles_joueur["prepa_combats"] == 7 && $bulles_joueur["recherche"] == 1)
			{
				$joueur->setBulles_daide_actives("non"); //On désactive les bulles si le joueur les a toutes vues
			}
		}
		$manager->update($joueur);
	}
	//Dans tous les cas on récupère la prochaine bulle
	$bulles_joueur = $joueur->bulles_daide_vues();
	if($bulles_joueur == "")
	{
		$numero = 1;
	}
	else
	{
		if(!isset($bulles_joueur[$_POST["adresse"]]))
		{
			$numero = 1;
		}
		else
		{
			$numero = $bulles_joueur[$_POST["adresse"]] + 1;
		}
	}
	$bulle = array();
	$bulle["adresse"] = $_POST["adresse"];
	$bulle["numero"] = $numero;
	echo json_encode($bulle);
}

if(isset($_POST["advanced_spell_description"])){
	$joueur->setAdvanced_description((int)$_POST["advanced_spell_description"]);
	$music_settings = array();
	$music_settings[] = (int)$_POST["volume_music"];
	$music_settings[] = (int)$_POST["volume_sound_effects"];
	$music_settings[] = (int)$_POST["volume_interface"];
	$joueur->setMusic_settings($music_settings);
	$manager->update($joueur);
	/*
	if($_POST["statut_bulles_daides"] == 1)	{
		$joueur->setBulles_daide_actives("oui");
		$manager->update($joueur);
	}
	else
	{
		$joueur->setBulles_daide_actives("non");
		$manager->update($joueur);
	} */
}

if(isset($_POST["type_classement"]) && isset($_POST["classe"]))  //Script pour récupérer le classement à afficher
{

		if($_POST["type_classement"] == "individuel")
		{
			$joueurs = $manager->get_classement_by($joueur, $_POST["classe"]);
			$rang = 0;
			foreach($joueurs as $joueur_cible) {
				$joueur_cible->setCurrent_classement($manager->get_actual_position_by_prestige($joueur_cible->id(), $_POST["classe"]));
				$rang ++;
				if($joueur->id() == $joueur_cible->id())
					{$surbrillance = "fond_beige_clair"; //Met en évidence la ligne du joueur
					echo ('<span class="cache" id="position">'.($joueur_cible->getCurrent_classement() + 1).'</span>');}  //Met un champ caché qui permettra de centrer le scroll sur le joueur en JS
				else
					{$surbrillance = "";}
				echo('<a class="gris" href="/app/controllers/profil.php?id='.$joueur_cible->id().'">');
				echo('<div class="ligne_scroll '.$surbrillance.'">');
					echo('<span class="l8">'.($joueur_cible->getCurrent_classement() + 1).'°</span>');
					echo('<span class="l15 md2">'.$joueur_cible->pseudo().'</span>');
					echo('<span class="l15"><img class="l60 team_portraits" title="'.$joueur_cible->pseudo().' - Niv.'.$joueur_cible->niveau().'" src="'.$joueur_cible->full_portrait().'" /></span>');
					echo('<span class="l15">'.$joueur_cible->prestige().'</span>');
					echo('<span class="l8">'.$joueur_cible->classe().'</span>');
					echo(' <span class="l25 p0">'.$joueur_cible->college().'</span>');
					echo(' <span class="l10 p0">'.$joueur_cible->departement().'</span>');
				echo('</div>');
				echo('</a>');
            }
		}
		if($_POST["type_classement"] == "meilleur_coequipier") {
			$joueurs = $manager->get_classement_by($joueur, $_POST["classe"]);
			$classement = array();
			foreach($joueurs as $joueur_cible) {
				$classement[(string)$joueur_cible->id()] = $combats_manager->points_coequipier_global($joueur_cible);
			}
			arsort($classement);
			$rang = 0;
			foreach($classement as $id_joueur => $total_prestige)
        	{
				$joueur_cible = $manager->get((int)$id_joueur);
				$joueur_cible->setCurrent_classement($manager->get_actual_position_by_prestige($id_joueur, $_POST["classe"]));
				$rang ++;
				if($joueur->id() == $joueur_cible->id())
					{$surbrillance = "fond_beige_clair"; //Met en évidence la ligne du joueur
					echo ('<span class="cache" id="position">'.($joueur_cible->getCurrent_classement() + 1).'</span>');}  //Met un champ caché qui permettra de centrer le scroll sur le joueur en JS
				else
					{$surbrillance = "";}
				echo('<a class="gris" href="/app/controllers/profil.php?id='.$joueur_cible->id().'">');
				echo('<div class="ligne_scroll '.$surbrillance.'">');
  				echo('<span class="l8">'.($joueur_cible->getCurrent_classement() + 1).'°</span>');
					echo('<span class="l15 md2">'.$joueur_cible->pseudo().'</span>');
					echo('<span class="l15"><img class="l60 team_portraits" title="'.$joueur_cible->pseudo().' - Niv.'.$joueur_cible->niveau().'" src="'.$joueur_cible->full_portrait().'" /></span>');
					echo('<span class="l15">'.$total_prestige.'</span>');
					echo('<span class="l8">'.$joueur_cible->classe().'</span>');
					echo(' <span class="l25 p0">'.$joueur_cible->college().'</span>');
					echo(' <span class="l10 p0">'.$joueur_cible->departement().'</span>');
				echo('</div>');
				echo('</a>');
            }
		}
		if($_POST["type_classement"] == "equipe")
		{
			$scores_equipes = $combats_manager->points_equipe_global();
			if($_POST["classe"] != "Toutes"){
				foreach($scores_equipes as $equipe => $score){
					$id_players = explode(",", $equipe);
					foreach($id_players as $id_player){
						$team_player = $manager->get((int)$id_player);
						if (isset($team_player) &&  $team_player->classe() != $_POST["classe"]){
							unset($scores_equipes[$equipe]);
							break;
						}
					}
				}
			}
			arsort($scores_equipes);
			$rang = 0;
			foreach($scores_equipes as $equipe => $score)
        	{
				$id_equipe = explode(",", $equipe);
				$rang ++;
				if(in_array((string)$joueur->id(), $id_equipe))
					{$surbrillance = "fond_beige_clair"; //Met en évidence la ligne du joueur
					echo ('<span class="cache" id="position">'.$rang.'</span>');}  //Met un champ caché qui permettra de centrer le scroll sur le joueur en JS
				else
					{$surbrillance = "";}
				echo('<div class="ligne_scroll '.$surbrillance.'">');
					echo('<span class="l8">'.$rang.'°</span>');
					echo('<span class="l60">');
						foreach($id_equipe as $id_joueur) {
							$joueur_cible = $manager->get((int)$id_joueur);
							if (isset($joueur_cible)) echo('<img class="ib l8 team_portraits" title="'.$joueur_cible->pseudo().' - Niv.'.$joueur_cible->niveau().'" src="'.$joueur_cible->full_portrait().'" />');
						}
					echo('</span>');
					echo('<span class="l30">'.$score.'</span>');
				echo('</div>');
            }
		}
}

// Get challenges with tries > 0
if(isset($_POST["get_challenges_tried"])) {

	// From $joueur get all challenges
	$challenges = $challenges_manager->get_tried($joueur);
	$first = true;
	$output = "[";
	foreach($challenges as $challenge) {
		if (! $first ) $output = $output.",";
		else $first = false;

		$output = $output."{";
		$output = $output."\"element\": \"".$challenge->getElement()."\",";
		$output = $output."\"name\": \"".$challenge->getName()."\",";
		$output = $output."\"currentMastery\": ".$challenge->getCurrent_level()."";
		$output = $output."}";
	}
	$output = $output."]";
	echo $output;
}

if(isset($_POST["assignChallenge"]) && $joueur->stock_challenges()>0) //Assign a random challenge of the selected element
{
	$element = $_POST["assignChallenge"];
	switch ($element){
		case "feu" :
			$element = "fire";
			break;
		case "eau" :
			$element = "water";
			break;
		case "vent" :
			$element = "wind";
			break;
		case "terre" :
			$element = "earth";
			break;
		default :
			$element = "fire";
			break;
	}
	$potentialChallenges = $challenges_manager->get_potential($joueur, $element); //Get all potential challenges of the given element
	$challenge = $challenges_manager->selectChallenge($potentialChallenges, $scores_manager->count_scored_today($joueur));
	$challenge->setStock($challenge->getStock() + 1);
	$challenges_manager->save_or_update($challenge);
	$joueur->setStock_challenges($joueur->stock_challenges() - 1);
	if($joueur->tuto() != "fini")	{
		$joueur->setTuto("accueil_defi_4");
	}
	$manager->update($joueur);
}

// Save impression
if(isset($_POST["record_impression"])) {
	$record_impression = false;
	if($_POST["record_impression"] == "Introduction"){
		$joueur->setTuto("index_1");
		$joueur->setHistoires_vues(array("0_".$joueur->element()));
		$record_impression = true;
	} elseif($_POST["record_impression"] == "Fin Tutoriel"){
		$histoires_vues = $joueur->histoires_vues();
		if(count($histoires_vues) == 1){
			$histoires_vues[] = "1_".$joueur->element();
			$joueur->setHistoires_vues($histoires_vues);
			$record_impression = true;
		}
	}
	$manager->update($joueur);
	if($record_impression && $_POST["quality"] != ""){
		$impression = new Impression(array(
			"id_player" => $joueur->id(),
			"video" => $_POST["record_impression"],
			"quality" => $_POST["quality"]
		));
		$impressions_manager->add($impression);
	}
}

// Send email to parent
if(isset($_POST["sendEmailParent"])) {
	$joueur->setEmail_parent($_POST["sendEmailParent"]);
	$manager->update($joueur);
	if(!isset($_SESSION["emailParentSent"])){
		$_SESSION["emailParentSent"] = 1;
		$countChallenges = $challenges_manager->countTries($joueur);
		$classroom = $classrooms_manager->getClassroomStudent($joueur);
		if($classroom != "NoClassroom"){
			$teacher = $manager->get($classroom->idTeacher());
		} else {
			$teacher = "NoTeacher";
		}
		$joueur->sendEmailEndFreePeriod("parent", $countChallenges, $teacher);
		echo "ok";
	} else {
		echo "TooMuchEmails";
	}
}

// Send email to confirm email
if(isset($_POST["sendEmailActivationLink"])) {
	if(isset($_SESSION["activationLinkSent"]) && $_SESSION["activationLinkSent"]<3){
		$joueur->sendEmailActivationLink();
		$_SESSION["activationLinkSent"] ++;
	} elseif(!isset($_SESSION["activationLinkSent"])){
		$_SESSION["activationLinkSent"] = 1;
		$joueur->sendEmailActivationLink();
	}
}

//Generate a new code for admins
if(isset($_POST["maxInvitations"]) && isset($_POST["descriptionInvitation"]) && isset($_POST["categoryInvitation"])){
	$categoryInvitation = $_POST["categoryInvitation"];
	$maxInvitations = (int) $_POST["maxInvitations"];
	$descriptionInvitation = $_POST["descriptionInvitation"];
	$categoryInvitation = $_POST["categoryInvitation"];
	$code = $codes_manager->generateCode($joueur, $categoryInvitation, $maxInvitations, $descriptionInvitation);
	$codes_manager->add($code);
	echo $code->code();
}

//Generate a new code for admins
if(isset($_POST["inviteFriend"])){
	if($joueur->prenom() != ""){
		$firstname = $joueur->prenom();
	} else {
		$firstname = $joueur->pseudo();
	}
	$code = $codes_manager->get_sponsor($joueur->id());
	$code = $joueur->encrypt($code->code());
	$email = $joueur->encrypt($_POST["inviteFriend"]);
	$link = $joueur->rootLink()."/app/controllers/inscription.php?token=".$code."&id=".$email;
	if(!isset($_SESSION["invitationSent"])){
		$_SESSION["invitationSent"] = 1;
	} else {
		$_SESSION["invitationSent"] ++;
	}
	if($_SESSION["invitationSent"] <= 3){
		$joueur->send_email("122859", "Player", $firstname." t'a invité sur Navadra", $_POST["inviteFriend"], $params = '{ "Prenom": "'.$firstname.'", "Feminin": "'.$joueur->feminine("e").'", "3emePersonne": "'.$joueur->feminine("3rdBis").'", "Link": "'.$link.'" }');
		echo("ok");
	} else {
		echo("tooMuchInvitations");
	}
}

if(isset($_POST["discoverMonster"])){
	$monster = $monstres_manager->apparition_monstre_multi($joueur, $joueur->niveau(), 8);
	$codes_manager->setRewardObtained($joueur);
	$monsterCaracs = array(
		"id" => $monster->id(),
		"name" => $monster->nom(),
		"level" => $monster->niveau(),
		"color" => $monster->couleur_monstre(),
		"image" => $monster->img()
	);
	echo json_encode($monsterCaracs);
}

// Send email to confirm email
if(isset($_POST["dontReceiveEmail"])) {
	if(isset($_SESSION["dontReceiveEmail"])){
		echo "alreadyAlerted";
	} else {
		$_SESSION["dontReceiveEmail"] = 1;
		if($joueur->sameEmail()){
			$emailTitle = "Email du parent";
			$link = $joueur->rootLink()."/app/controllers/ajax_disconnected.php?confirmEmailParent=".$joueur->suivi_link();
			$mailLink = "mailto:".$joueur->email()."?subject=Email de confirmation non reçu&body=Bonjour,%0A%0ANavadra est un jeu vidéo gratuit pour aider les collégiens à progresser en mathématiques.%0A%0AVotre enfant, ".$joueur->firstname().", indique que vous n'avez pas reçu l'email vous permettant de confirmer votre adresse email et se retrouve donc bloqué dans le jeu.%0A%0ACopier-collez simplement le lien ci-dessous dans votre navigateur pour débloquer votre enfant.%0A%0A".$link."%0A%0ABonne journée !%0A%0A%0A%0AMichel Ferry – CEO et Cofondateur%0A%0Amichel@navadra.com";
		} elseif($joueur->classe() == "Prof"){
			$emailTitle = "Email (prof)";
			$link = $joueur->rootLink()."/app/controllers/ajax_disconnected.php?confirmEmail=".$joueur->suivi_link();
			$mailLink = "mailto:".$joueur->email()."?subject=Email de confirmation non reçu&body=Bonjour,%0A%0AVous avez indiqué ne pas avoir reçu l'email vous permettant de confirmer votre adresse email.%0A%0ACopier-collez simplement le lien ci-dessous dans votre navigateur pour débloquer votre compte.%0A%0A".$link."%0A%0ABonne journée !%0A%0A%0A%0AMichel Ferry – CEO et Cofondateur%0A%0Amichel@navadra.com";
		} else {
			$emailTitle = "Email du joueur";
			$link = $joueur->rootLink()."/app/controllers/ajax_disconnected.php?confirmEmail=".$joueur->suivi_link();
			$mailLink = "mailto:".$joueur->email()."?subject=Email de confirmation non reçu&body=Bonjour ".$joueur->firstname().",%0A%0AApparemment tu n'as pas reçu l'email de confirmation de Navadra.%0A%0ACopie-colle simplement le lien ci-dessous dans ton navigateur pour débloquer ton compte.%0A%0A".$link."%0A%0AA bientôt ;).%0A%0A%0A%0AMichel Ferry – CEO et Cofondateur%0A%0Amichel@navadra.com";
		}
		$joueur->send_email("135330", "Navadra", $joueur->firstname()." n'a pas reçu son email de confirmation", "michel@navadra.com", $params = '{ "Prenom": "'.$joueur->firstname().'", "IntituleEmail": "'.$emailTitle.'", "Email": "'.$joueur->email().'", "Pseudo": "'.$joueur->pseudo().'", "Link": "'.$mailLink.'"}');
		echo "reportSent";
	}
}


if(isset($_POST["getCollegeList"])) {
	$CollegeList = array();
	$q = $db_RO->prepare('SELECT * FROM colleges');
	$q->execute();
	while($data = $q->fetch()) {
		$CollegeList[] = array("departement" => $data["departement"], "nom" => $data["nom"]);
	}
	echo json_encode($CollegeList);
}
