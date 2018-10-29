<?php

class CombatsManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
	}

	public function add(Combat $combat) //ajoute un combat dans la BDD
    {
		$ordre = array($combat->id_orga());
		$id_prets = array($combat->id_orga());
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = strftime('%Y-%m-%d %H:%M:%S',time());
		$q = $this->_db_RW->prepare('INSERT INTO combats(id_orga, id_invites, id_monstre, ordre, id_prets, copies_joueurs, copie_monstre, endu_monstre_restante, deroulement, issue, prestige, date_combat, date_invitations, vu) VALUES(:id_orga, :id_invites, :id_monstre, :ordre, :id_prets, :copies_joueurs, :copie_monstre, :endu_monstre_restante, :deroulement, :issue, :prestige, :date_combat, :date_invitations, :vu)');
		$q->execute(array(
			'id_orga' => $combat->id_orga(),
			'id_invites' => "",
			'id_monstre' => $combat->id_monstre(),
			'ordre' => serialize($ordre),
			'id_prets' => serialize($id_prets),
			'copies_joueurs' => "",
			'copie_monstre' => "",
			'endu_monstre_restante' => 0,
			'deroulement' => "",
			'issue' => "",
			'prestige' => 0,
			'date_combat' => "",
			'date_invitations' => "",
			'vu' => "",
			));
		$combat->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),
      		'id_invites' => "",
			'ordre' => $ordre,
			'id_prets' => $id_prets,
			'copies_joueurs' => "",
			'copie_monstre' => "",
			'endu_monstre_restante' => 0,
			'deroulement' => "",
			'issue' => "",
			'prestige' => 0,
			'date_combat' => "",
			'date_invitations' => "",
			'vu' => "",
    	));
	}

	public function exists($id_joueur, $id_monstre) //Permet de tester si un combat existe basé sur l'id du joueur (orga ou invité) et l'id du monstre
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE (id_orga= :id_orga OR id_invites LIKE :id_serialize) AND id_monstre= :id_monstre');
		$q->execute(array(
 		'id_orga' => $id_joueur,
		'id_serialize' => '%i:'.$id_joueur.';%',
		'id_monstre' => $id_monstre,
	 	));
		return (bool) $q->fetch();
	}

	public function participe_combat($id_joueur, $id_combat)
	{
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE (id_orga= :id_orga OR id_invites LIKE :id_serialize) AND id= :id_combat');
		$q->execute(array(
 		'id_orga' => $id_joueur,
		'id_serialize' => '%i:'.$id_joueur.';%',
		'id_combat' => $id_combat,
	 	));
		return (bool) $q->fetch();
	}

	public function get($id_joueur, $id_monstre) //Permet de récupérer un combat basé sur l'id du joueur et l'id du monstre
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE (id_orga= :id_orga OR id_invites LIKE :id_serialize) AND id_monstre= :id_monstre');
		$q->execute(array(
 		'id_orga' => $id_joueur,
		'id_serialize' => '%i:'.$id_joueur.';%',
		'id_monstre' => $id_monstre,
	 	));
		$donnees = $q->fetch();
		//On désérialise les arrays
		$donnees["id_invites"] = unserialize($donnees["id_invites"]);
		$donnees["ordre"] = unserialize($donnees["ordre"]);
		$donnees["id_prets"] = unserialize($donnees["id_prets"]);
		$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
		$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
		$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
		$donnees["vu"] = unserialize($donnees["vu"]);
		return new Combat($donnees);
	}

	public function exists_id($id) //Permet de tester si un combat existe basé sur son id
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE id= :id');
		$q->execute(array(
 		'id' => $id,
	 	));
		return (bool) $q->fetch();
	}

	public function get_id($id) //Permet de récupérer un combat basé sur son id
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE id= :id');
		$q->execute(array(
 		'id' => $id,
	 	));
		$donnees = $q->fetch();
		//On désérialise les arrays
		$donnees["id_invites"] = unserialize($donnees["id_invites"]);
		$donnees["ordre"] = unserialize($donnees["ordre"]);
		$donnees["id_prets"] = unserialize($donnees["id_prets"]);
		$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]); //Fonction spéciale pour tenir compte des caractères spéciaux
		$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]); //Fonction spéciale pour tenir compte des caractères spéciaux
		$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
		$donnees["vu"] = unserialize($donnees["vu"]);
		$combat = new Combat($donnees);
		return $combat;
	}

	//Return the count of all finished fights
  public function count_finished(Joueur $joueur) {
		$q = $this->_db_RO->prepare('SELECT COUNT(*) AS count_fights FROM combats WHERE (id_orga= :id_orga OR id_invites LIKE :id_serialize) AND issue!= :issue');
		$q->execute(array(
			'id_orga' => $joueur->id(),
			'id_serialize' => '%i:'.$joueur->id().';%',
			'issue' => "",
		));
		$donnees = $q->fetch();
		$count = $donnees["count_fights"];
		return (int) $count;
  }

	public function count_finished_today(Joueur $joueur) {
		setlocale(LC_TIME, 'fr_FR');
    date_default_timezone_set('Europe/Paris');
		$today = strftime('%Y-%m-%d', time());
		$q = $this->_db_RO->prepare('SELECT COUNT(*) AS count_fights FROM combats WHERE (id_orga= :id_orga OR id_invites LIKE :id_serialize) AND deroulement!= :deroulement AND date_combat LIKE :today');
		$q->execute(array(
			'id_orga' => $joueur->id(),
			'id_serialize' => '%i:'.$joueur->id().';%',
			'deroulement' => "",
			'today' => $today." %"
		));
		$donnees = $q->fetch();
		$count = $donnees["count_fights"];
		return (int) $count;
  }

	public function combat_valide(Joueur $joueur, $id) //Vérifie que le joueur n'a pas trafiqué l'URL
	{
		if($this->exists_id($id) && $this->participe_combat($joueur->id(), $id)) //Si le combat existe et que le joueur fait partie du combat
		{
			$combat = $this->get_id($id);
			if($combat->tous_prets()) //Si tout le monde est prêt
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function update(Combat $combat)
    {
		//On serialize les arrays
		if($combat->id_invites()){$id_invites = serialize($combat->id_invites());}else{$id_invites = "";}
		if($combat->ordre()){$ordre = serialize($combat->ordre());}else{$ordre = "";}
		if($combat->id_prets()){$id_prets = serialize($combat->id_prets());}else{$id_prets = "";}
		if($combat->copies_joueurs())
		{
			$copies_joueurs = $combat->copies_joueurs();
			$combat->setCopies_joueurs(serialize($copies_joueurs));
		}
		if($combat->copie_monstre())
		{
			$monstre = $combat->copie_monstre();
			$combat->setCopie_monstre(serialize($monstre));
		}
		if($combat->date_invitations()){$date_invitations = serialize($combat->date_invitations());}else{$date_invitations = "";}
		if($combat->vu()){$vu = serialize($combat->vu());}else{$vu = "";}

		$q = $this->_db_RW->prepare('UPDATE combats SET id_orga=:id_orga, id_invites=:id_invites, id_monstre=:id_monstre, ordre=:ordre, id_prets=:id_prets, copies_joueurs=:copies_joueurs,  copie_monstre=:copie_monstre, endu_monstre_restante=:endu_monstre_restante, deroulement=:deroulement, issue=:issue, prestige=:prestige, date_combat=:date_combat, date_invitations=:date_invitations, vu=:vu WHERE id=:id');
		$q->execute(array(
			'id' => $combat->id(),
			'id_orga' => $combat->id_orga(),
			'id_invites' => $id_invites,
			'id_monstre' => $combat->id_monstre(),
			'ordre' => $ordre,
			'id_prets' => $id_prets,
			'copies_joueurs' => $combat->copies_joueurs(),
			'copie_monstre' => $combat->copie_monstre(),
			'endu_monstre_restante' => $combat->endu_monstre_restante(),
			'deroulement' => $combat->deroulement(),
			'issue' => $combat->issue(),
			'prestige' => $combat->prestige(),
			'date_combat' => $combat->date_combat(),
			'date_invitations' => $date_invitations,
			'vu' => $vu,
			));
	}

	public function liste_invitations(Joueur $joueur) {
		//Les joueurs ne verront que les combats non finis où ils participent ou les combats commencés pour lesquels c'est à eux de jouer
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue=:vide AND id_invites != :vide AND (id_orga=:id_joueur OR id_invites LIKE :id_serialize)');
		$q->execute(array(
			'vide' => "",
			'id_joueur' => $joueur->id(),
			'id_serialize' => '%i:'.$joueur->id().';%',
		));
		$combats = array();
		while($donnees = $q->fetch())	{
			//On désérialise les arrays
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combats[] = new Combat($donnees);
		}
		/* foreach($combats as $combat){
			if($combat->deroulement() != "" && $joueur->id() != $combat->prochain_a_jouer()){ //On supprime l'invitation si le combat est déjà entamé et le joueur n'est pas le prochain à jouer
				array_splice($combats, array_search($combat, $combats), 1);
			}
		} */
		return $combats;
	}

	public function notifications(Joueur $joueur)
	{
		/*
		$liste_invitations = $this->liste_invitations($joueur); //On récupère toutes les invitations en cours du joueur
		foreach($liste_invitations as $invit)
		{
			if( $joueur->id() == $invit->prochain_a_jouer() && $invit->tous_prets()) //Si le joueur est le prochain à jouer et que tout le monde est prêt
			{
				$notif++;
			}
			elseif(!in_array($joueur->id(), $invit->id_prets(), true)) //Si le joueur est invité mais qu'il n'a pas encore répondu s'il était prêt
			{
				$notif++;
			}
		} */
		$notif = 0;
		$liste_combats_passes = $this->liste_combats_passes($joueur);
		foreach($liste_combats_passes as $combat) //On ajoute une notif si le joueur n'a pas encore vu le combat
		{
			if($combat->vu() == "" || !in_array($joueur->id(), $combat->vu()))
			{
				$notif++;
			}
		}
		return $notif;
	}

	public function combats_passes_equipe()
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue != :vide AND id_invites != :vide ORDER BY date_combat DESC');
		$q->execute(array(
			'vide' => "",
		));
		$combats = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combats[] = new Combat($donnees);
		}
		return $combats;
	}

	public function fight_ajax_aborted()
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue = :vide AND deroulement != :vide ORDER BY date_combat DESC');
		$q->execute(array(
			'vide' => "",
		));
		$fights = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$fights[] = new Combat($donnees);
		}
		return $fights;
	}

	public function combats_passes_equipe_semaine()
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue != :vide AND id_invites != :vide AND WEEKOFYEAR(date_combat) = WEEKOFYEAR(NOW())');
		$q->execute(array(
			'vide' => "",
		));
		$combats = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combats[] = new Combat($donnees);
		}
		return $combats;
	}

	public function liste_combats_passes(Joueur $joueur)
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue != :vide AND (id_orga=:id_joueur OR id_invites LIKE :id_serialize) ORDER BY date_combat DESC');
		$q->execute(array(
			'vide' => "",
			'id_joueur' => $joueur->id(),
			'id_serialize' => '%i:'.$joueur->id().';%',
		));
		$combats = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combats[] = new Combat($donnees);
		}
		return $combats;
	}

	public function list_fights_chronogically(Joueur $joueur)
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue != :vide AND (id_orga=:id_joueur OR id_invites LIKE :id_serialize) ORDER BY date_combat ASC');
		$q->execute(array(
			'vide' => "",
			'id_joueur' => $joueur->id(),
			'id_serialize' => '%i:'.$joueur->id().';%',
		));
		$combats = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combats[] = new Combat($donnees);
		}
		return $combats;
	}

	public function liste_combats_passes_semaine(Joueur $joueur) //Permet de récupérer toutes les combats livrés cette semaine
    {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue != :vide AND (id_orga=:id_joueur OR id_invites LIKE :id_serialize) AND WEEKOFYEAR(date_combat)=WEEKOFYEAR(NOW())');
		$q->execute(array(
			'vide' => "",
			'id_joueur' => $joueur->id(),
			'id_serialize' => '%i:'.$joueur->id().';%',
		));
		$combats = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combats[] = new Combat($donnees);
		}
		return $combats;
	}

	public function dernier_combat(Joueur $joueur)
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE id_orga=:id_joueur OR id_invites LIKE :id_serialize ORDER BY id DESC LIMIT 0, 1');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			'id_serialize' => '%i:'.$joueur->id().';%',
		));
		$donnees = $q->fetch();
		//On désérialise les arrays
		$donnees["id_invites"] = unserialize($donnees["id_invites"]);
		$donnees["ordre"] = unserialize($donnees["ordre"]);
		$donnees["id_prets"] = unserialize($donnees["id_prets"]);
		$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
		$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
		$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
		$donnees["vu"] = unserialize($donnees["vu"]);
		$combat = new Combat($donnees);
		return $combat;
	}

	public function last_fights(Joueur $joueur)
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue != :vide AND (id_orga=:id_joueur OR id_invites LIKE :id_serialize) ORDER BY date_combat DESC LIMIT 0, 4');
		$q->execute(array(
			'vide' => '',
			'id_joueur' => $joueur->id(),
			'id_serialize' => '%i:'.$joueur->id().';%',
		));
		$combats = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combats[] = new Combat($donnees);
		}
		return $combats;
	}

	public function best_teamates(Joueur $joueur)
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue != :vide AND id_invites != :vide AND (id_orga=:id_joueur OR id_invites LIKE :id_serialize) ORDER BY date_combat DESC');
		$q->execute(array(
			'vide' => '',
			'id_joueur' => $joueur->id(),
			'id_serialize' => '%i:'.$joueur->id().';%',
		));
		$combats = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combats[] = new Combat($donnees);
		}
		$teamates = array();
		foreach($combats as $combat){
			if(isset($teamates[$combat->id_orga()])){
				$teamates[$combat->id_orga()] ++;
			} elseif($combat->id_orga() != $joueur->id()) {
				$teamates[$combat->id_orga()] = 1;
			}
			foreach($combat->id_invites() as $id_invite){
				if(isset($teamates[$id_invite])){
					$teamates[$id_invite] ++;
				} elseif($id_invite != $joueur->id()) {
					$teamates[$id_invite] = 1;
				}
			}
		}
		arsort($teamates);
		$teamates = array_slice($teamates, 0, 5, true);
		return $teamates;
	}

	public function nb_victoires_derniers_combats_solo(Joueur $joueur)
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue != :vide AND id_orga=:id_joueur AND id_invites =:vide ORDER BY date_combat DESC LIMIT 0,2');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			'vide' => '',
		));
		$nb_victoires = 0;
		while($donnees = $q->fetch())
		{
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combat = new Combat($donnees);
			if($combat->issue() == "victoire")
			{
				$nb_victoires ++;
			}
		}
		return $nb_victoires;
	}

	public function nb_victoires_derniers_combats_multi(Joueur $joueur)
    {
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE issue != :vide AND (id_orga=:id_joueur OR id_invites LIKE :id_serialize) AND id_invites != :vide ORDER BY date_combat DESC LIMIT 0,2');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			'id_serialize' => '%i:'.$joueur->id().';%',
			'vide' => '',
		));
		$nb_victoires = 0;
		while($donnees = $q->fetch())
		{
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combat = new Combat($donnees);
			if($combat->issue() == "victoire")
			{
				$nb_victoires ++;
			}
		}
		return $nb_victoires;
	}

	public function delete(Combat $combat) //Supprime un combat
    {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = time();
		$mois = strftime('%Y-%m',$date);
		$q = $this->_db_RW->prepare('DELETE FROM combats WHERE id =:id');
		$q->execute(array(
			'id' => $combat->id(),
		));
	}

	public function delete_vieux_combats() //Supprime tous les combats du mois dernier
    {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = time();
		$mois = strftime('%Y-%m',$date);
		$q = $this->_db_RW->prepare('DELETE FROM combats WHERE date_combat NOT LIKE :date AND issue != :issue');
		$q->execute(array(
			'date' => $mois."%",
			'issue' => "",
		));
	}

	public function delete_all(Joueur $joueur) {
		$params = array(
	        'id_orga' => $joueur->id(),
	    );
      $q = $this->_db_RW->prepare('DELETE FROM combats WHERE id_orga=:id_orga');
      $q->execute($params);
  }

	public function bilan_saison($id_joueur1, $id_joueur2) //Compte tous les combats où les 2 joueurs ont participé
	{
		$q = $this->_db_RO->prepare('SELECT COUNT(*) AS nb_combats FROM combats WHERE (id_orga= :id_joueur1 OR id_invites LIKE :id_joueur1_seri) AND (id_orga= :id_joueur2 OR id_invites LIKE :id_joueur2_seri) AND issue != :issue');
		$q->execute(array(
			'id_joueur1' => $id_joueur1,
			'id_joueur1_seri' => '%i:'.$id_joueur1.';%',
			'id_joueur2' => $id_joueur2,
			'id_joueur2_seri' => '%i:'.$id_joueur2.';%',
			'issue' => '',
			));
		$donnees = $q->fetch();
		return $donnees["nb_combats"];
	}

	public function gain_semaine_standard(Joueur $joueur, $id_exclude = 0) //Permet de calculer le gain par semaine en s'affranchissant du facteur niveau
	{
		$gain = 0;
		$combats = $this->liste_combats_passes_semaine($joueur);
		foreach($combats  as $combat)
		{
			if($combat->id() != $id_exclude){
				$gain += $combat->prestige();
			}
		}
		$gain = $gain/(2 * pow($joueur->niveau(), 0.8) + 5);
		return round($gain, 1);
	}

	public function points_coequipier_global(Joueur $joueur) //Permet de calculer le gain de prestige en "coéquipier" sur la saison (pas de standardisation)
	{
		$gain = 0;
		$combats = $this->liste_combats_passes($joueur);
		foreach($combats  as $combat)
		{
			if($joueur->id() != $combat->id_orga()) //Seulement si le joueur était invité
			{
				$gain += $combat->prestige();
			}
		}
		return round($gain);
	}

	public function points_coequipier_semaine(Joueur $joueur) //Permet de calculer le gain de prestige en "coéquipier" sur la semaine (standardisation)
	{
		$gain = 0;
		$combats = $this->liste_combats_passes_semaine($joueur);
		foreach($combats  as $combat)
		{
			if($joueur->id() != $combat->id_orga()) //Seulement si le joueur était invité
			{
				$gain += $combat->prestige();
			}
		}
		$gain = $gain/(2 * pow($joueur->niveau(), 0.8) + 5);
		return round($gain, 1);
	}

	public function points_equipe_global() //Permet de voir le classement par équipe au global (pas de standardisation)
	{
		$combats = $this->combats_passes_equipe();
		$scores_equipes = array();
		foreach($combats  as $combat) //On récupère les différentes équipes
		{
			$ordre = $combat->ordre();
			sort($ordre);
			$ordre = implode(",", $ordre);
			if(isset($scores_equipes[$ordre])) //Si cette équipe a déjà un score
			{
				$scores_equipes[$ordre] += $combat->prestige();
			}
			else //Si cette équipe n'a pas encore de score
			{
				$scores_equipes[$ordre] = $combat->prestige();
			}
		}
		return $scores_equipes;
	}

	public function points_equipe_semaine() //Permet de voir le classement par équipe à la semaine (standardisation)
	{
		$combats = $this->combats_passes_equipe_semaine();
		$scores_equipes = array();
		foreach($combats  as $combat) //On récupère les différentes équipes
		{
			$ordre = $combat->ordre();
			sort($ordre);
			$ordre = implode(",", $ordre);
			if(isset($scores_equipes[$ordre])) //Si cette équipe a déjà un score
			{
				$monstre = $combat->copie_monstre();
				$gain = $combat->prestige()/(2 * pow($monstre->niveau(), 0.8) + 5);
				$scores_equipes[$ordre] += $gain;
			}
			else //Si cette équipe n'a pas encore de score
			{
				$monstre = $combat->copie_monstre();
				$gain = $combat->prestige()/(2 * pow($monstre->niveau(), 0.8) + 5);
				$scores_equipes[$ordre] = $gain;
			}
		}
		return $scores_equipes;
	}

	public function get_bugged(){
		$q = $this->_db_RO->prepare('SELECT * FROM combats WHERE deroulement LIKE :deroulement');
		$q->execute(array(
 			'deroulement' => "changer_joueur, , , , , ;changer_joueur, , , , , ;changer_joueur, , , , , ;changer_joueur, , , , , ;changer_joueur, , , , , ;changer_joueur, , , , , ;changer_joueur, , , , , ;changer_joueur, , , , , ;changer_joueur, , , , , ;%",
	 	));
		$combats = array();
		while($donnees = $q->fetch())	{
			$donnees["id_invites"] = unserialize($donnees["id_invites"]);
			$donnees["ordre"] = unserialize($donnees["ordre"]);
			$donnees["id_prets"] = unserialize($donnees["id_prets"]);
			$donnees["copies_joueurs"] = $this->deserialiser($donnees["copies_joueurs"]);
			$donnees["copie_monstre"] = $this->deserialiser($donnees["copie_monstre"]);
			$donnees["date_invitations"] = unserialize($donnees["date_invitations"]);
			$donnees["vu"] = unserialize($donnees["vu"]);
			$combats[] = new Combat($donnees);
		}
		return $combats;
	}

	public function deserialiser($string)
	{
		if($string != "")
		{
			$string = preg_replace_callback(
				'!s:(\d+):"(.*?)";!s',
				function ($matches) {
					if ( isset( $matches[2] ) )
						return 's:'.strlen($matches[2]).':"'.$matches[2].'";';
				},
				$string
			);
			return unserialize($string);
		}
		else
		{
			return $string;
		}
	}







}
