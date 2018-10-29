<?php

class SaisonsManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }


  public function add(Saison $saison) //Ajoute une saison dans la bdd
    {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date_saison = new DateTime();
		$date_saison->modify('-1 month');
		$date_saison= $date_saison->getTimestamp();
		$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
		$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
		$date_saison = $mois[date("n", $date_saison)]." ".date("Y", $date_saison);
		$q = $this->_db_RW->prepare('INSERT INTO saisons(id, nom, classement, prestige, vues) VALUES(:id, :nom, :classement, :prestige, :vues)');
		$q->execute(array(
			'id' => $saison->id(),
			'nom' => $date_saison,
			'classement' => $saison->classement(),
			'prestige' => $saison->prestige(),
			'vues' => $saison->vues(),
			));
		$saison->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),
			'nom' => $date_saison,
    	));
	}

	public function nouvelle_saison() //Teste si on entre dans une nouvelle saison ou pas
    {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date_saison = new DateTime();
		$date_saison->modify('-1 month');
		$date_saison= $date_saison->getTimestamp();
		$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
		$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
		$date_saison = $mois[date("n", $date_saison)]." ".date("Y", $date_saison);

		if($this->saison_existante())
		{
			$derniere = $this->get_derniere();
			$derniere_date = $derniere->nom();
			if($date_saison == $derniere_date)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return true;
		}
	}

	public function update(Saison $saison) //Modifie une saison (tous les paramètres au cas où modifs par admin)
    {
		$q = $this->_db_RW->prepare('UPDATE saisons SET nom=:nom, classement=:classement, prestige=:prestige, vues=:vues WHERE id=:id');
		$q->execute(array(
			'id' => $saison->id(),
			'nom' => $saison->nom(),
			'classement' => $saison->classement(),
			'prestige' => $saison->prestige(),
			'vues' => $saison->vues(),
			));
	}

	public function nb_saisons() //Permet de compter le nombre de saisons
    {
		$q = $this->_db_RO->prepare('SELECT COUNT(*) AS nb_saisons FROM saisons');
		$q->execute(array());
		$donnees = $q->fetch();
		return $donnees["nb_saisons"];
	}

	public function get_all() //Permet de récupérer tous les saisons triés par id décroissant
    {
		$q = $this->_db_RO->prepare('SELECT * FROM saisons ORDER BY id DESC');
		$q->execute(array());
		$saisons = array();
		while($donnees = $q->fetch())
		{
		$saisons[] = new Saison($donnees);
		}
		return $saisons;
	}

	public function get_derniere() //Permet de récupérer la dernière saison
    {
		$q = $this->_db_RO->prepare('SELECT * FROM saisons ORDER BY id DESC LIMIT 0,1');
		$q->execute(array());
		$saisons = array();
		$donnees = $q->fetch();
		$saison = new Saison($donnees);
		return $saison;
	}

	public function saison_existante() //Teste s'il existe au moins une saison
    {
		$q = $this->_db_RO->prepare('SELECT * FROM saisons ORDER BY id DESC LIMIT 0,1');
		$q->execute(array());
		return (bool) $q->fetch();
	}

	public function get($id) //Permet de récupérer un saison par son id
    {
		$q = $this->_db_RO->prepare('SELECT * FROM saisons WHERE id= :id');
		$q->execute(array(
 			'id' => $id,
	 		));
		$donnees = $q->fetch();
		$saison = new Saison($donnees);
		return $saison;
	}

	public function get_saison_joueur(Joueur $joueur) //Permet de récupérer toutes les saisons auxquelles le joueur a participé
    {
		$q = $this->_db_RO->prepare('SELECT * FROM saisons WHERE classement LIKE :id_joueur_deb OR classement LIKE :id_joueur_mid OR classement LIKE :id_joueur_fin');
		$q->execute(array(
			"id_joueur_deb" => $joueur->id().",%",
			"id_joueur_mid" => "%,".$joueur->id().",%",
			"id_joueur_fin" => "%,".$joueur->id(),
		));
		$saisons = array();
		while($donnees = $q->fetch())
		{
			$saisons[] = new Saison($donnees);
		}
		return $saisons;
	}

	public function recompenses_joueur(Joueur $joueur, Joueur $joueur_actif) //Détermine les trophées décrochés par le joueur
	{
		$saisons = $this->get_saison_joueur($joueur);
		if($saisons)
		{
			if($joueur_actif->id() == $joueur->id())
			{
				$pseudo_joueur = "Tu as ";
			}
			else
			{
				$pseudo_joueur = $joueur->pseudo()." a ";
			}
			if($joueur->sexe() =="gars")
			{
				$premier = "PREMIER";
			}
			else
			{
				$premier = "PREMIÈRE";
			}
			$deuxieme = "DEUXIÈME";
			$troisieme = "TROISIÈME";
			$meilleur_classement = 10000000000;
			$descriptif_classement = "";
			$nb_coupes_or = 0;
			$descriptif_or = "";
			$nb_coupes_argent = 0;
			$descriptif_argent = "";
			$nb_coupes_bronze = 0;
			$descriptif_bronze = "";
			foreach($saisons as $saison)
			{
				$classement_joueur = $saison->classement_joueur($joueur);
				if($classement_joueur < $meilleur_classement)
				{
					$meilleur_classement = $classement_joueur;
					$descriptif_classement = $saison->nom();
				}
				if($classement_joueur == 1)
				{
					$nb_coupes_or++;
					$descriptif_or .= $saison->nom().", ";
				}
				if($classement_joueur == 2)
				{
					$nb_coupes_argent++;
					$descriptif_argent .= $saison->nom().", ";
				}
				if($classement_joueur == 3)
				{
					$nb_coupes_bronze++;
					$descriptif_bronze .= $saison->nom().", ";
				}
			}
			if($descriptif_or != ""){$descriptif_or = substr($descriptif_or, 0, -2);}
			if($descriptif_argent != ""){$descriptif_argent = substr($descriptif_argent, 0, -2);}
			if($descriptif_bronze != ""){$descriptif_bronze = substr($descriptif_bronze, 0, -2);}
			if($nb_coupes_or == 1)
				{$descriptif_or = $pseudo_joueur."fini <span class='g p1 or'>".$premier."</span> <span class='g'>1 fois</span><br>(saison ".$descriptif_or.")";}
			elseif($nb_coupes_or > 1)
				{$descriptif_or = $pseudo_joueur."fini <span class='g p1 or'>".$premier."</span> <span class='g'>".$nb_coupes_or." fois</span><br>(saisons ".$descriptif_or.")";}
			if($nb_coupes_argent == 1)
				{$descriptif_argent = $pseudo_joueur."fini <span class='g p1 argent'>".$deuxieme."</span> <span class='g'>1 fois</span><br>(saison ".$descriptif_argent.")";}
			elseif($nb_coupes_argent > 1)
				{$descriptif_argent = $pseudo_joueur."fini <span class='g p1 argent'>".$deuxieme."</span> <span class='g'>".$nb_coupes_argent." fois</span><br>(saisons ".$descriptif_argent.")";}
			if($nb_coupes_bronze == 1)
				{$descriptif_bronze = $pseudo_joueur."fini <span class='g p1 bronze'>".$troisieme."</span> <span class='g'>1 fois</span><br>(saison ".$descriptif_bronze.")";}
			elseif($nb_coupes_bronze > 1)
				{$descriptif_bronze = $pseudo_joueur."fini <span class='g p1 bronze'>".$troisieme."</span> <span class='g'>".$nb_coupes_bronze." fois</span><br>(saisons ".$descriptif_bronze.")";}
			$descriptif_classement = "Meilleur classement : <span class='g p2'>".$meilleur_classement."°</span><br>(saison ".$descriptif_classement.")";
			$bilan = array(
				"meilleur_classement" => $meilleur_classement,
				"descriptif_classement" => $descriptif_classement,
				"nb_coupes_or" => $nb_coupes_or,
				"descriptif_or" => $descriptif_or,
				"nb_coupes_argent" => $nb_coupes_argent,
				"descriptif_argent" => $descriptif_argent,
				"nb_coupes_bronze" => $nb_coupes_bronze,
				"descriptif_bronze" => $descriptif_bronze
				);
			return $bilan;
		}
		else
		{
			return "Aucune saison";
		}
	}

}
