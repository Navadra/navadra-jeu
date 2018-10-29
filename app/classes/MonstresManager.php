<?php

class MonstresManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }



public function add(Monstre $monstre, Joueur $joueur, $niveau) //Ajoute un monstre dans la BDD
    {
		$position = $this->determiner_position($monstre, $joueur); //On détermine une position pour le monstre et on lui donne ses caractéristiques
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date_creation = strftime('%Y-%m-%d %H:%M:%S',time());
		$q = $this->_db_RW->prepare('INSERT INTO monstres(id_joueur, nom, element, niveau, nb_chasseurs, categorie, position, img, dead, date_creation) VALUES(:id_joueur, :nom, :element, :niveau, :nb_chasseurs, :categorie, :position, :img, :dead, :date_creation)');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			'nom' => $monstre->nom(),
			'element' => $monstre->element(),
			'niveau' => $niveau,
			'nb_chasseurs' => $monstre->nb_chasseurs(),
			'categorie' => $monstre->categorie(),
			'position' => serialize($position),
			'img' => $monstre->img(),
			'dead' => 0,
			'date_creation' => $date_creation,
			));
		$monstre->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),
			'niveau' => $niveau,
			'position' => $position,
			'dead' => 0,
			'date_creation' => $date_creation,
    	));
	}

	public function add_premiers_monstres(Monstre $monstre, Joueur $joueur, $element_monstre, $niveau_monstre) //Ajoute le premier monstre dans la BDD (tuto)
    {
		$position = $this->determiner_position_tuto($monstre, $joueur, $element_monstre); //On détermine une position pour le monstre et on lui donne ses caractéristiques
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date_creation = strftime('%Y-%m-%d %H:%M:%S',time());
		$q = $this->_db_RW->prepare('INSERT INTO monstres(id_joueur, nom, element, niveau, nb_chasseurs, categorie, position, img, dead, date_creation) VALUES(:id_joueur, :nom, :element, :niveau, :nb_chasseurs, :categorie, :position, :img, :dead, :date_creation)');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			'nom' => $monstre->nom(),
			'element' => $monstre->element(),
			'niveau' => $niveau_monstre,
			'nb_chasseurs' => $monstre->nb_chasseurs(),
			'categorie' => $monstre->categorie(),
			'position' => serialize($position),
			'img' => $monstre->img(),
			'dead' => 0,
			'date_creation' => $date_creation,
			));
		$monstre->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),
			'niveau' => $niveau_monstre,
			'position' => $position,
			'dead' => 0,
			'date_creation' => $date_creation,
    	));
	}

	public function nouveau_monstre_solo(Joueur $joueur, $niveau) //Créé un monstre solo en fonction du niveau du joueur
	{
		$monstre = new Monstre(array("nb_chasseurs"=>1));
		$this->add($monstre, $joueur, $niveau);
		return $monstre;
	}

	public function apparition_monstre_multi(Joueur $joueur, $niveau, $size_monster = "?", $nbDaysLastLog = 0) {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		if($size_monster != "?" && in_array($size_monster, array(2.5,4.5,8)) ){
			$monstre = new Monstre(array("nb_chasseurs"=>$size_monster));
		} elseif($size_monster == "?"){
			$heure_log = (int) substr($joueur->dernier_log(), 11, 2);
			$facteur_proba = max(1, min(1 * $nbDaysLastLog/2, 3));
			if($facteur_proba < 1.5 && $heure_log >= 18){
				$facteur_proba = 1.5;
			}
			if($joueur->niveau() >= 5 && $joueur->niveau() < 12) //On crée un monstre 2-3
			{
				$rand = rand(1, 100);
				if($rand <= 33*$facteur_proba)
				{$monstre = new Monstre(array("nb_chasseurs"=>2.5));}
			}
			elseif($joueur->niveau() >= 12 && $joueur->niveau() < 20) //On crée un monstre 2-3 ou un 4-5
			{
				$rand = rand(1, 100);
				if($rand <= 22*$facteur_proba)
				{$monstre = new Monstre(array("nb_chasseurs"=>2.5));}
				elseif($rand <= 33*$facteur_proba)
				{$monstre = new Monstre(array("nb_chasseurs"=>4.5));}
			}
			elseif($joueur->niveau() >= 20) //On crée un monstre 2-3 ou un 4-5 ou un 6-8
			{
				$rand = rand(1, 100);
				if($rand <= 17*$facteur_proba)
				{$monstre = new Monstre(array("nb_chasseurs"=>2.5));}
				elseif($rand <= 28*$facteur_proba)
				{$monstre = new Monstre(array("nb_chasseurs"=>4.5));}
				elseif($rand <= 33*$facteur_proba)
				{$monstre = new Monstre(array("nb_chasseurs"=>8));}
			}
		}
		if(isset($monstre))
		{
			$this->add($monstre, $joueur, $niveau);
			return $monstre;
		}
		else
		{
			return false;
		}
	}

	public function newMonstersTimeSlot (Joueur $joueur, $nbMonsters) {
		for($i = 1; $i<=$nbMonsters; $i++){
			$rand = rand(1, 100);
			if($rand <= 70){
				$monstre = new Monstre(array("nb_chasseurs"=>1));
			} else if($rand <= 90){
				$monstre = new Monstre(array("nb_chasseurs"=>2.5));
			} else {
				$monstre = new Monstre(array("nb_chasseurs"=>4.5));
			}
			$this->add($monstre, $joueur, $joueur->niveau());
		}
	}

	public function update(Monstre $monstre) //Modifie un monstre (tous les paramètres au cas où modifs par admin)
    {
		$position = serialize($monstre->position());
		$q = $this->_db_RW->prepare('UPDATE monstres SET nom=:nom, element=:element, niveau=:niveau, nb_chasseurs=:nb_chasseurs, categorie=:categorie, position=:position, img=:img, dead=:dead, date_creation=:date_creation WHERE id=:id');
		$q->execute(array(
			'id' => $monstre->id(),
			'nom' => $monstre->nom(),
			'element' => $monstre->element(),
			'niveau' => $monstre->niveau(),
			'nb_chasseurs' => $monstre->nb_chasseurs(),
			'categorie' => $monstre->categorie(),
			'position' => $position,
			'img' => $monstre->img(),
			'dead' => $monstre->dead(),
			'date_creation' => $monstre->date_creation()
			));
	}

	public function updateMonstersLevel (Joueur $joueur) {
		$params = array(
	        'id_joueur' => $joueur->id(),
	        'niveau' => $joueur->niveau()
	    );
      // UPDATE
      $q = $this->_db_RW->prepare('UPDATE monstres SET niveau=:niveau WHERE id_joueur=:id_joueur');
      $q->execute($params);
  }

	public function nb_monstres_solo(Joueur $joueur) //Permet de compter le nombre de monstres solo du joueur
    {
		$q = $this->_db_RW->prepare('SELECT COUNT(*) FROM monstres WHERE id_joueur=:id_joueur AND nb_chasseurs=:nb_chasseurs AND dead=:dead');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			'nb_chasseurs' => 1,
			'dead' => 0
			));
		$donnees = $q->fetch();
		return $donnees[0]; //Pour retourner directement le nombre et pas un array
	}

	public function nb_monstres_multi(Joueur $joueur) //Permet de compter le nombre de monstres multi du joueur
    {
		$q = $this->_db_RW->prepare('SELECT COUNT(*) FROM monstres WHERE id_joueur= :id_joueur AND nb_chasseurs!=:nb_chasseurs AND dead=:dead');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			'nb_chasseurs' => 1,
			'dead' => 0
			));
		$donnees = $q->fetch();
		return $donnees[0]; //Pour retourner directement le nombre et pas un array
	}

	public function nb_monstres_total(Joueur $joueur)
    {
		$q = $this->_db_RW->prepare('SELECT COUNT(*) FROM monstres WHERE id_joueur= :id_joueur AND dead=:dead');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			'dead' => 0
			));
		$donnees = $q->fetch();
		return $donnees[0]; //Pour retourner directement le nombre et pas un array
	}

	public function delete(Monstre $monstre) //Supprime un monstre après un combat
    {
		$q = $this->_db_RW->prepare('DELETE FROM monstres WHERE id=:id');
		$q->execute(array(
			'id' => $monstre->id(),
			));
	}

	public function delete_all(Joueur $joueur) //Supprime tous les monstres d'un joueur (s'il supprime son compte)
	{
		$q = $this->_db_RW->prepare('DELETE FROM monstres WHERE id_joueur=:id_joueur');
		$q->execute(array(
				'id_joueur' => $joueur->id(),
		));
	}

	//Supprime tous les monstres qui sont dead=0, et dont la table combat est en issue!=''
	public function delete_all_bad_monsters() 	{
		$q = $this->_db_RW->prepare("SELECT c.id as id_combat FROM monstres m INNER JOIN combats c ON c.id_monstre=m.id WHERE m.dead=0 AND c.issue<>'';");
		$q->execute();
		while($donnees = $q->fetch()) {
		  $q1 = $this->_db_RW->prepare('DELETE FROM combats WHERE id=:id_combat');
      $q1->execute(array(
			  'id_combat' => $donnees["id_combat"]
			));
		}
	}

	// Réhabilite tous les monstres qui sont dead=0, et dont la table combat est en issue!='' pour un joueur
	// Pour ça j'efface la table combat pour ces monstres là...
	public function delete_bad_monsters(Joueur $joueur) 	{
		$q = $this->_db_RW->prepare("SELECT c.id as id_combat FROM monstres m INNER JOIN combats c ON c.id_monstre=m.id WHERE m.id_joueur=:id_joueur AND m.dead=0 AND c.issue<>'';");
		$q->execute(array(
		  'id_joueur' => $joueur->id()
		));
		while($donnees = $q->fetch()) {
		  $q1 = $this->_db_RW->prepare('DELETE FROM combats WHERE id=:id_combat');
      $q1->execute(array(
			  'id_combat' => $donnees["id_combat"]
			));
		}
	}

	public function delete_all_today(Joueur $joueur) //Supprime tous les monstres d'un joueur (s'il supprime son compte)
  {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$today = strftime('%Y-%m-%d',time());
		$q = $this->_db_RW->prepare('DELETE FROM monstres WHERE id_joueur=:id_joueur AND date_creation LIKE :today');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			'today' => $today,
			));
	}

	public function get(Joueur $joueur){ //Permet de récupérer tous les monstres du joueur
		$q = $this->_db_RW->prepare('SELECT * FROM monstres WHERE id_joueur= :id_joueur AND dead=:dead');
		$q->execute(array(
 			'id_joueur' => $joueur->id(),
			'dead' => 0,
	 		));
		$monstres = array();
		while($donnees = $q->fetch())
		{
		//On désérialise les arrays
		$donnees["position"] = unserialize($donnees["position"]);
		$monstres[] = new Monstre($donnees);
		}
		return $monstres;
	}

	public function getToday(Joueur $joueur) {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$today = strftime('%Y-%m-%d',time());
		$q = $this->_db_RO->prepare('SELECT * FROM monstres WHERE id_joueur= :id_joueur AND dead=:dead AND date_creation LIKE :today');
		$q->execute(array(
 			'id_joueur' => $joueur->id(),
			'dead' => 0,
			'today' => $today
	 		));
		$monstres = array();
		while($donnees = $q->fetch())	{
			$donnees["position"] = unserialize($donnees["position"]);
			$monstres[] = new Monstre($donnees);
		}
		return $monstres;
	}

	public function get_id($id_monstre) //Permet de récupérer un monstre avec son id
    {
		$q = $this->_db_RW->prepare('SELECT * FROM monstres WHERE id= :id');
		$q->execute(array(
 			'id' => $id_monstre,
	 		));
		$donnees = $q->fetch();
		//On désérialise les arrays
		$donnees["position"] = unserialize($donnees["position"]);
		return new Monstre($donnees);
	}

	public function exists($id_monstre) //Vérifie si un monstre existe déjà
	{
		$q = $this->_db_RW->prepare('SELECT * FROM monstres WHERE id= :id_monstre AND dead=:dead');
		$q->execute(array(
 			'id_monstre' => $id_monstre,
			'dead' => 0,
	 		));
		return (bool) $q->fetch();
	}

	public function monstre_existant($nom, Joueur $joueur) //Vérifie si un monstre existe déjà parmi les monstres appartenant au joueur
	{
		$q = $this->_db_RO->prepare('SELECT * FROM monstres WHERE id_joueur= :id_joueur AND nom= :nom AND dead=:dead');
		$q->execute(array(
 			'nom' => $nom,
			'id_joueur' => $joueur->id(),
			'dead' => 0,
	 		));
		return (bool) $q->fetch();
	}

	public function monstre_existant_id($id_monstre, Joueur $joueur) //Vérifie si un monstre existe déjà parmi les monstres appartenant au joueur
	{
		$q = $this->_db_RO->prepare('SELECT * FROM monstres WHERE id_joueur= :id_joueur AND id= :id_monstre AND dead=:dead');
		$q->execute(array(
 			'id_monstre' => $id_monstre,
			'id_joueur' => $joueur->id(),
			'dead' => 0,
	 		));
		return (bool) $q->fetch();
	}

	public function carac_monstre($nb_chasseurs) //Permet d'attribuer des caractéristiques aléatoire à un nouveau monstre en fonction du nb de chasseurs recommandé
	{
		$caracs = array();
		switch($nb_chasseurs)
		{
			case 1 :
			$rand = rand(1, 20);
			if($rand==1)
			{
				$caracs["nom"] = "Iguane";
				$caracs["element"] = "feu";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/iguane.png";
			}
			if($rand==2)
			{
				$caracs["nom"] = "Scorpion";
				$caracs["element"] = "feu";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/scorpion.png";
			}
			if($rand==3)
			{
				$caracs["nom"] = "Vipère";
				$caracs["element"] = "feu";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/vipere.png";
			}
			if($rand==4)
			{
				$caracs["nom"] = "Lion";
				$caracs["element"] = "feu";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/lion.png";
			}
			if($rand==5)
			{
				$caracs["nom"] = "Puma";
				$caracs["element"] = "feu";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/puma.png";
			}
			if($rand==6)
			{
				$caracs["nom"] = "Dendrobate";
				$caracs["element"] = "eau";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/dendrobate.png";
			}
			if($rand==7)
			{
				$caracs["nom"] = "Couleuvre";
				$caracs["element"] = "eau";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/couleuvre.png";
			}
			if($rand==8)
			{
				$caracs["nom"] = "Sangsue";
				$caracs["element"] = "eau";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/sangsue.png";
			}
			if($rand==9)
			{
				$caracs["nom"] = "Gavial";
				$caracs["element"] = "eau";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/gavial.png";
			}
			if($rand==10)
			{
				$caracs["nom"] = "Rage des marais";
				$caracs["element"] = "eau";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/rage_des_marais.png";
			}
			if($rand==11)
			{
				$caracs["nom"] = "Veuve noire";
				$caracs["element"] = "terre";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/veuve_noire.png";
			}
			if($rand==12)
			{
				$caracs["nom"] = "Cobra";
				$caracs["element"] = "terre";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/cobra.png";
			}
			if($rand==13)
			{
				$caracs["nom"] = "Loup";
				$caracs["element"] = "terre";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/loup.png";
			}
			if($rand==14)
			{
				$caracs["nom"] = "Sanglier";
				$caracs["element"] = "terre";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/sanglier.png";
			}
			if($rand==15)
			{
				$caracs["nom"] = "Dévoreur";
				$caracs["element"] = "terre";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/devoreur.png";
			}
			if($rand==16)
			{
				$caracs["nom"] = "Chauve-souris";
				$caracs["element"] = "vent";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/chauve_souris.png";
			}
			if($rand==17)
			{
				$caracs["nom"] = "Vautour";
				$caracs["element"] = "vent";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/vautour.png";
			}
			if($rand==18)
			{
				$caracs["nom"] = "Aigle";
				$caracs["element"] = "vent";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/aigle.png";
			}
			if($rand==19)
			{
				$caracs["nom"] = "Terreur ailée";
				$caracs["element"] = "vent";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/terreur_ailee.png";
			}
			if($rand==20)
			{
				$caracs["nom"] = "Dard mortel";
				$caracs["element"] = "vent";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/dard_mortel.png";
			}
			break;
			case 2.5 :
			$rand = rand(1, 8);
			if($rand==1)
			{
				$caracs["nom"] = "Tigre";
				$caracs["element"] = "feu";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/tigre.png";
			}

			if($rand==2)
			{
				$caracs["nom"] = "Lynx";
				$caracs["element"] = "feu";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/lynx.png";
			}
			if($rand==3)
			{
				$caracs["nom"] = "Varan";
				$caracs["element"] = "eau";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/varan.png";
			}
			if($rand==4)
			{
				$caracs["nom"] = "Alligator";
				$caracs["element"] = "eau";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/alligator.png";
			}
			if($rand==5)
			{
				$caracs["nom"] = "Jaguar";
				$caracs["element"] = "terre";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/jaguar.png";
			}
			if($rand==6)
			{
				$caracs["nom"] = "Boa";
				$caracs["element"] = "terre";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/boa.png";
			}
			if($rand==7)
			{
				$caracs["nom"] = "Brute des plaines";
				$caracs["element"] = "vent";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/brute_des_plaines.png";
			}
			if($rand==8)
			{
				$caracs["nom"] = "Coureur du vent";
				$caracs["element"] = "vent";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/coureur_du_vent.png";
			}
			break;
			case 4.5 :
			$rand = rand(1, 8);
			if($rand==1)
			{
				$caracs["nom"] = "Fureur des pics";
				$caracs["element"] = "feu";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/fureur_des_pics.png";
			}
			if($rand==2)
			{
				$caracs["nom"] = "Grizzly";
				$caracs["element"] = "feu";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/grizzly.png";
			}
			if($rand==3)
			{
				$caracs["nom"] = "Anaconda";
				$caracs["element"] = "eau";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/anaconda.png";
			}
			if($rand==4)
			{
				$caracs["nom"] = "Hippopotame";
				$caracs["element"] = "eau";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/hippopotame.png";
			}
			if($rand==5)
			{
				$caracs["nom"] = "Gorille";
				$caracs["element"] = "terre";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/gorille.png";
			}
			if($rand==6)
			{
				$caracs["nom"] = "Ours brun";
				$caracs["element"] = "terre";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/ours_brun.png";
			}
			if($rand==7)
			{
				$caracs["nom"] = "Harpie";
				$caracs["element"] = "vent";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/harpie.png";
			}
			if($rand==8)
			{
				$caracs["nom"] = "Griffon";
				$caracs["element"] = "vent";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/griffon.png";
			}
			break;
			case 8 :
			$rand = rand(1, 4);
			if($rand==1)
			{
				$caracs["nom"] = "Cyclope";
				$caracs["element"] = "feu";
				$caracs["categorie"] = "offensif";
				$caracs["img"] = "/webroot/img/monstres/cyclope.png";
			}
			if($rand==2)
			{
				$caracs["nom"] = "Hydre";
				$caracs["element"] = "eau";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/hydre.png";
			}
			if($rand==3)
			{
				$caracs["nom"] = "Minotaure";
				$caracs["element"] = "terre";
				$caracs["categorie"] = "defensif";
				$caracs["img"] = "/webroot/img/monstres/minotaure.png";
			}
			if($rand==4)
			{
				$caracs["nom"] = "Dragon";
				$caracs["element"] = "vent";
				$caracs["categorie"] = "equilibre";
				$caracs["img"] = "/webroot/img/monstres/dragon.png";
			}
			break;
		}
	return $caracs;
	}

	public function determiner_position (Monstre $monstre, Joueur $joueur) //Permet de déterminer une position pour le nouveau monstre sans chevauchement et lui donne ses caracs
	{
		$position_joueur = $joueur->position();
		$X_joueur = (float) substr($position_joueur["posX"], 0, strlen($position_joueur["posX"]) - 1);
		$Y_joueur = (float) substr($position_joueur["posY"], 0, strlen($position_joueur["posY"]) - 1);
		$monstres = $this->get($joueur);
		$ok = 1;
		$largeur_joueur = 10; //On prend la largeur du joueur et des monstres
		$hauteur_joueur = 10;
		$largeur_monstre = 12;
		$hauteur_monstre = 10;
		while($ok > 0)
		{
			$monstre->hydrate($this->carac_monstre($monstre->nb_chasseurs())); //On tire un monstre au hasard en fonction du nb de chasseurs recommandés
			switch($monstre->element()) //Détermination de l'intervalle de position possible en fonction de l'élément du monstre
			{
				case "feu" :
					$xmin = 530;
					$xmax = 680;
					$ymin = 470;
					$ymax = 680;
					break;
				case "eau" :
					$xmin = 210;
					$xmax = 400;
					$ymin = 210;
					$ymax = 400;
					break;
				case "vent" :
					$xmin = 250;
					$xmax = 450;
					$ymin = 500;
					$ymax = 720;
					break;
				case "terre" :
					$xmin = 500;
					$xmax = 680;
					$ymin = 170;
					$ymax = 380;
					break;
			}
			$ok = 0;
			$X = rand($xmin, $xmax)/10;
			$Y = rand($ymin, $ymax)/10;
			if( (abs($X - $X_joueur) <= $largeur_joueur) && (abs($Y - $Y_joueur) <= $hauteur_joueur) ) // Si la position générée se chevauche avec celle du joueur
				{$ok++;}
			foreach($monstres as $m) //On récupère chaque monstre pour tester le chevauchement de position
				{
					$position_monstre = $m->position(); //On récupère la position du monstre
					$X_monstre = (float) substr($position_monstre["posX"], 0, strlen($position_monstre["posX"]) - 1);
					$Y_monstre = (float) substr($position_monstre["posY"], 0, strlen($position_monstre["posY"]) - 1);
					if( (abs($X - $X_monstre) <= $largeur_monstre) && (abs($Y - $Y_monstre) <= $hauteur_monstre)) // Si la position générée se chevauche avec celle du monstre
						{$ok++;}
				}
		}
		$position = array();
		$position["posX"] = $X;
		$position["posX"] = (string) $position["posX"]."%";
		$position["posY"] = $Y;
		$position["posY"] = (string) $position["posY"]."%";
		return $position;
	}

	public function determiner_position_tuto (Monstre $monstre, Joueur $joueur, $element_monstre) //Permet de déterminer une position pour le nouveau monstre et lui donne ses caracs (tuto)
	{
		$position_joueur = $joueur->position();
		$X_joueur = (float) substr($position_joueur["posX"], 0, strlen($position_joueur["posX"]) - 1);
		$Y_joueur = (float) substr($position_joueur["posY"], 0, strlen($position_joueur["posY"]) - 1);
		$ok = 1;
		$largeur_joueur = 10; //On prend la largeur du joueur
		$hauteur_joueur = 10;
		while($ok > 0)
		{
			switch($element_monstre)
			{
				case "feu" :
					$monstre->hydrate(array("nom" => "Vipère", "element" => "feu", "categorie" => "offensif", "img" => "/webroot/img/monstres/vipere.png"));
					$xmin = 530;
					$xmax = 680;
					$ymin = 470;
					$ymax = 680;
					break;
				case "eau" :
					$monstre->hydrate(array("nom" => "Couleuvre", "element" => "eau", "categorie" => "offensif", "img" => "/webroot/img/monstres/couleuvre.png"));
					$xmin = 210;
					$xmax = 400;
					$ymin = 200;
					$ymax = 400;
					break;
				case "vent" :
					$monstre->hydrate(array("nom" => "Vautour", "element" => "vent", "categorie" => "equilibre", "img" => "/webroot/img/monstres/vautour.png"));
					$xmin = 250;
					$xmax = 450;
					$ymin = 500;
					$ymax = 720;
					break;
				case "terre" :
					$monstre->hydrate(array("nom" => "Cobra", "element" => "terre", "categorie" => "offensif", "img" => "/webroot/img/monstres/cobra.png"));
					$xmin = 500;
					$xmax = 680;
					$ymin = 150;
					$ymax = 380;
					break;
			}
			$ok = 0;
			$X = rand($xmin, $xmax)/10;
			$Y = rand($ymin, $ymax)/10;
			if( (abs($X - $X_joueur) <= $largeur_joueur) && (abs($Y - $Y_joueur) <= $hauteur_joueur) ) // Si la position générée se chevauche avec celle du joueur
				{$ok++;}
		}
		$position = array();
		$position["posX"] = $X;
		$position["posX"] = (string) $position["posX"]."%";
		$position["posY"] = $Y;
		$position["posY"] = (string) $position["posY"]."%";
		return $position;
	}

	public function get_monstre($id) //Permet de récupérer un monstre en particulier
    {
		$q = $this->_db_RO->prepare('SELECT * FROM monstres WHERE id= :id');
		$q->execute(array(
 			'id' => $id,
	 		));
		$donnees = $q->fetch();
		$donnees["position"] = unserialize($donnees["position"]);
		$monstre = new Monstre($donnees);
		return $monstre;
	}

}
