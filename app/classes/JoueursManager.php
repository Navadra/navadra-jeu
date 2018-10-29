<?php

class JoueursManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }


public function add(Joueur $joueur) //ajoute un joueur dans la bdd puis hydrate l'objet en lui ajoutant son id et les valeurs par défaut pour les champs manquants
    {
		//Génération des variables automatiques
		if($joueur->classe() == "Prof" || $joueur->classe() == "Autre"){
			$tuto = "cinematique_0";
		} else {
			//$tuto = "cinematique_0";
			$tuto = "cinematique_0";
		}
		$joueur->setMdp(sha1($joueur->mdp()));
		$rand = rand(1,4);
		switch($rand)
		{
			case 1 :
				$tuteur = "Namuka";
				$img_tuteur = "/webroot/img/personnages/namuka.png";
				break;
			case 2 :
				$tuteur = "Katillys";
				$img_tuteur = "/webroot/img/personnages/katillys.png";
				break;
			case 3 :
				$tuteur = "Sivem";
				$img_tuteur = "/webroot/img/personnages/sivem.png";
				break;
			case 4 :
				$tuteur = "Leorn";
				$img_tuteur = "/webroot/img/personnages/leorn.png";
				break;
		}
		$joueur->setTuteur($tuteur);
		$joueur->determiner_position();
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = strftime('%Y-%m-%d %H:%M:%S',time());
		$dernier_log = $date;
		$date_inscription = $date;
		$suivi_link = sha1("R4l56df4esF-".$joueur->pseudo() );
		$music_settings = array(1, 1, 1); //Music, Sound Effects and Interface sounds
		$q = $this->_db_RW->prepare('INSERT INTO joueurs(nom, prenom, pseudo, mdp, email, email_parent, sexe, classe, avatar_tete, avatar_entier, departement, college, niveau, xp, pyrs_feu, pyrs_eau, pyrs_vent, pyrs_terre, pyrs_feu_dep, pyrs_eau_dep, pyrs_vent_dep, pyrs_terre_dep, tuteur, img_tuteur, prestige, dernier_log, position, contacts, histoires_vues,  date_inscription, reinitialisation_sorts, nb_combats, tuto, date_fin_tuto, stock_challenges, bulles_daide_actives, bulles_daide_vues, connecte, advanced_description, music_settings, abonnement_ok, suivi_link, email_confirme) VALUES(:nom, :prenom, :pseudo, :mdp, :email, :email_parent, :sexe, :classe, :avatar_tete, :avatar_entier, :departement, :college, :niveau, :xp, :pyrs_feu, :pyrs_eau, :pyrs_vent, :pyrs_terre, :pyrs_feu_dep, :pyrs_eau_dep, :pyrs_vent_dep, :pyrs_terre_dep, :tuteur, :img_tuteur, :prestige, :dernier_log, :position, :contacts, :histoires_vues, :date_inscription, :reinitialisation_sorts, :nb_combats, :tuto, :date_fin_tuto, :stock_challenges, :bulles_daide_actives, :bulles_daide_vues, :connecte, :advanced_description, :music_settings, :abonnement_ok, :suivi_link, :email_confirme)');
		$q->execute(array(
			'nom' => $joueur->nom(),
			'prenom' => $joueur->prenom(),
			'pseudo' => $joueur->pseudo(),
			'mdp' => $joueur->mdp(),
			'email' => $joueur->email(),
			'email_parent' => "",
			'sexe' => $joueur->sexe(),
			'classe' => $joueur->classe(),
			'avatar_tete' => $joueur->avatar_tete(),
			'avatar_entier' => $joueur->avatar_entier(),
			'departement' => $joueur->departement(),
			'college' => $joueur->college(),
			'niveau' => 1,
			'xp' => 0,
			'pyrs_feu' => 0,
			'pyrs_eau' => 0,
			'pyrs_vent' => 0,
			'pyrs_terre' => 0,
			'pyrs_feu_dep' => 1,
			'pyrs_eau_dep' => 1,
			'pyrs_vent_dep' => 1,
			'pyrs_terre_dep' => 1,
			'tuteur' => $tuteur,
			'img_tuteur' => $img_tuteur,
			'prestige' => 0,
			'dernier_log' => $dernier_log,
			'position' => serialize($joueur->position()),
			'contacts' => "",
			'histoires_vues' => "",
			'date_inscription' => $date_inscription,
			'reinitialisation_sorts' => 0,
			'nb_combats' => 1,
			'tuto' => $tuto,
			'date_fin_tuto' => "",
			'stock_challenges' => 0,
			'bulles_daide_actives' => "oui",
			'bulles_daide_vues' => "",
			'connecte' => "oui",
			'advanced_description' => 0,
			'music_settings' => serialize($music_settings),
			'abonnement_ok' => 0,
			'suivi_link' => $suivi_link,
			'email_confirme' => 0
			));
		$joueur->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),
      		'niveau' => 1,
			'xp' => 0,
			'email_parent' => "",
			'pyrs_feu' => 0,
			'pyrs_eau' => 0,
			'pyrs_vent' => 0,
			'pyrs_terre' => 0,
			'pyrs_feu_dep' => 1,
			'pyrs_eau_dep' => 1,
			'pyrs_vent_dep' => 1,
			'pyrs_terre_dep' => 1,
			'tuteur' => $tuteur,
			'img_tuteur' => $img_tuteur,
			'prestige' => 0,
			'dernier_log' => $dernier_log,
			'contacts' => "",
			'histoires_vues' => "",
			'date_inscription' => $date_inscription,
			'reinitialisation_sorts' => 0,
			'nb_combats' => 1,
			'tuto' => $tuto,
			'date_fin_tuto' => "",
			'stock_challenges' => 0,
			'bulles_daide_actives' => "oui",
			'bulles_daide_vues' => "",
			'connecte' => "oui",
			'advanced_description' => 0,
			'music_settings' => $music_settings,
			'abonnement_ok' => 0,
			'suivi_link' => $suivi_link,
			'email_confirme' => 0
    	));
		$joueur->update_portrait();
	}

	public function exists($info) //Permet de tester si un joueur existe, $info pouvant être une id, un pseudo ou un email
    {

		if(is_int($info)) //On test si on a passé un id en argument
		{
			$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE id= :id');
			$q->execute(array(
 			'id' => $info,
	 		));
			return (bool) $q->fetch();
		}
		elseif(!is_array($info) && preg_match("#^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9_.-]{2,}\.[a-zA-Z]{2,4}$#", $info)) //Sinon on test pour voir si on passé un email en argument
		{
			$info = $info;
			$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE email= :email');
			$q->execute(array(
 			'email' => $info,
	 		));
			return (bool) $q->fetch();
		}
		elseif(is_array($info) && sizeof($info)==2 && preg_match("#^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9_.-]{2,}\.[a-zA-Z]{2,4}$#", $info[1])) //Sinon on test pour voir si c'est un couple pseudo / email (recup de mot de passe)
		{
			$pseudo = $info[0];
			$email = $info[1];
			$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE email= :email AND pseudo= :pseudo');
			$q->execute(array(
			'pseudo' => $pseudo,
 			'email' => $email,
	 		));
			return (bool) $q->fetch();
		}
		elseif(is_array($info) && sizeof($info)==2) //Sinon on test pour voir si c'est un couple pseudo / mdp (connexion)
		{
			$pseudo = $info[0];
			$mdp = sha1($info[1]);
			$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE pseudo= :pseudo AND mdp= :mdp');
			$q->execute(array(
 			'pseudo' => $pseudo,
			'mdp' => $mdp,
	 		));
			return (bool) $q->fetch();
		}
		elseif(is_array($info) && sizeof($info)==3) //Sinon on test pour voir si c'est un triplet pseudo / question_secrete / reponse_secrete (recup mdp)
		{
			$pseudo = $info[0];
			$question_secrete = $info[1];
			$reponse_secrete = $info[2];
			$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE pseudo= :pseudo AND question_secrete= :question_secrete AND reponse_secrete= :reponse_secrete');
			$q->execute(array(
 			'pseudo' => $pseudo,
			'question_secrete' => $question_secrete,
			'reponse_secrete' => $reponse_secrete,
	 		));
			return (bool) $q->fetch();
		}
		else //Sinon on fait la recherche sur le pseudo
		{
			$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE pseudo= :pseudo');
			$q->execute(array(
 			'pseudo' => $info,
	 		));
			return (bool) $q->fetch();
		}
	}

	public function loginPseudo($info){ //Try to loggin with the pseudo
		if(is_array($info) && sizeof($info)==2) {
			$pseudo = $info[0];
			$mdp = sha1($info[1]);
			$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE pseudo= :pseudo AND mdp= :mdp');
			$q->execute(array(
 			'pseudo' => $pseudo,
			'mdp' => $mdp,
	 		));
			return (bool) $q->fetch();
		} else {
			return false;
		}
	}

	public function loginEmail($info){ //Try to loggin with the pseudo
		if(is_array($info) && sizeof($info)==2 && preg_match("#^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9_.-]{2,}\.[a-zA-Z]{2,4}$#", $info[0])) {
			$email = $info[0];
			$mdp = sha1($info[1]);
			$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE email= :email AND mdp= :mdp');
			$q->execute(array(
 			'email' => $email,
			'mdp' => $mdp,
	 		));
			return (bool) $q->fetch();
		} else {
			return false;
		}
	}

	public function get_by_id($info) //Permet de récupérer les infos d'un joueur, $info pouvant être une id, un pseudo ou un email
	{
		$q = $this->_db_RW->prepare('SELECT * FROM joueurs WHERE id= :id');
		$q->execute(array(
				'id' => $info,
		));
		$donnees = $q->fetch();
		//On désérialise les arrays
		$donnees["position"] = unserialize($donnees["position"]);
		$donnees["contacts"] = unserialize($donnees["contacts"]);
		$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
		$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
		$donnees["music_settings"] = unserialize($donnees["music_settings"]);
		// IF Suivi_link does'nt exists : create it
		return new Joueur($donnees);
	}
	public function get($info) //Permet de récupérer les infos d'un joueur, $info pouvant être une id, un pseudo ou un email
	{
		if(is_int($info)) //On test si on a passé un id en argument
		{
			$q = $this->_db_RW->prepare('SELECT * FROM joueurs WHERE id= :id');
			$q->execute(array(
					'id' => $info,
			));
			$donnees = $q->fetch();
			//On désérialise les arrays
			if ( $donnees[0] != null ) {
				$donnees["position"] = unserialize($donnees["position"]);
				$donnees["contacts"] = unserialize($donnees["contacts"]);
				$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
				$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
				$donnees["music_settings"] = unserialize($donnees["music_settings"]);
				// IF Suivi_link does'nt exists : create it
				return new Joueur($donnees);
			}
			return null;
		}
		elseif(preg_match("#^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9_.-]{2,}\.[a-zA-Z]{2,4}$#", $info)) //Sinon on test pour voir si on passé un email en argument
		{
			$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE email= :email');
			$q->execute(array(
 			'email' => $info,
	 		));
			$donnees = $q->fetch();
			//On désérialise les arrays
			$donnees["position"] = unserialize($donnees["position"]);
			$donnees["contacts"] = unserialize($donnees["contacts"]);
			$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
			$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
			$donnees["music_settings"] = unserialize($donnees["music_settings"]);
			return new Joueur($donnees);
		}
		else //Sinon on fait la recherche sur le pseudo
		{
			$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE pseudo= :pseudo');
			$q->execute(array(
 			'pseudo' => $info,
	 		));
			$donnees = $q->fetch();
			//On désérialise les arrays
			$donnees["position"] = unserialize($donnees["position"]);
			$donnees["contacts"] = unserialize($donnees["contacts"]);
			$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
			$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
			$donnees["music_settings"] = unserialize($donnees["music_settings"]);
			return new Joueur($donnees);
		}
	}

	public function recherche($infos) //Permet de rechercher tous les joueurs correspondant à ces critères
    {
		if($infos["sexe"] == ""){$infos["sexe"] = "%";}
		if($infos["classe"] == ""){$infos["classe"] = "%";}
		if($infos["departement"] == ""){$infos["departement"] = "%";}
		if($infos["college"] == ""){$infos["college"] = "%";}
		$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE pseudo LIKE :pseudo AND prenom LIKE :prenom AND nom LIKE :nom AND sexe LIKE :sexe AND classe LIKE :classe AND departement LIKE :departement AND college LIKE :college ORDER BY nom ASC');
		$q->execute(array(
 		'pseudo' => "%".$infos["pseudo"]."%",
		'prenom' => "%".$infos["prenom"]."%",
		'nom' => "%".$infos["nom"]."%",
		'sexe' => $infos["sexe"],
		'classe' => $infos["classe"],
		'departement' => $infos["departement"],
		'college' => $infos["college"],
	 	));
		$joueurs = array();
		while($donnees = $q->fetch())
		{
			$joueurs[] = new Joueur($donnees);
		}
		return $joueurs;
	}

	public function update(Joueur $joueur)
    {
		//Serialization des arrays
		$position = serialize($joueur->position());
		if($joueur->contacts()){$contacts = serialize($joueur->contacts());}
		else{$contacts = "";}
		if($joueur->histoires_vues()){$histoires_vues = serialize($joueur->histoires_vues());}
		else{$histoires_vues = "";}
		if($joueur->bulles_daide_vues()){$bulles_daide_vues = serialize($joueur->bulles_daide_vues());}
		else{$bulles_daide_vues = "";}
		$music_settings = serialize($joueur->music_settings());
		$q = $this->_db_RW->prepare('UPDATE joueurs SET nom=:nom, prenom=:prenom, pseudo=:pseudo, mdp=:mdp, email=:email, email_parent=:email_parent, classe=:classe, avatar_tete=:avatar_tete, avatar_entier=:avatar_entier, departement=:departement, college=:college, niveau=:niveau, xp=:xp, pyrs_feu=:pyrs_feu, pyrs_eau=:pyrs_eau, pyrs_vent=:pyrs_vent, pyrs_terre=:pyrs_terre, pyrs_feu_dep=:pyrs_feu_dep, pyrs_eau_dep=:pyrs_eau_dep, pyrs_vent_dep=:pyrs_vent_dep, pyrs_terre_dep=:pyrs_terre_dep, tuteur=:tuteur, img_tuteur=:img_tuteur, prestige=:prestige, dernier_log=:dernier_log, position=:position, contacts=:contacts, histoires_vues=:histoires_vues, reinitialisation_sorts=:reinitialisation_sorts, nb_combats=:nb_combats, tuto=:tuto, date_fin_tuto=:date_fin_tuto, stock_challenges=:stock_challenges, bulles_daide_actives=:bulles_daide_actives, bulles_daide_vues=:bulles_daide_vues, connecte=:connecte, advanced_description=:advanced_description, music_settings=:music_settings , abonnement_ok=:abonnement_ok , suivi_link=:suivi_link, email_confirme=:email_confirme WHERE id=:id');
		$q->execute(array(
			'id' => $joueur->id(),
			'nom' => $joueur->nom(),
			'prenom' => $joueur->prenom(),
			'pseudo' => $joueur->pseudo(),
			'mdp' => $joueur->mdp(),
			'email' => $joueur->email(),
			'email_parent' => $joueur->email_parent(),
			'classe' => $joueur->classe(),
			'avatar_tete' => $joueur->avatar_tete(),
			'avatar_entier' => $joueur->avatar_entier(),
			'departement' => $joueur->departement(),
			'college' => $joueur->college(),
			'niveau' => $joueur->niveau(),
			'xp' => $joueur->xp(),
			'pyrs_feu' => $joueur->pyrs_feu(),
			'pyrs_eau' => $joueur->pyrs_eau(),
			'pyrs_vent' => $joueur->pyrs_vent(),
			'pyrs_terre' => $joueur->pyrs_terre(),
			'pyrs_feu_dep' => $joueur->pyrs_feu_dep(),
			'pyrs_eau_dep' => $joueur->pyrs_eau_dep(),
			'pyrs_vent_dep' => $joueur->pyrs_vent_dep(),
			'pyrs_terre_dep' => $joueur->pyrs_terre_dep(),
			'tuteur' => $joueur->tuteur(),
			'img_tuteur' => $joueur->img_tuteur(),
			'prestige' => $joueur->prestige(),
			'dernier_log' => $joueur->dernier_log(),
			'position' => $position,
			'contacts' => $contacts,
			'histoires_vues' => $histoires_vues,
			'reinitialisation_sorts' => $joueur->reinitialisation_sorts(),
			'nb_combats' => $joueur->nb_combats(),
			'tuto' => $joueur->tuto(),
			'date_fin_tuto' => $joueur->date_fin_tuto(),
			'stock_challenges' => $joueur->stock_challenges(),
			'bulles_daide_actives' => $joueur->bulles_daide_actives(),
			'bulles_daide_vues' => $bulles_daide_vues,
			'connecte' => $joueur->connecte(),
			'advanced_description' => $joueur->advanced_description(),
			'music_settings' => $music_settings,
			'abonnement_ok' => $joueur->abonnement_ok(),
			'suivi_link' => $joueur->suivi_link(),
			'email_confirme' => $joueur->email_confirme()
		));
	}

	public function delete(Joueur $joueur) //Supprime un joueur (admin)
    {
		$q = $this->_db_RW->prepare('DELETE FROM joueurs WHERE id=:id');
		$q->execute(array(
			'id' => $joueur->id(),
			));
	}

  /**
   * Type :
   * 1 = Classement by niveau (5 first, player and friends)
   * 2 = Classement by prestige (5 first, player and friends)
   */
	public function get_classement_by(Joueur $joueur, $class) {
    // ****
    // We select only ids first and after we make the real request to get everything in the right order just once
    $joueurs_id = $this->get_classement_ids_for_player($joueur, $class);

		// Now we have the list let's make the request
    $query = 'SELECT * FROM joueurs WHERE ';
		if($class == "Eleve"){
			$query = $query.'(classe=\'6°\' OR classe=\'5°\' OR classe=\'4°\' OR classe=\'3°\') AND ( ';
		} elseif($class != "Toutes"){
			$query = $query.'classe=\''.$class.'\' AND ( ';
		}
    $first = true;
    foreach( $joueurs_id as $id_joueur ){
			if ($first == false) $query = $query.' OR ';
      $query = $query.'id='.$id_joueur;
			$first = false;
    }
		if ( $class != "Toutes") $query = $query.') ';
    $query = $query.' ORDER BY prestige DESC, niveau DESC, xp DESC';
    $q = $this->_db_RO->prepare($query);
    $q->execute(array());
    while($donnees = $q->fetch()) {
      $donnees["position"] = unserialize($donnees["position"]);
      $donnees["contacts"] = unserialize($donnees["contacts"]);
      $donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
      $donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
      $donnees["music_settings"] = unserialize($donnees["music_settings"]);

			$jou = new Joueur($donnees);
			//$jou->setCurrent_classement($this->get_actual_position_by_prestige($jou->id(), $class));
      $joueurs[] = $jou;
    }
    return $joueurs;
	}


	public function get_classement_ids_for_player(Joueur $joueur,$class) {

    // get position by niveau
    $position = $this->get_actual_position_by_prestige($joueur->id(),$class);

    // Array of all players ids
    $joueurs_id = array();
    // if position is <= 10 show all until position + 2 (so 15)
    $limit = 5;
    if ($position <= 10) $limit = 15;
    $query = 'SELECT id FROM joueurs ';
		if($class == "Eleve"){
			$query = $query.' WHERE (classe=\'6°\' OR classe=\'5°\' OR classe=\'4°\' OR classe=\'3°\') ';
		} elseif($class != "Toutes"){
			$query = $query.' WHERE classe=\''.$class.'\' ';
		}
		$query = $query.' ORDER BY ';
    $query = $query.'prestige DESC, niveau DESC';
    $query = $query.', xp DESC LIMIT 0,'.$limit;

    $q = $this->_db_RO->prepare($query);
    $q->execute(array());
    while($donnees = $q->fetch()) {
      $joueurs_id[] = $donnees["id"];
    }
    // Add player and those around if position is over 10 (otherwise he is already added)
    if ($position > 10) {
			$query2 = 'SELECT id FROM joueurs ';
			if($class == "Eleve"){
				$query = $query.' WHERE classe=\'6°\' OR classe=\'5°\' OR classe=\'4°\' OR classe=\'3°\' ';
			} elseif($class != "Toutes"){
				$query = $query.' WHERE classe=\''.$class.'\' ';
			}
			$query2 = $query2.' ORDER BY ';
			$query2 = $query2.'prestige DESC, niveau DESC';
			$query2 = $query2.', xp DESC LIMIT '.($position - 5).',10';

			$q2 = $this->_db_RO->prepare($query2);
			$q2->execute(array());
			while($donnees2 = $q2->fetch()) {
				$joueurs_id[] = $donnees2["id"];
			}
			//$joueurs_id[] = ''.$joueur->id();
		}

    // In every case we look for all player's friends
    if ( is_array($joueur->contacts()) ) {
      // Check each player in current classement list
      foreach( $joueur->contacts() as $friend_id ){
        // If not already inside : add if
        if( in_array($friend_id, $joueurs_id) == false ) {
          $joueurs_id[] = $friend_id;
        }
      }
    }
    return $joueurs_id;
	}

	public function get_actual_position_by_prestige($id, $class) {

		$query = 'select count(*) AS position from joueurs where prestige > (select prestige from joueurs where id=:id ';
		if($class != "Toutes"){
			$query = $query.' and classe=\''.$class.'\' ';
		}
		$query = $query.' ) ';
		if($class != "Toutes"){
			$query = $query.' and classe=\''.$class.'\' ';
		}
		$query = $query.' ORDER BY prestige DESC';
		$q = $this->_db_RO->prepare($query);

    //$q = $this->_db_RO->prepare('select count(*) AS position from joueurs where prestige >= (select prestige from joueurs where id=:id) ORDER BY prestige DESC');
    $q->execute(array(
			"id" => $id,
		));
    $donnees = $q->fetch();
    return $donnees["position"];
  }

	public function liste_joueurs_classement() {
		$q = $this->_db_RO->prepare('SELECT * FROM joueurs ORDER BY prestige DESC, niveau DESC, xp DESC');
		$q->execute(array());
		$joueurs = array();
		while($donnees = $q->fetch()) {
			$donnees["position"] = unserialize($donnees["position"]);
			$donnees["contacts"] = unserialize($donnees["contacts"]);
			$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
			$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
			$donnees["music_settings"] = unserialize($donnees["music_settings"]);
			$jou = new Joueur($donnees);
			$joueurs[] = $jou;
		}
		return $joueurs;
	}

	public function liste_pseudo() {
		$q = $this->_db_RO->prepare('SELECT pseudo FROM joueurs ORDER BY pseudo ASC');
		$q->execute(array());
		$pseudos = array();
		while($donnees = $q->fetch()) {
			$pseudos[] = $donnees["pseudo"];
		}
		return $pseudos;
	}

	public function liste_email() {
		$q = $this->_db_RO->prepare('SELECT id, email FROM joueurs where email != "" ORDER BY email ASC');
		$q->execute(array());
		$joueurs = array();
		while($donnees = $q->fetch()) {
			$jou = new Joueur($donnees);
			$joueurs[] = $jou;
		}
		return $joueurs;
	}

	public function studentsList()
    {
		$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE classe!= :other ORDER BY prestige DESC, niveau DESC, xp DESC');
		$q->execute(array(
			"other" => "Autre",
		));
		$joueurs = array();
		while($donnees = $q->fetch())
		{
			$donnees["position"] = unserialize($donnees["position"]);
			$donnees["contacts"] = unserialize($donnees["contacts"]);
			$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
			$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
			$donnees["music_settings"] = unserialize($donnees["music_settings"]);
			$jou = new Joueur($donnees);
			$joueurs[] = $jou;
		}
		return $joueurs;
	}

	public function freePlanPlayers($lvlLimit) {
		$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE classe != :other AND classe != :prof AND niveau >= :lvlLimit AND abonnement_ok = :no ORDER BY id ASC');
		$q->execute(array(
			"other" => "Autre",
			"prof" => "Prof",
			"lvlLimit" => $lvlLimit,
			"no" => 0
		));
		$joueurs = array();
		while($donnees = $q->fetch())	{
			$donnees["position"] = unserialize($donnees["position"]);
			$donnees["contacts"] = unserialize($donnees["contacts"]);
			$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
			$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
			$donnees["music_settings"] = unserialize($donnees["music_settings"]);
			$jou = new Joueur($donnees);
			$joueurs[] = $jou;
		}
		return $joueurs;
	}

	public function ranking_by_class($class)
    {
		if($class != "Toutes"){
			$filter = "WHERE classe= :class";
		} else {
			$filter = "";
		}
		$query = "SELECT * FROM joueurs ".$filter." ORDER BY prestige DESC, niveau DESC, xp DESC";
		$q = $this->_db_RO->prepare($query);
		$q->execute(array(
			"class" => $class,
		));
		$joueurs = array();
		while($donnees = $q->fetch())
		{
			$donnees["position"] = unserialize($donnees["position"]);
			$donnees["contacts"] = unserialize($donnees["contacts"]);
			$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
			$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
			$donnees["music_settings"] = unserialize($donnees["music_settings"]);
			$jou = new Joueur($donnees);
			$joueurs[] = $jou;
		}
		return $joueurs;
	}

	public function week_individual_ranking(Joueur $joueur, CombatsManager $combats_manager, $id_last_fight = 0)
	{
		$joueurs = $this->ranking_by_class($joueur->classe());
		$classement = array();
		foreach($joueurs as $joueur_cible){
			$classement[(string)$joueur_cible->id()] = $combats_manager->gain_semaine_standard($joueur_cible, $id_last_fight);
		}
		arsort($classement);
		$rang = 0;
		foreach($classement as $id_joueur => $progression_semaine){
			$rang ++;
			if((int)$id_joueur == $joueur->id()){
				return $rang;
			}
		}
	}

	public function global_individual_ranking(Joueur $joueur)
	{
		$joueurs = $this->ranking_by_class($joueur->classe());
		$rang = 0;
		foreach($joueurs as $joueur_cible){
			$rang ++;
			if($joueur_cible->id() == $joueur->id()){
				return $rang;
			}
		}
	}

	public function liste_joueurs_pseudo_alphabetique()
    {
		$q = $this->_db_RO->prepare('SELECT * FROM joueurs ORDER BY pseudo ASC');
		$q->execute(array());
		$joueurs = array();
		while($donnees = $q->fetch())
		{
			$donnees["position"] = unserialize($donnees["position"]);
			$donnees["contacts"] = unserialize($donnees["contacts"]);
			$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
			$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
			$donnees["music_settings"] = unserialize($donnees["music_settings"]);
			$joueurs[] = new Joueur($donnees);
		}
		return $joueurs;
	}

	public function liste_contacts(Joueur $joueur)
    {
		if($joueur->contacts()=="")
		{
			return false;
		}
		$joueurs = $this->liste_joueurs_pseudo_alphabetique();
		$contacts = array();
		foreach($joueurs as $joueur_cible)
		{
			if(in_array($joueur_cible->id(), $joueur->contacts()))
			{
				$contacts[] = $this->get((int)$joueur_cible->id());
			}
		}
		return $contacts;
	}

	public function classement(Joueur $joueur) {
		$joueurs = $this->liste_joueurs_classement();
		$classement = 1;
		foreach($joueurs as $j) {
			if($j->id() == $joueur->id()) {
				return $classement;
			}
			else {
				$classement ++;
			}
		}
	}

	public function potential_partners(Joueur $joueur){
		$q = $this->_db_RO->prepare('SELECT * FROM joueurs ORDER BY dernier_log DESC, niveau DESC, xp DESC');
		$q->execute(array());
		$joueurs = array();
		while($donnees = $q->fetch()){
			if($donnees["id"] != $joueur->id()){
				$donnees["position"] = unserialize($donnees["position"]);
				$donnees["contacts"] = unserialize($donnees["contacts"]);
				$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
				$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
				$donnees["music_settings"] = unserialize($donnees["music_settings"]);
				$jou = new Joueur($donnees);
				$jou->setXP(abs($joueur->niveau() - $jou->niveau()));
				$joueurs[] = $jou;
			}
		}
		usort($joueurs,array($this,"compare"));
		if(count($joueurs)>50){
			array_splice($joueurs, 50);
		}
		return $joueurs;
	}

	public function tutorial_uncomplete(){
		$q = $this->_db_RW->prepare('SELECT * FROM joueurs WHERE date_fin_tuto = :vide');
		$q->execute(array(
			"vide" => "",
		));
		$players = array();
		while($donnees = $q->fetch()){
				$donnees["position"] = unserialize($donnees["position"]);
				$donnees["contacts"] = unserialize($donnees["contacts"]);
				$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
				$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
				$donnees["music_settings"] = unserialize($donnees["music_settings"]);
				$player = new Joueur($donnees);
				$players[] = $player;
		}
		return $players;
	}

	public function tutorial_complete(){
		$q = $this->_db_RW->prepare('SELECT * FROM joueurs WHERE date_fin_tuto != :vide');
		$q->execute(array(
			"vide" => "",
		));
		$players = array();
		while($donnees = $q->fetch()){
				$donnees["position"] = unserialize($donnees["position"]);
				$donnees["contacts"] = unserialize($donnees["contacts"]);
				$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
				$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
				$donnees["music_settings"] = unserialize($donnees["music_settings"]);
				$player = new Joueur($donnees);
				$players[] = $player;
		}
		return $players;
	}

	public function SignUpBetween($periodStart, $periodEnd){
		$q = $this->_db_RO->prepare(
			'SELECT DISTINCT j.id AS playerId, j.date_inscription AS playerSignUp, j.dernier_log AS playerLastLog, j.niveau AS playerLevel, j.tuto AS playerTutorial
			FROM joueurs j
			WHERE  date_inscription>= :periodStart AND date_inscription<= :periodEnd
			ORDER BY j.id ASC');
			$q->execute(array(
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd
			));
			$result = array();
	    while ($donnees = $q->fetch()) {
	      $result[] = $donnees;
	    }
	    return $result;
			/*AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4)
			'class1' => "6°",
			'class2' => "5°",
			'class3' => "4°",
			'class4' => "3°",*/
	}

	public function ConnectedBetween($periodStart, $periodEnd){
		$q = $this->_db_RO->prepare(
			'SELECT COUNT(*) AS WAU
			FROM joueurs j
			WHERE  j.dernier_log>= :periodStart AND j.dernier_log<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4)
			ORDER BY j.id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd
			));
			$donnees = $q->fetch();
	    return (int) $donnees["WAU"];
	}

	public function reachedLimitChallenges($periodStart, $periodEnd){
		$q = $this->_db_RW->prepare(
			'SELECT j.id AS playerId, j.niveau AS playerLevel, j.classe AS playerClassroom, j.sexe AS playerSex, s.date_score AS dateScore, s.end_level AS levelReached
			FROM joueurs j
				LEFT OUTER JOIN scores s
				ON j.id = s.id_joueur
			WHERE  j.date_inscription>= :periodStart AND j.date_inscription<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4) AND j.tuto= :finished
			ORDER BY j.id ASC, s.id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd,
				'finished' => "fini"
			));
			$result = array();
			$playerId = 0;
			$challenges = array();
			$previousData = array();
	    while ($donnees = $q->fetch()) {
				if($playerId == 0){
					$playerId = $donnees["playerId"];
				}
				if($donnees["playerId"] != $playerId){
					$previousData["levelReached"] = round(array_sum($challenges) / count($challenges), 1);
					$result[] = $previousData;
					$playerId = $donnees["playerId"];
					$challenges = array();
				}
				if(count($challenges) < 3){
					if($donnees["levelReached"] != null){
						$challenges[] = $donnees["levelReached"];
					} else {
						$challenges[] = 0;
					}
					$previousData = $donnees;
				}
	    }
	    return $result;
	}

	public function reachedLimitFights($periodStart, $periodEnd){
		$q = $this->_db_RW->prepare(
			'SELECT j.id AS playerId, j.niveau AS playerLevel, j.classe AS playerClassroom, j.sexe AS playerSex, c.date_combat AS dateFight, c.issue AS issue
			FROM joueurs j
				LEFT OUTER JOIN combats c
				ON j.id = c.id_orga
			WHERE  j.date_inscription>= :periodStart AND j.date_inscription<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4) AND j.tuto= :finished
			ORDER BY j.id ASC, c.id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd,
				'finished' => "fini"
			));
			$result = array();
			$playerId = 0;
			$fights = array();
			$previousData = array();
	    while ($donnees = $q->fetch()) {
				if($playerId == 0){
					$playerId = $donnees["playerId"];
				}
				if($donnees["playerId"] != $playerId){
					$counts = array_count_values($fights);
					if(isset($counts['victoire'])){
						$previousData["issue"] = $counts['victoire'];
					} else {
						$previousData["issue"] = 0;
					}
					$result[] = $previousData;
					$playerId = $donnees["playerId"];
					$fights = array();
				}
				if(count($fights) < 6){
					if($donnees["issue"] != null){
						$fights[] = $donnees["issue"];
					} else {
						$fights[] = "unfinished";
					}
					$previousData = $donnees;
				}
	    }
	    return $result;
	}

	public function reachedLimitVideos($periodStart, $periodEnd){
		$q = $this->_db_RO->prepare(
			'SELECT j.id AS playerId, j.niveau AS playerLevel, j.classe AS playerClassroom, j.sexe AS playerSex, i.quality AS qualityVideo
			FROM joueurs j
				LEFT OUTER JOIN impressions i
				ON j.id = i.id_player
			WHERE  j.date_inscription>= :periodStart AND j.date_inscription<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4) AND j.tuto= :finished
			ORDER BY j.id ASC, i.id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd,
				'finished' => "fini"
			));
			$result = array();
			$playerId = 0;
			$videos = array();
			$previousData = array();
	    while ($donnees = $q->fetch()) {
				if($playerId == 0){
					$playerId = $donnees["playerId"];
				}
				if($donnees["playerId"] != $playerId){
					$previousData["qualityVideo"] = round(array_sum($videos)/count($videos),1);
					$result[] = $previousData;
					$playerId = $donnees["playerId"];
					$videos = array();
				}
				if(count($videos) < 2) {
					if($donnees["qualityVideo"] != null && $donnees["qualityVideo"] == "Trop bien"){
						$videos[] = 3;
					} elseif($donnees["qualityVideo"] != null && $donnees["qualityVideo"] == "Sympa"){
						$videos[] = 2;
					} elseif($donnees["qualityVideo"] != null && $donnees["qualityVideo"] == "Bof"){
						$videos[] = 1;
					} else {
						$videos[] = 0;
					}
					$previousData = $donnees;
				}
	    }
	    return $result;
	}

	public function reachedLimitSponsored($periodStart, $periodEnd){
		$q = $this->_db_RO->prepare(
			'SELECT j.id AS playerId, j.niveau AS playerLevel, j.classe AS playerClassroom, j.sexe AS playerSex, i.category AS categorySponsor
			FROM joueurs j
				LEFT OUTER JOIN invitation_codes i
				ON i.id_invited LIKE CONCAT("%:", j.id ,";%")
			WHERE  j.date_inscription>= :periodStart AND j.date_inscription<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4) AND j.tuto= :finished
			ORDER BY j.id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd,
				'finished' => "fini"
			));
			$result = array();
	    while ($donnees = $q->fetch()) {
				$result[] = $donnees;
	    }
			array_pop($result);
	    return $result;
	}

	public function playersWhoSponsored($periodStart, $periodEnd){
		$q = $this->_db_RO->prepare(
			'SELECT COUNT(*) AS countPlayersWhoSponsored
			FROM joueurs j
				LEFT OUTER JOIN invitation_codes i
				ON i.id_sponsor = j.id
			WHERE  j.date_inscription>= :periodStart AND j.date_inscription<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4) AND i.id_invited!= :empty
			ORDER BY j.id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd,
				'empty' => ""
			));
			$donnees = $q->fetch();
	    return $donnees["countPlayersWhoSponsored"];
	}

	public function playersWhoFirstSponsoredBetween($periodStart, $periodEnd){
		$q = $this->_db_RO->prepare(
			'SELECT j.id AS playerId, j.niveau AS playerLevel, j.classe AS playerClassroom, j.sexe AS playerSex, j.date_inscription AS dateSignUp, i.category AS categorySponsor, i.id_sponsor as sponsorId, i.id_invited AS sponsored
			FROM joueurs j
				INNER JOIN invitation_codes i
				ON i.id_invited LIKE CONCAT("%:", j.id ,";%")
			WHERE  j.date_inscription>= :periodStart AND j.date_inscription<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4) AND i.category= :inscription
			ORDER BY j.id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd,
				'inscription' => "inscription"
			));
			$idSponsors = array();
	    while ($donnees = $q->fetch()) {
				$sponsored = unserialize($donnees["sponsored"]);
				if($donnees["playerId"] == $sponsored[0] && !isset($idSponsors[$donnees["sponsorId"]]) ) {
					$idSponsors[$donnees["sponsorId"]] = 1;
				}
	    }
	    return count($idSponsors);
	}

	public function WonBetween($periodStart, $periodEnd){
		$NbDaysConsideredLost = 14;
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$lastLogLimit = strftime('%Y-%m-%d %H:%M:%S', time()-60*60*24*$NbDaysConsideredLost);
		$q = $this->_db_RO->prepare(
			'SELECT DISTINCT dernier_log AS playerLastLog, date_inscription AS playerSignUp	FROM joueurs
			WHERE  date_inscription>= :periodStart AND date_inscription<= :periodEnd AND dernier_log > :lastLogLimit AND (classe= :class1 OR classe= :class2 OR classe= :class3 OR classe= :class4)
			ORDER BY id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd,
				'lastLogLimit' => $lastLogLimit
			));
			$result = array();
	    while ($donnees = $q->fetch()) {
	      $result[] = $donnees;
	    }
	    return $result;
	}

	public function WonButNotKeptBetween($periodStart, $periodEnd){
		$NbDaysConsideredLost = 14;
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$lastLogLimit = strftime('%Y-%m-%d %H:%M:%S', time()-60*60*24*$NbDaysConsideredLost);
		$q = $this->_db_RO->prepare(
			'SELECT DISTINCT dernier_log AS playerLastLog, date_inscription AS playerSignUp	FROM joueurs
			WHERE  date_inscription>= :periodStart AND date_inscription<= :periodEnd AND dernier_log <= :lastLogLimit AND (classe= :class1 OR classe= :class2 OR classe= :class3 OR classe= :class4)
			ORDER BY id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd,
				'lastLogLimit' => $lastLogLimit
			));
			$result = array();
	    while ($donnees = $q->fetch()) {
	      $result[] = $donnees;
	    }
	    return $result;
	}

	public function LostBetween($periodStart, $periodEnd){
		$NbDaysConsideredLost = 14;
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$lastLogLimit = strftime('%Y-%m-%d %H:%M:%S', time()-60*60*24*$NbDaysConsideredLost);
		$q = $this->_db_RO->prepare(
			'SELECT DISTINCT dernier_log AS playerLastLog,	date_inscription AS playerSignUp FROM joueurs
			WHERE  date_inscription < :periodStart AND dernier_log>= :periodStart AND dernier_log<= :lastLogLimit AND (classe= :class1 OR classe= :class2 OR classe= :class3 OR classe= :class4)
			ORDER BY id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'lastLogLimit' => $lastLogLimit
			));
			$result = array();
	    while ($donnees = $q->fetch()) {
	      $result[] = $donnees;
	    }
	    return $result;
	}

	public function maxSessionInterval() {
    $q = $this->_db_RO->prepare(
			'SELECT e.date_exercise AS dateEx, e.id_joueur AS playerId, SUBSTR(j.date_inscription, 1, 10) as dateSignUp
			FROM exercises e
			INNER JOIN joueurs j
			ON j.id = e.id_joueur
			WHERE date_exercise>= :periodStart AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4)
			ORDER BY j.id ASC, e.id ASC');
    $q->execute(array(
			'class1' => "6°",
			'class2' => "5°",
			'class3' => "4°",
			'class4' => "3°",
			'periodStart' => "2016-10-10 00:00:00",
		));
		$maxSessionIntervals = array();
		$maxPlayer = 0;
		$playerId = 0;
		$lastSession = "";
    while ($donnees = $q->fetch()) {
			if($playerId == 0){
				$playerId = $donnees["playerId"];
			}
			if($donnees["playerId"] != $playerId){
				if($maxPlayer > 0){
					$maxSessionIntervals [] = $maxPlayer;
				}
				$maxPlayer = 0;
				$playerId = $donnees["playerId"];
				$lastSession = "";
			} else {
				if($lastSession != ""){
					$intervalSession = round((strtotime($donnees["dateEx"]) - strtotime($lastSession))/(60*60*24), 1);
					if($intervalSession > $maxPlayer){
						$maxPlayer = $intervalSession;
					}
				}
				$lastSession = $donnees["dateEx"];
			}
    }
		$percentile = $this->percentile($maxSessionIntervals, 95);
    return round($percentile, 1);
  }

	public function percentile($data, $percentile){
    if( 0 < $percentile && $percentile < 1 ) {
        $p = $percentile;
    } else if( 1 < $percentile && $percentile <= 100 ) {
        $p = $percentile * .01;
    } else {
        return "";
    }
    $count = count($data);
    $allindex = ($count-1)*$p;
    $intvalindex = intval($allindex);
    $floatval = $allindex - $intvalindex;
    sort($data);
    if(!is_float($floatval)){
        $result = $data[$intvalindex];
    }else {
        if($count > $intvalindex+1)
            $result = $floatval*($data[$intvalindex+1] - $data[$intvalindex]) + $data[$intvalindex];
        else
            $result = $data[$intvalindex];
    }
    return $result;
	}

	public function weeklyActiveUsers($periodStart, $periodEnd) {
    $q = $this->_db_RO->prepare(
			'SELECT DISTINCT e.id_joueur AS playerId
			FROM exercises e
			INNER JOIN joueurs j
			ON j.id = e.id_joueur
			WHERE  date_exercise>= :periodStart AND date_exercise<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4)
			ORDER BY j.id ASC');
    $q->execute(array(
			'class1' => "6°",
			'class2' => "5°",
			'class3' => "4°",
			'class4' => "3°",
			'periodStart' => $periodStart,
			'periodEnd' => $periodEnd
		));
		$total = 0;
    while ($donnees = $q->fetch()) {
      $total ++;
    }
    return $total;
  }

	public function activeUsers($dateStart, $dateEnd) {
    $q = $this->_db_RO->prepare(
			'SELECT DISTINCT j.id AS playerId
			FROM joueurs j
			LEFT OUTER JOIN exercises e
			ON j.id = e.id_joueur
			WHERE  (date_exercise>= :dateStart AND date_exercise<= :dateEnd) OR (date_inscription>= :dateStart AND date_inscription<= :dateEnd)
			ORDER BY j.id ASC');
    $q->execute(array(
			'dateStart' => $dateStart,
			'dateEnd' => $dateEnd
		));
		$total = 0;
    while ($donnees = $q->fetch()) {
      $total ++;
    }
    return $total;
  }

	public function churn($periodStart, $periodEnd, $maxInactivityPeriod) {
		$dateStart = strftime('%Y-%m-%d %H:%M:%S', strtotime($periodEnd) - $maxInactivityPeriod*24*60*60);
    $q = $this->_db_RO->prepare(
			'SELECT DISTINCT e.id_joueur AS playerId, j.date_inscription AS dateInscription, j.tuto AS tutorial
			FROM exercises e
			INNER JOIN joueurs j
			ON j.id = e.id_joueur
			WHERE  date_exercise>= :dateStart AND date_exercise<= :periodEnd AND date_inscription< :periodStart AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4) AND j.tuto= :tutorial
			ORDER BY j.id ASC');
    $q->execute(array(
			'class1' => "6°",
			'class2' => "5°",
			'class3' => "4°",
			'class4' => "3°",
			'dateStart' => $dateStart,
			'periodStart' => $periodStart,
			'periodEnd' => $periodEnd,
			'tutorial' => "fini"
		));
		$activeUsersEndPeriod = 0;
    while ($donnees = $q->fetch()) {
      $activeUsersEndPeriod ++;
    }
		$churn = max(0, $this->activeUsers($periodStart, $maxInactivityPeriod) - $activeUsersEndPeriod);
    return $churn;
  }

	public function acquisitionSplit($periodStart, $periodEnd){
		$q = $this->_db_RO->prepare(
			'SELECT j.id AS playerId, j.niveau AS playerLevel, j.classe AS playerClassroom, j.sexe AS playerSex, i.category AS categorySponsor, c.idTeacher AS idTeacher
			FROM joueurs j
				LEFT OUTER JOIN invitation_codes i
				ON i.id_invited LIKE CONCAT("%:", j.id ,";%")
				LEFT OUTER JOIN classrooms c
				ON c.idStudents LIKE CONCAT("%:", j.id ,";%")
			WHERE  j.date_inscription>= :periodStart AND j.date_inscription<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4)
			ORDER BY j.id ASC');
		$q->execute(array(
			'class1' => "6°",
			'class2' => "5°",
			'class3' => "4°",
			'class4' => "3°",
			'periodStart' => $periodStart,
			'periodEnd' => $periodEnd
		));
		$result = array();
	  while ($donnees = $q->fetch()) {
			$result[] = $donnees;
	  }
	  return $result;
	}

	public function initial_level_ranking(Joueur $currentPlayer, Joueur $facticePlayer){
		$q = $this->_db_RO->prepare('SELECT * FROM joueurs ORDER BY niveau DESC, xp DESC');
		$q->execute(array());
		$players = array();
		$initialRanking = 0;
		$i = 0;
		while($donnees = $q->fetch()){
			if($donnees["id"] != $currentPlayer->id()){ //don't take current player
				$i++;
				$donnees["position"] = unserialize($donnees["position"]);
				$donnees["contacts"] = unserialize($donnees["contacts"]);
				$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
				$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
				$donnees["music_settings"] = unserialize($donnees["music_settings"]);
				$player = new Joueur($donnees);
				if($initialRanking == 0 && $player->niveau() <= $facticePlayer->niveau() && $player->xp() <= $facticePlayer->xp()){
					$initialRanking = $i;
					$players[] = $facticePlayer;
				}
				$players[] = $player;
			}
		}
		if($initialRanking == 0){
			$initialRanking = $i+1;
		}
		$beatenPlayers = sizeof($players) - $initialRanking + 1; //Include the player himself
		return min(100,ceil(100*$beatenPlayers/sizeof($players)));
	}

	public function current_level_ranking(Joueur $currentPlayer){
		$q = $this->_db_RO->prepare('SELECT * FROM joueurs ORDER BY niveau DESC, xp DESC');
		$q->execute(array());
		$players = array();
		$currentRanking = 0;
		$i = 0;
		while($donnees = $q->fetch()){
			$i++;
			$donnees["position"] = unserialize($donnees["position"]);
			$donnees["contacts"] = unserialize($donnees["contacts"]);
			$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
			$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
			$donnees["music_settings"] = unserialize($donnees["music_settings"]);
			$player = new Joueur($donnees);
			if($donnees["id"] == $currentPlayer->id()){ //don't take current player
				$currentRanking = $i;
			}
			$players[] = $player;
		}
		$beatenPlayers = sizeof($players) - $currentRanking + 1; //Include the player himself
		return min(100,ceil(100*$beatenPlayers/sizeof($players)));
	}

	public function get_by_suivi_link( $suivi_link ) {
		$q = $this->_db_RO->prepare('SELECT * FROM joueurs WHERE suivi_link=:suivi_link;');
		$q->execute(array(
				'suivi_link' => $suivi_link
		));
		$donnees = $q->fetch();
		if($donnees){
			$donnees["position"] = unserialize($donnees["position"]);
			$donnees["contacts"] = unserialize($donnees["contacts"]);
			$donnees["histoires_vues"] = unserialize($donnees["histoires_vues"]);
			$donnees["bulles_daide_vues"] = unserialize($donnees["bulles_daide_vues"]);
			$donnees["music_settings"] = unserialize($donnees["music_settings"]);
			return new Joueur($donnees);
		} else {
			return null;
		}
	}

	public function compare($a, $b){
		if ($a->xp()<2 && $b->xp()<2) {
			return strtotime($b->dernier_log()) - strtotime($a->dernier_log());
		} elseif ($a->xp()<2) {
			return -1;
		} elseif ($b->xp()<2) {
			return 1;
		} elseif ($a->xp() == $b->xp()) {
			return strtotime($b->dernier_log()) - strtotime($a->dernier_log());
		} else {
			return $a->xp() - $b->xp();
		}
	}

}
