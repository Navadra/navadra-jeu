<?php

class ChallengesManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
	}

  public function save_or_update(Challenge $challenge) //Modifie un défi (tous les paramètres au cas où modifs par admin)
  {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date_last_try = strftime('%Y-%m-%d %H:%M:%S', time());
		$params = array(
        'element' => $challenge->getElement(),
        'name' => $challenge->getName(),
        'id_joueur' => $challenge->getId_joueur(),
				'initial_level' => $challenge->getInitial_level(),
        'current_level' => $challenge->getCurrent_level(),
				'stock' => $challenge->getStock(),
        'tries' => $challenge->getTries(),
				'date_last_try' => $date_last_try
    );
    if ($challenge->getId() > 0) {
	  $params['id'] = $challenge->getId();
      // UPDATE
      $q = $this->_db_RW->prepare('UPDATE challenges SET element=:element, name=:name, id_joueur=:id_joueur, initial_level=:initial_level, current_level=:current_level, stock=:stock, tries=:tries, date_last_try=:date_last_try WHERE id=:id');
      $q->execute($params);
    }
    else {
      // SAVE
      $q = $this->_db_RW->prepare('INSERT INTO challenges (element, name, id_joueur, initial_level, current_level, stock, tries, date_last_try) VALUES (:element, :name, :id_joueur, :initial_level, :current_level, :stock, :tries, :date_last_try)');
      $q->execute($params);
      $challenge->hydrate(array(
          'id' => $this->_db_RW->lastInsertId(),
      ));
    }
  }

  public function reset_challenges(Joueur $joueur) //Only for demo
  {
	$params = array(
        'id_joueur' => $joueur->id(),
        'current_level' => 0,
				'initial_level' => 0,
        'tries' => 0,
        'stock' => 0
    );
      // UPDATE
      $q = $this->_db_RW->prepare('UPDATE challenges SET current_level=:current_level, initial_level=:initial_level, tries=:tries, stock=:stock, date_last_try=:date_last_try WHERE id_joueur=:id_joueur');
      $q->execute($params);
  }

	public function removeStockChallenges (Joueur $joueur) {
	$params = array(
        'id_joueur' => $joueur->id(),
        'stock' => 0
    );
      // UPDATE
      $q = $this->_db_RW->prepare('UPDATE challenges SET stock=:stock WHERE id_joueur=:id_joueur');
      $q->execute($params);
  }

	public function delete_challenges(Joueur $joueur) //Only for demo
  {
		$params = array(
	        'id_joueur' => $joueur->id(),
	        'id_challenge' => 128
	    );
      $q = $this->_db_RW->prepare('DELETE FROM challenges WHERE id_joueur=:id_joueur AND id!=:id_challenge');
      $q->execute($params);
  }

	public function delete_all(Joueur $joueur) //For reset
  {
		$params = array(
	        'id_joueur' => $joueur->id()
	    );
      $q = $this->_db_RW->prepare('DELETE FROM challenges WHERE id_joueur=:id_joueur');
      $q->execute($params);
  }

  //Get all player challenges
  public function get(Joueur $joueur)
  {
    $q = $this->_db_RW->prepare('SELECT * FROM challenges WHERE id_joueur= :id_joueur ORDER BY date_last_try ASC');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $challenges = array();
    while ($donnees = $q->fetch()) {
      $challenges[] = new Challenge($donnees);
    }
    return $challenges;
  }

  //Get all player challenges of a given element
  public function get_element(Joueur $joueur, $element)
  {
    $q = $this->_db_RW->prepare('SELECT * FROM challenges WHERE id_joueur= :id_joueur AND element= :element ORDER BY date_last_try ASC');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
				'element' => $element,
    ));
    $challenges = array();
    while ($donnees = $q->fetch()) {
      $challenges[] = new Challenge($donnees);
    }
    return $challenges;
  }

  //Get all potential challenges
  public function get_potential(Joueur $joueur, $element)
  {
    $challenges = $this->get_element($joueur, $element);
		$codesChallenges = array();
		foreach($challenges as $challenge){
			$codesChallenges[] = $challenge->getElement()."_".$challenge->getName();
		}
	if($element == "fire")
	{
		if(!in_array("fire_integers", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'integers', 'id_joueur' => $joueur->id()));}
		if(!in_array("fire_decimals", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'decimals', 'id_joueur' => $joueur->id()));}
		if(!in_array("fire_multiples", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'multiples', 'id_joueur' => $joueur->id()));}
		if(!in_array("fire_fractions", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'fractions', 'id_joueur' => $joueur->id()));}
		if(!in_array("fire_greatNumbers", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'greatNumbers', 'id_joueur' => $joueur->id()));}
		if(!in_array("fire_divisions", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'divisions', 'id_joueur' => $joueur->id()));}
		if(!in_array("fire_problemInterpretation", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'problemInterpretation', 'id_joueur' => $joueur->id()));}
	}
	elseif($element == "water")
	{
		if(!in_array("water_proportionality", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'proportionality', 'id_joueur' => $joueur->id()));}
		if(!in_array("water_percentages", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'percentages', 'id_joueur' => $joueur->id()));}
		if(!in_array("water_tables", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'tables', 'id_joueur' => $joueur->id()));}
		if(!in_array("water_graphs", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'graphs', 'id_joueur' => $joueur->id()));}
		if(!in_array("water_radars", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'radars', 'id_joueur' => $joueur->id()));}
		if(!in_array("water_bars", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'bars', 'id_joueur' => $joueur->id()));}
		if(!in_array("water_circulars", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'circulars', 'id_joueur' => $joueur->id()));}
	}
	elseif($element == "wind")
	{
		if(!in_array("wind_lines", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'lines', 'id_joueur' => $joueur->id()));}
		if(!in_array("wind_angles", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'angles', 'id_joueur' => $joueur->id()));}
		if(!in_array("wind_circles", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'circles', 'id_joueur' => $joueur->id()));}
		if(!in_array("wind_quadrilaterals", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'quadrilaterals', 'id_joueur' => $joueur->id()));}
		if(!in_array("wind_triangles", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'triangles', 'id_joueur' => $joueur->id()));}
		if(!in_array("wind_bisectors", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'bisectors', 'id_joueur' => $joueur->id()));}
		if(!in_array("wind_symmetries", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'symmetries', 'id_joueur' => $joueur->id()));}
	}
	elseif($element == "earth")
	{
		if(!in_array("earth_lengths", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'lengths', 'id_joueur' => $joueur->id()));}
		if(!in_array("earth_weights", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'weights', 'id_joueur' => $joueur->id()));}
		if(!in_array("earth_durations", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'durations', 'id_joueur' => $joueur->id()));}
		if(!in_array("earth_prices", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'prices', 'id_joueur' => $joueur->id()));}
		if(!in_array("earth_perimeters", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'perimeters', 'id_joueur' => $joueur->id()));}
		if(!in_array("earth_areas", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'areas', 'id_joueur' => $joueur->id()));}
		if(!in_array("earth_volumes", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'volumes', 'id_joueur' => $joueur->id()));}
	}
	//Determination of the maximal challenge priority
	$max_priority = 3;
	usort($challenges,array($this,"comparePriority"));
	foreach($challenges as $challenge)
	{
		if($challenge->priority() == 1 && $challenge->getTries() == 0)
		{
			$max_priority = 1;
			break;
		}
		if($challenge->priority() == 2 && $challenge->getTries() == 0)
		{
			$max_priority = 2;
			break;
		}
	}
	$potential_challenges = array();
	foreach($challenges as $challenge)
	{
		if($challenge->priority() <= $max_priority)
		{
			$potential_challenges[] = $challenge;
		}
	}
	usort($potential_challenges,array($this,"compareDateLastTry"));
	return $potential_challenges;
  }

  //Get all challenges of a player (for progress tracking)
  public function get_all_challenges(Joueur $joueur)
  {
    $challenges = $this->get($joueur);
		$codesChallenges = array();
		foreach($challenges as $challenge) {
			$codesChallenges[] = $challenge->getElement()."_".$challenge->getName();
		}
	if(!in_array("fire_integers", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'integers', 'id_joueur' => $joueur->id()));}
	if(!in_array("fire_decimals", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'decimals', 'id_joueur' => $joueur->id()));}
	if(!in_array("fire_multiples", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'multiples', 'id_joueur' => $joueur->id()));}
	if(!in_array("fire_fractions", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'fractions', 'id_joueur' => $joueur->id()));}
	if(!in_array("fire_greatNumbers", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'greatNumbers', 'id_joueur' => $joueur->id()));}
	if(!in_array("fire_divisions", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'divisions', 'id_joueur' => $joueur->id()));}
	if(!in_array("fire_problemInterpretation", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'fire', 'name' => 'problemInterpretation', 'id_joueur' => $joueur->id()));}

	if(!in_array("water_proportionality", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'proportionality', 'id_joueur' => $joueur->id()));}
	if(!in_array("water_percentages", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'percentages', 'id_joueur' => $joueur->id()));}
	if(!in_array("water_tables", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'tables', 'id_joueur' => $joueur->id()));}
	if(!in_array("water_graphs", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'graphs', 'id_joueur' => $joueur->id()));}
	if(!in_array("water_radars", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'radars', 'id_joueur' => $joueur->id()));}
	if(!in_array("water_bars", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'bars', 'id_joueur' => $joueur->id()));}
	if(!in_array("water_circulars", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'water', 'name' => 'circulars', 'id_joueur' => $joueur->id()));}

	if(!in_array("wind_lines", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'lines', 'id_joueur' => $joueur->id()));}
	if(!in_array("wind_angles", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'angles', 'id_joueur' => $joueur->id()));}
	if(!in_array("wind_circles", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'circles', 'id_joueur' => $joueur->id()));}
	if(!in_array("wind_quadrilaterals", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'quadrilaterals', 'id_joueur' => $joueur->id()));}
	if(!in_array("wind_triangles", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'triangles', 'id_joueur' => $joueur->id()));}
	if(!in_array("wind_bisectors", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'bisectors', 'id_joueur' => $joueur->id()));}
	if(!in_array("wind_symmetries", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'wind', 'name' => 'symmetries', 'id_joueur' => $joueur->id()));}

	if(!in_array("earth_lengths", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'lengths', 'id_joueur' => $joueur->id()));}
	if(!in_array("earth_weights", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'weights', 'id_joueur' => $joueur->id()));}
	if(!in_array("earth_durations", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'durations', 'id_joueur' => $joueur->id()));}
	if(!in_array("earth_prices", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'prices', 'id_joueur' => $joueur->id()));}
	if(!in_array("earth_perimeters", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'perimeters', 'id_joueur' => $joueur->id()));}
	if(!in_array("earth_areas", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'areas', 'id_joueur' => $joueur->id()));}
	if(!in_array("earth_volumes", $codesChallenges)){$challenges[]=new Challenge(array('element' => 'earth', 'name' => 'volumes', 'id_joueur' => $joueur->id()));}

	return $challenges;
  }

	public function get_challenges_by_element(Joueur $joueur){
		$challengesFire = array();
		$challengesFire[] = new Challenge(array('element' => 'fire', 'name' => 'integers', 'id_joueur' => $joueur->id()));
		$challengesFire[] = new Challenge(array('element' => 'fire', 'name' => 'decimals', 'id_joueur' => $joueur->id()));
		$challengesFire[] = new Challenge(array('element' => 'fire', 'name' => 'multiples', 'id_joueur' => $joueur->id()));
		$challengesFire[] = new Challenge(array('element' => 'fire', 'name' => 'fractions', 'id_joueur' => $joueur->id()));
		$challengesFire[] = new Challenge(array('element' => 'fire', 'name' => 'greatNumbers', 'id_joueur' => $joueur->id()));
		$challengesFire[] = new Challenge(array('element' => 'fire', 'name' => 'divisions', 'id_joueur' => $joueur->id()));
		$challengesFire[] = new Challenge(array('element' => 'fire', 'name' => 'problemInterpretation', 'id_joueur' => $joueur->id()));

		$challengesWater = array();
		$challengesWater[] = new Challenge(array('element' => 'water', 'name' => 'proportionality', 'id_joueur' => $joueur->id()));
		$challengesWater[] = new Challenge(array('element' => 'water', 'name' => 'percentages', 'id_joueur' => $joueur->id()));
		$challengesWater[] = new Challenge(array('element' => 'water', 'name' => 'tables', 'id_joueur' => $joueur->id()));
		$challengesWater[] = new Challenge(array('element' => 'water', 'name' => 'graphs', 'id_joueur' => $joueur->id()));
		$challengesWater[] = new Challenge(array('element' => 'water', 'name' => 'radars', 'id_joueur' => $joueur->id()));
		$challengesWater[] = new Challenge(array('element' => 'water', 'name' => 'bars', 'id_joueur' => $joueur->id()));
		$challengesWater[] = new Challenge(array('element' => 'water', 'name' => 'circulars', 'id_joueur' => $joueur->id()));

		$challengesWind = array();
		$challengesWind[] = new Challenge(array('element' => 'wind', 'name' => 'lines', 'id_joueur' => $joueur->id()));
		$challengesWind[] = new Challenge(array('element' => 'wind', 'name' => 'angles', 'id_joueur' => $joueur->id()));
		$challengesWind[] = new Challenge(array('element' => 'wind', 'name' => 'circles', 'id_joueur' => $joueur->id()));
		$challengesWind[] = new Challenge(array('element' => 'wind', 'name' => 'quadrilaterals', 'id_joueur' => $joueur->id()));
		$challengesWind[] = new Challenge(array('element' => 'wind', 'name' => 'triangles', 'id_joueur' => $joueur->id()));
		$challengesWind[] = new Challenge(array('element' => 'wind', 'name' => 'bisectors', 'id_joueur' => $joueur->id()));
		$challengesWind[] = new Challenge(array('element' => 'wind', 'name' => 'symmetries', 'id_joueur' => $joueur->id()));

		$challengesEarth = array();
		$challengesEarth[] = new Challenge(array('element' => 'earth', 'name' => 'lengths', 'id_joueur' => $joueur->id()));
		$challengesEarth[] = new Challenge(array('element' => 'earth', 'name' => 'weights', 'id_joueur' => $joueur->id()));
		$challengesEarth[] = new Challenge(array('element' => 'earth', 'name' => 'durations', 'id_joueur' => $joueur->id()));
		$challengesEarth[] = new Challenge(array('element' => 'earth', 'name' => 'prices', 'id_joueur' => $joueur->id()));
		$challengesEarth[] = new Challenge(array('element' => 'earth', 'name' => 'perimeters', 'id_joueur' => $joueur->id()));
		$challengesEarth[] = new Challenge(array('element' => 'earth', 'name' => 'areas', 'id_joueur' => $joueur->id()));
		$challengesEarth[] = new Challenge(array('element' => 'earth', 'name' => 'volumes', 'id_joueur' => $joueur->id()));

		return array($challengesFire, $challengesWater, $challengesWind, $challengesEarth);
	}

  public function get_by_name(Joueur $joueur, $name) //Permet de récupérer un défi par son nom et le joueur associé
  {
    $q = $this->_db_RW->prepare('SELECT * FROM challenges WHERE id_joueur= :id_joueur AND name= :name');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
        'name' => $name
    ));
    $donnees = $q->fetch();
    $challenge = new Challenge($donnees);
    return $challenge;
  }

  public function get_by_id($id) //Permet de récupérer un défi par son id
  {
    $q = $this->_db_RW->prepare('SELECT * FROM challenges WHERE id= :id');
    $q->execute(array(
        'id' => $id
    ));
    $donnees = $q->fetch();
    $challenge = new Challenge($donnees);
    return $challenge;
  }

  public function validId($id, Joueur $joueur) //Verify if the id is correct and the player has tried the challenge at least once
  {
    $challenge = $this->get_by_id($id);
	if($challenge && $challenge->getId_joueur() == $joueur->id() && $challenge->getTries() > 0){
		return true;
	} else {
		return false;
	}
  }


  //Permet de récupérer tous les défis qui ont été essayés
  public function get_tried(Joueur $joueur)
  {
    $q = $this->_db_RW->prepare('SELECT * FROM challenges WHERE id_joueur= :id_joueur AND tries > 0 ORDER BY current_level DESC');
    $q->execute(array(
        'id_joueur' => $joueur->id()
    ));
    $challenges = array();
    while ($donnees = $q->fetch()) {
      $challenges[] = new Challenge($donnees);
    }
    return $challenges;
  }

  //Permet de retourner le plus haut rang debloque par le joueur
  public function get_niveau_max(Joueur $joueur)
  {
    $q = $this->_db_RO->prepare('SELECT current_level FROM challenges WHERE id_joueur=:id_joueur ORDER BY current_level DESC LIMIT 1');
    $q->execute(array(
        'id_joueur' => $joueur->id()
    ));
    $donnees = $q->fetch();
    return $donnees["current_level"];
  }

  //Return the count of all challenges stock (assigned or not)
  public function get_all_stock(Joueur $joueur)
  {
    $q = $this->_db_RW->prepare('SELECT SUM(stock) stock FROM challenges WHERE id_joueur= :id_joueur');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $donnees = $q->fetch();
	$sum = $donnees["stock"] + $joueur->stock_challenges();
    return $sum;
  }

  //Return the count of all challenges assigned
  public function get_assigned(Joueur $joueur)
  {
    $q = $this->_db_RW->prepare('SELECT SUM(stock) stock FROM challenges WHERE id_joueur= :id_joueur');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $donnees = $q->fetch();
	$sum = (int)$donnees["stock"];
    return $sum;
  }

  public function get_next_challenge(Joueur $joueur) //Permet de récupérer le prochain challenge du joueur
  {
    $q = $this->_db_RW->prepare('SELECT * FROM challenges WHERE id_joueur= :id_joueur AND stock > 0 ORDER BY current_level ASC, id ASC LIMIT 1');
    $q->execute(array(
        'id_joueur' => $joueur->id()
    ));
    $donnees = $q->fetch();
    $challenge = new Challenge($donnees);
    return $challenge;
  }

  public function get_any_challenge(Joueur $joueur) //Permet de récupérer un challenge au pif
  {
    $q = $this->_db_RW->prepare('SELECT * FROM challenges WHERE id_joueur= :id_joueur ORDER BY current_level ASC, id ASC LIMIT 1');
    $q->execute(array(
        'id_joueur' => $joueur->id()
    ));
    $donnees = $q->fetch();
    $challenge = new Challenge($donnees);
    return $challenge;
  }

  public function countTries(Joueur $joueur) //Permet de compter le nombre de défis effectués par le joueur
  {
	$q = $this->_db_RW->prepare('SELECT SUM(tries) AS tries FROM challenges WHERE id_joueur= :id_joueur');
	$q->execute(array(
		'id_joueur' => $joueur->id(),
		));
	$donnees = $q->fetch();
	return $donnees["tries"];
  }


  //Sum of probability factors
  public function somme_facteurs_proba($challenges) {
    $somme_facteurs_proba = 0;
    //On parcours les challenges
    foreach ($challenges as $challenge)
    {
      $facteur_proba = $this->facteur_proba($challenge);
      $somme_facteurs_proba += $facteur_proba;
    }
    return $somme_facteurs_proba;
  }

   //Focus on challenges non masterized and challenge not already in stock
  public function facteur_proba($challenge) {
    return (1-$challenge->getStock())*pow(5.01 - $challenge->getCurrent_level(), 3);
  }

	public function lowestLevelChallenge($potentialChallenges, $filter){
		$minLevel = 10;
		foreach($potentialChallenges as $challenge){
			if($filter == "tried" && $challenge->getCurrent_level() < $minLevel && !$challenge->practicedToday() && $challenge->getTries() > 0){
				$minLevel = $challenge->getCurrent_level();
			} elseif($filter == "all" && $challenge->getCurrent_level() < $minLevel && !$challenge->practicedToday()){
				$minLevel = $challenge->getCurrent_level();
			}
		}
		if($minLevel == 10){
			$minLevel = 0; //Means that all potential challenges are new and the filter was set to "tried"
		}
		return $minLevel;
	}

	public function shuffleArray($array) {
	  if (!is_array($array)) return $array;
	  $keys = array_keys($array);
	  shuffle($keys);
	  $random = array();
	  foreach ($keys as $key){
			$random[$key] = $array[$key];
		}
	  return $random;
	}

	public function selectChallenge($potentialChallenges, $doneToday){
		$minLevelTried = $this->lowestLevelChallenge($potentialChallenges, "tried");
		$minLevelAll = $this->lowestLevelChallenge($potentialChallenges, "all");
		//If first challenge of the day : revision of a challenge with the lowest current level
		if($doneToday == 0){
			if($minLevelTried <= 2){ //Revision of a challenge not assimilated
				foreach($potentialChallenges as $challenge){
					if($challenge->getCurrent_level() == $minLevelTried && !$challenge->practicedToday()){
						return $challenge;
					}
				}
			} else { //New challenge
				foreach($potentialChallenges as $challenge){
					if($challenge->getCurrent_level() == $minLevelAll && !$challenge->practicedToday()){
						return $challenge;
					}
				}
			}
		}
		//If second challenge of the day : focus on revision if a challenge has not been assimilated, new one otherwise
		elseif($doneToday == 1){
			if($minLevelTried <= 4){ //Revision of a challenge (except lvl 5)
				foreach($potentialChallenges as $challenge){
					if($challenge->getCurrent_level() == $minLevelTried && !$challenge->practicedToday()){
						return $challenge;
					}
				}
			} else { //New challenge
				foreach($potentialChallenges as $challenge){
					if($challenge->getCurrent_level() == $minLevelAll && !$challenge->practicedToday()){
						return $challenge;
					}
				}
			}
		}
		//If third challenge of the day : Focus on new challenges
		elseif($doneToday == 2){
			foreach($potentialChallenges as $challenge){
				if($challenge->getCurrent_level() == $minLevelAll && !$challenge->practicedToday()){
					return $challenge;
				}
			}
		}
		else { //Should not happen but just in case
			foreach($potentialChallenges as $challenge){
				if($challenge->getCurrent_level() == $minLevelAll && !$challenge->practicedToday()){
					return $challenge;
				}
			}
		}

	}

  public function new_mentor(Joueur $joueur) //Determine the new player mentor
  {
		$challenge = $this->get_next_challenge($joueur);
		if($challenge->getElement() == "fire")
			{return "Namuka";}
		if($challenge->getElement() == "water")
			{return "Katillys";}
		if($challenge->getElement() == "wind")
			{return "Sivem";}
		if($challenge->getElement() == "earth")
			{return "Leorn";}
  }

  public function comparePriority($a, $b)  {
    return strcmp($a->priority(), $b->priority());
  }

	public function compareDateLastTry($a, $b)  {
    return strcmp($a->getDate_last_try(), $b->getDate_last_try());
  }

	public function compareNotion($a, $b)  {
    return strcmp($a->notion(), $b->notion());
  }



}
