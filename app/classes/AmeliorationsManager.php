<?php

class AmeliorationsManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
	{
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
	}

	public function add(Amelioration $amelioration) //Ajoute une amelioration dans la BDD
    {
		$vues = array($amelioration->id_joueur());
		$vues = serialize($vues);
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = strftime('%Y-%m-%d %H:%M:%S',time());
		$q = $this->_db_RW->prepare('INSERT INTO ameliorations(id_joueur, titre, descriptif, votants, nb_votes, vues, statut, date_soumission) VALUES(:id_joueur, :titre, :descriptif, :votants, :nb_votes, :vues, :statut, :date_soumission)');
		$q->execute(array(
			'id_joueur' => $amelioration->id_joueur(),
			'titre' => $amelioration->titre(),
			'descriptif' => $amelioration->descriptif(),
			'votants' => "",
			'nb_votes' => 0,
			'vues' => $vues,
			'statut' => $amelioration->statut(),
			'date_soumission' => $date
			));
		$amelioration->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),
			'date_soumission' => $date,
			'nb_votes' => 0,
    	));
	}

	public function update(Amelioration $amelioration) //Permet de mettre à jour une amelioration
    {
		//On serialize les arrays
		if($amelioration->votants()){$votants = serialize($amelioration->votants());}else{$votants = "";}
		if($amelioration->vues()){$vues = serialize($amelioration->vues());}else{$vues = "";}
		$q = $this->_db_RW->prepare('UPDATE ameliorations SET votants=:votants, nb_votes=:nb_votes, vues=:vues, statut=:statut WHERE id=:id');
		$q->execute(array(
			'votants' => $votants,
			'nb_votes' => $amelioration->nb_votes(),
			'vues' => $vues,
			'statut' => $amelioration->statut(),
			'id' => $amelioration->id(),
			));
	}

	public function delete(Amelioration $amelioration) //Supprime une amelioration
    {
		$q = $this->_db_RW->prepare('DELETE FROM ameliorations WHERE id=:id');
		$q->execute(array(
			'id' => $amelioration->id(),
			));
	}

	public function delete_all(Joueur $joueur) //Supprime tous les améliorations d'un joueur (s'il supprime son compte)
    {
		$q = $this->_db_RW->prepare('DELETE FROM ameliorations WHERE id_joueur=:id_joueur');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			));
	}

	public function get_all_en_cours() //Permet de récupérer toutes les améliorations en cours
    {
		$q = $this->_db_RO->prepare('SELECT * FROM ameliorations WHERE statut= :statut ORDER BY id DESC');
		$q->execute(array(
 			'statut' => "en_cours",
	 		));
		$ameliorations = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["votants"] = unserialize($donnees["votants"]);
			$donnees["vues"] = unserialize($donnees["vues"]);
			$ameliorations[] = new Amelioration($donnees);
		}
		return $ameliorations;
	}

	public function get_all_realisees() //Permet de récupérer toutes les améliorations déjà réalisées
    {
		$q = $this->_db_RO->prepare('SELECT * FROM ameliorations WHERE statut= :statut1 OR statut= :statut2 ORDER BY id DESC');
		$q->execute(array(
 			'statut1' => "realisee",
			'statut2' => "refusee",
	 		));
		$ameliorations = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["votants"] = unserialize($donnees["votants"]);
			$donnees["vues"] = unserialize($donnees["vues"]);
			$ameliorations[] = new Amelioration($donnees);
		}
		return $ameliorations;
	}

	public function get_joueur($id_joueur) //Permet de récupérer toutes les amélioration d'un joueur
	{
		$q = $this->_db_RO->prepare('SELECT * FROM ameliorations WHERE id_joueur= :id_joueur ORDER BY nb_votes DESC');
		$q->execute(array(
 			'id_joueur' => $id_joueur,
	 		));
		$ameliorations = array();
		while($donnees = $q->fetch())
		{
			//On désérialise les arrays
			$donnees["votants"] = unserialize($donnees["votants"]);
			$donnees["vues"] = unserialize($donnees["vues"]);
			$ameliorations[] = new Amelioration($donnees);
		}
		return $ameliorations;
	}

	public function get($id) //Permet de récupérer une amélioration par son id
	{
		$q = $this->_db_RO->prepare('SELECT * FROM ameliorations WHERE id= :id');
		$q->execute(array(
 			'id' => $id,
	 		));
		$donnees = $q->fetch();
		//On désérialise les arrays
		$donnees["votants"] = unserialize($donnees["votants"]);
		$donnees["vues"] = unserialize($donnees["vues"]);
		$amelioration = new Amelioration($donnees);
		return $amelioration;
	}

}
