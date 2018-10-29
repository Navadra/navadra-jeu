<?php

class Challenge
{

  protected $id,
      $element,
      $name,
      $id_joueur,
      $current_level,
	  	$initial_level,
      $tries,
      $stock,
			$date_last_try,
	  	$time_remaining, //Not stored in DB
			$additionalData; //Not stored in DB

  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value) {
      $method = 'set' . ucfirst($key);
      if (method_exists($this, $method)) {
        $this->$method($value);
      }
    }
  }

  public function __construct(array $donnees) {
    $this->hydrate($donnees);
		if(!isset($this->id))
		{
			$this->setId(0);
		}
		if(!isset($this->current_level))
		{
			$this->setCurrent_level(0);
		}
		if(!isset($this->initial_level))
		{
			$this->setInitial_level(0);
		}
		if(!isset($this->tries))
		{
			$this->setTries(0);
		}
		if(!isset($this->stock))
		{
			$this->setStock(0);
		}
		if(!isset($this->date_last_try))
		{
			$this->setDate_last_try("2016-01-01 08:00:00");
		}
  }

	//GETTERS & SETTERS
  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = (int) $id;
  }

  /**
   * @return mixed
   */
  public function getElement()
  {
    return $this->element;
  }

  /**
   * @param mixed $element
   */
  public function setElement($element)
  {
    $this->element = $element;
  }

  /**
   * @return mixed
   */
  public function getId_joueur()
  {
    return $this->id_joueur;
  }

  /**
   * @param mixed $id_joueur
   */
  public function setId_joueur($id_joueur)
  {
    $this->id_joueur = (int) $id_joueur;
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

	//SETTERS
  /**
   * @param mixed $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getCurrent_level()
  {
    return $this->current_level;
  }

  /**
   * @param mixed $current_level
   */
  public function setCurrent_level($current_level)
  {
    $this->current_level = (int) $current_level;
  }

  /**
   * @return mixed
   */
  public function getInitial_level()
  {
    return $this->initial_level;
  }

  /**
   * @param mixed $initial_level
   */
  public function setInitial_level($initial_level)
  {
    $this->initial_level = (int) $initial_level;
  }

  /**
   * @return mixed
   */
  public function getTries()
  {
    return $this->tries;
  }

  /**
   * @param mixed $current_level
   */
  public function setTries($tries)
  {
    $this->tries = (int) $tries;
  }

  /**
   * @return mixed
   */
  public function getStock()
  {
    return $this->stock;
  }

  /**
   * @param mixed $stock
   */
  public function setStock($stock)
  {
    $this->stock = (int) $stock;
  }

 public function getDate_last_try()
 {
	 return $this->date_last_try;
 }

 /**
	* @param mixed $stock
	*/
 public function setDate_last_try($date_last_try)
 {
	 $this->date_last_try = $date_last_try;
 }


  /**
   * @return mixed
   */
  public function getTime_remaining()
  {
    return $this->time_remaining;
  }

  /**
   * @param mixed $time_remaining
   */
  public function setTime_remaining($time_remaining)
  {
    $this->time_remaining = $time_remaining;
  }

	/**
   * @return mixed
   */
  public function getAdditionalData()
  {
    return $this->additionalData;
  }

  /**
   * @param mixed $time_remaining
   */
  public function setAdditionalData($additionalData)
  {
    $this->additionalData = $additionalData;
  }

  //Title of the challenge
  public function notion()
  {
    switch ($this->getName()) {
      // FIRE
      case "decimals" :
        return "Nombres décimaux";
      case "divisions" :
        return "Divisions Euclidiennes";
      case "fractions" :
        return "Manipulation de fractions";
      case "greatNumbers" :
        return "Manipuler les grands nombres";
      case "integers" :
        return "Nombres entiers";
      case "multiples" :
        return "Multiples et diviseurs";
      case "problemInterpretation" :
        return "Interprétation de problèmes";

      // WATER
      case "bars" :
        return "Diagrammes en barres";
      case "circulars" :
        return "Diagrammes circulaires";
      case "graphs" :
        return "Lecture de graphiques";
      case "proportionality" :
        return "Proportionnalité";
      case "percentages" :
        return "Pourcentages";
      case "radars" :
        return "Diagrammes radars";
      case "tables" :
        return "Lecture de tableaux";

      // WIND
      case "angles" :
        return "Les angles";
      case "bisectors" :
        return "Médiatrices et hauteurs";
      case "circles" :
        return "Les cercles";
      case "lines" :
        return "Droites, segments et demi-droites";
      case "quadrilaterals" :
        return "Les quadrilatères";
      case "symmetries" :
        return "Symétrie axiale";
      case "triangles" :
        return "Reconnaissance de triangles";

      // EARTH
      case "areas" :
        return "Les aires";
      case "durations" :
        return "Les durées";
      case "lengths" :
        return "Les longueurs";
      case "perimeters" :
        return "Les périmètres";
      case "prices" :
        return "Les prix";
      case "volumes" :
        return "Les volumes";
      case "weights" :
        return "Les masses";

      default:
        return "Defi inconnu";
    }
  }

  public function title_element()
  {
    switch ($this->getElement())
	{
      case "fire" :
        return "Nombres et Calculs";
      case "water" :
        return "Gestion de données et Fonctions";
      case "wind" :
        return "Espace et Géométrie";
      case "earth" :
        return "Grandeurs et Mesures";
    }
  }

  public function priority()
  {
    switch ($this->getName())
	{
      // FIRE
      case "decimals" :
        return 2;
      case "divisions" :
        return 2;
      case "fractions" :
        return 3;
      case "greatNumbers" :
        return 2;
      case "integers" :
        return 1;
      case "multiples" :
        return 2;
      case "problemInterpretation" :
        return 3;

      // WATER
      case "bars" :
        return 3;
      case "circulars" :
        return 2;
      case "graphs" :
        return 2;
      case "proportionality" :
        return 1;
      case "percentages" :
        return 2;
      case "radars" :
        return 3;
      case "tables" :
        return 1;

      // WIND
      case "angles" :
        return 2;
      case "bisectors" :
        return 3;
      case "circles" :
        return 2;
      case "lines" :
        return 1;
      case "quadrilaterals" :
        return 2;
      case "symmetries" :
        return 1;
      case "triangles" :
        return 2;

      // EARTH
      case "areas" :
        return 3;
      case "durations" :
        return 1;
      case "lengths" :
        return 1;
      case "perimeters" :
        return 2;
      case "prices" :
        return 2;
      case "volumes" :
        return 2;
      case "weights" :
        return 2;

      default:
        return 0;
    }
  }

  //Utils functions
  public function element_img()
  {
	  switch ($this->getElement())
	{
      case "fire" :
        return "/webroot/img/elements/feu.png";
      case "water" :
        return "/webroot/img/elements/eau.png";
      case "wind" :
        return "/webroot/img/elements/vent.png";
      case "earth" :
        return "/webroot/img/elements/terre.png";
    }
  }

  public function mastery_img()
  {
	  if ($this->getCurrent_level() >= 5){
        return "/webroot/img/icones/check.png";
	  } else {
		return "/webroot/img/icones/sablier2.png";
	  }
  }

  public function mastery_title()
  {
	  if ($this->getCurrent_level() >= 5){
        return "Tu as prouvé que tu maîtrisais ce défi.";
	  } else {
		return "Tu n'as pas encore maîtrisé ce défi.";
	  }
  }

  public function level_title()
  {
	  if ($this->getCurrent_level() >= 1){
        return "Tu as atteint la Maîtrise ".min(5,$this->getCurrent_level())." sur ce défi.";
	  } else {
		return "Tu dois d'abord avoir rencontré ce défi une fois avant de pouvoir t'entraîner dessus.";
	  }
  }

  public function ultimate_img()
  {
	  if ($this->getCurrent_level() == 6){
        return "/webroot/img/icones/ultime_mastery.png";
	  }
	  elseif ($this->getCurrent_level() < 5){
        return "/webroot/img/icones/lock.png";
	  }
	  elseif ($this->getTime_remaining() == 0){
        return "/webroot/img/icones/play.png";
	  } else {
		return "/webroot/img/icones/sablier.png";
	  }
  }

  public function ultimate_img_size()
  {
	  if ($this->getCurrent_level() == 6){
        return "img_50";
	  }
	  elseif ($this->getCurrent_level() < 5){
        return "img_30";
	  }
	  elseif ($this->getTime_remaining() == 0){
        return "img_40";
	  } else {
		return "img_40";
	  }
  }

  public function ultimate_title()
  {
	  if ($this->getCurrent_level() == 6){
        return "Tu as réussi la Maîtrise Ultime sur ce défi. Félicitations !";
	  }
	  elseif ($this->getCurrent_level() < 5){
        return "Atteins la Maîtrise 5 sur ce défi pour débloquer ce secret.";
	  }
	  elseif ($this->getTime_remaining() == 0){
        return "Tu as le droit à un essai aujourd'hui pour atteindre la Maîtrise Ultime.";
	  } else {
		return "Tu dois attendre ".$this->getTime_remaining()." avant de pouvoir tenter la Maîtrise Ultime.";
	  }
  }

  public function element_class_color()
  {
	  switch ($this->getElement())
	{
      case "fire" :
        return "rouge";
      case "water" :
        return "bleu";
      case "wind" :
        return "jaune";
      case "earth" :
        return "vert";
    }
  }

  //Challenge level display to user
  public function getLevel_user($level, $tries) {
		if($tries == 0 || $level == 0)		{
			return "Découverte";
		}
		elseif($level >= 5)		{
			return "Défi Ultime";
		}
		else	{
			return "Maîtrise ".$level;
		}
  }

  	//Game mecanics
    public function bonusXP($level, Joueur $joueur) //Not used anymore
    {
		switch($level)
		{
			case 1 :
				$coeff = 0.5;
				break;
			case 2 :
				$coeff = 1;
				break;
			case 3 :
				$coeff = 2;
				break;
			case 4 :
				$coeff = 3;
				break;
			case 5 :
				$coeff = 4;
				break;
			case 6 :
				$coeff = 5;
				break;
		}
		$normal_bonus = round($this->regularXP(10, $joueur) * $coeff);
		$min = 0;
		if($joueur->tuto() != "fini")
		{
			$min = 55; //At least 55 for first challenge
		}
		return max($min,$normal_bonus);
	}

	public function bonusPyrs($level, Joueur $joueur) //Not used anymore
	{
		$normal_bonus = round($this->bonusXP($level, $joueur) / 5);
		$min = 0;
		if($joueur->tuto() != "fini")
		{
			$min = 11; //At least 11 for first challenge (cost of a spell)
		}
		return max($min,$normal_bonus);
	}

	public function regularXP($score)
	{
		if($score <= 1){
			return 33;
		} elseif($score <= 3){
			return 40;
		} elseif($score <= 5){
			return 48;
		} elseif($score <= 7){
			return 57;
		} elseif($score <= 9){
			return 69;
		} elseif($score == 10){
			return 82;
		}
	}

	public function regularPyrs($score, Joueur $joueur)	{
		if($joueur->tuto() != "fini"){
			return max(7, 3*$score);
		} else {
			return max(3, 3*$score);
		}
	}

	public function endMessage(Joueur $joueur){
		switch ($joueur->sexe()) {
	    case "gars" :
	      $e = "";
				$ne = "";
	      break;
	    case "fille" :
	      $e = "e";
				$ne = "ne";
	      break;
	  }
		$level = $this->getCurrent_level();
		if($this->getTries() == 0) { //Diagnosis
			if($level== 1)  {
	      $achievementMsg = '<span class="p3 ib l100">Tu commences le défi "'.$this->notion().'" en Maîtrise 1.<br><br>Objectif pour la prochaine fois : la Maîtrise 2 !</span>';
	    } elseif($level== 2) {
	      $achievementMsg = '<span class="p3 ib l100">Tu commences le défi "'.$this->notion().'" en Maîtrise 2.<br><br>Quelque chose me dit que tu n\'es pas le genre de personne à t\'arrêter là !</span>';
	    } elseif($level== 3) {
	      $achievementMsg = '<span class="p3 ib l100">Tu commences le défi "'.$this->notion().'" en Maîtrise 3.<br><br>Tu m\'as l\'air de déjà bien connaître cette notion, la prochaine fois ce sera plus difficile pour que tu puisses vraiment progresser !</span>';
	    } elseif($level== 4) {
	      $achievementMsg = '<span class="p3 ib l100">Je vois que tu es déjà très à l\'aise sur le défi "'.$this->notion().'", tu as de quoi devenir un'.$e.' grand'.$e.' magicien'.$ne.' !<br><br>Pour que ta magie devienne plus puissante encore, je vais augmenter la difficulté la prochaine fois que tu rencontreras cette notion.</span>';
	    } elseif($level == 5) {
	      $achievementMsg = '<span class="p3 ib l100">Je vois que tu es déjà très à l\'aise sur le défi "'.$this->notion().'", tu as de quoi devenir un'.$e.' grand'.$e.' magicien'.$ne.' !<br><br>Pour que ta magie devienne plus puissante encore, je vais augmenter la difficulté la prochaine fois que tu rencontreras cette notion.</span>';
	    }
		} else { //Regular challenge
				if($level== 2) {
	      $achievementMsg = '<span class="p3 ib l100">BRAVO !<br><br>Tu as atteint la Maîtrise 2 du défi "'.$this->notion().'".<br><br>Quelque chose me dit que tu n\'es pas le genre de personne à t\'arrêter là !</span>';
	    } elseif($level== 3) {
	      $achievementMsg = '<span class="p3 ib l100">BRAVO !<br><br>Tu as atteint la Maîtrise 3 du défi "'.$this->notion().'".<br><br>Tes efforts sont récompensés, continue comme ça !</span>';
	    } elseif($level== 4) {
	      $achievementMsg = '<span class="p3 ib l100">BRAVO !<br><br>Tu as atteint la Maîtrise 4 du défi "'.$this->notion().'".<br><br>Ton entrainement te rapproche de plus en plus de la maîtrise de ce défi !</span>';
	    } elseif($level == 5) {
	      $achievementMsg = '<span class="p3 ib l100">FÉLICITATIONS !<br><br>Tu as atteint la Maîtrise 5 du défi "'.$this->notion().'".<br><br>Encore un peu d\'efforts et tu pourras atteindre la Maîtrise Ultime...</span>';
	    } elseif($level == 6) {
	      $achievementMsg = '<span class="p3 ib l100">INCROYABLE !<br><br>Tu viens de réussir la Maîtrise Ultime pour le défi "'.$this->notion().'".<br><br>Je ne suis pas du genre à faire beaucoup de compliments mais là, ta persévérance m\'épate !</span>';
	    }
		}
		return $achievementMsg;
	}

	public function congratulations(){
		if($this->getTries() == 0 && ($this->getCurrent_level() == 4 || $this->getCurrent_level() ==5)) {
			return 1;
		} else {
			return 1;
		}
	}

	public function loading_msg(){
	  $rand = rand(1, 6);
	  switch($rand){
		  case 1 :
		  	return "Canalisation de la magie...";
			break;
		  case 2 :
		  	return "Invocation de boissons rafraîchissantes...";
			break;
		  case 3 :
		  	return "Echauffement des méninges...";
			break;
		  case 4 :
		  	return "A la recherche d'un sujet d'entraînement...";
			break;
		  case 5 :
		  	return "Adoption de la posture du chimpanzé avisé...";
			break;
		  case 6 :
		  	return "Adoption de la posture du rapace sagace...";
			break;
      }
	}

	public function practicedToday(){
		$lastPractice = substr($this->getDate_last_try(), 0, 10); //On récupère la date du jour (sans les heures) du dernier défi réalisé
		setlocale(LC_TIME, 'fr_FR');
    date_default_timezone_set('Europe/Paris');
		$today = substr(strftime('%Y-%m-%d %H:%M:%S', time()), 0, 10); //On récupère la date d'aujourd'hui (sans les heures)
    //Si le dernier défi a bien été réalisé aujourd'hui
    return $today == $lastPractice;
	}

	public function daysLastPractice(){
		$lastPractice = substr($this->getDate_last_try(), 0, 10); //On récupère la date du jour (sans les heures) du dernier défi réalisé
		setlocale(LC_TIME, 'fr_FR');
    date_default_timezone_set('Europe/Paris');
		$today = substr(strftime('%Y-%m-%d %H:%M:%S', time()), 0, 10); //On récupère la date d'aujourd'hui (sans les heures)
    $nbDays = round((strtotime($today) - strtotime($lastPractice))/60/60/24);
    return $nbDays." j";
	}

}
