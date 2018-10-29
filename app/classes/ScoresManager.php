<?php

class ScoresManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }

  public function add(Score $score) //Ajoute un score dans la bdd
  {
    setlocale(LC_TIME, 'fr_FR');
    date_default_timezone_set('Europe/Paris');
    $date_score = strftime('%Y-%m-%d %H:%M:%S', time());
    $q = $this->_db_RW->prepare('INSERT INTO scores(element, challenge, id_joueur, good_answers, wrong_answers, initial_level, end_level, train, diagnosis, player_lvl, player_xp_percent, player_rank, date_score) VALUES(:element, :challenge, :id_joueur, :good_answers, :wrong_answers, :initial_level, :end_level, :train, :diagnosis, :player_lvl, :player_xp_percent, :player_rank, :date_score)');
    $q->execute(array(
		'element' => $score->getElement(),
		'challenge' => $score->getChallenge(),
        'id_joueur' => $score->getId_joueur(),
        'good_answers' => $score->getGood_answers(),
		'wrong_answers' => $score->getWrong_answers(),
        'initial_level' => $score->getInitial_level(),
		'end_level' => $score->getEnd_level(),
        'date_score' => $date_score,
		'train' => $score->getTrain(),
		'diagnosis' => $score->getDiagnosis(),
		'player_lvl' => $score->getPlayer_lvl(),
		'player_xp_percent' => $score->getPlayer_xp_percent(),
		'player_rank' => $score->getPlayer_rank(),
    ));
    $score->hydrate(array(
        'id' => $this->_db_RW->lastInsertId(),
        'date_score' => $date_score,
    ));
  }

  public function update(Score $score) //Modifie un défi (tous les paramètres au cas où modifs par admin)
  {
    $q = $this->_db_RW->prepare('UPDATE scores SET element=:element, challenge=:challenge, id_joueur=:id_joueur, good_answers=:good_answers, wrong_answers=:wrong_answers, initial_level=:initial_level, end_level=:end_level, date_score=:date_score, train=:train, diagnosis=:diagnosis, player_lvl=:player_lvl, player_xp_percent=:player_xp_percent, player_rank=:player_rank WHERE id=:id');
    $q->execute(array(
        'id' => $score->getId(),
		'element' => $score->getElement(),
		'challenge' => $score->getChallenge(),
        'id_joueur' => $score->getId_joueur(),
        'good_answers' => $score->getGood_answers(),
		'wrong_answers' => $score->getWrong_answers(),
        'initial_level' => $score->getInitial_level(),
		'end_level' => $score->getEnd_level(),
        'date_score' => $score->getDate_score(),
		'train' => $score->getTrain(),
		'diagnosis' => $score->getDiagnosis(),
		'player_lvl' => $score->getPlayer_lvl(),
		'player_xp_percent' => $score->getPlayer_xp_percent(),
		'player_rank' => $score->getPlayer_rank(),
    ));
  }

  public function nb_scores(Joueur $joueur) //Permet de compter le nombre de scores du joueur
  {
    $q = $this->_db_RO->prepare('SELECT COUNT(*) AS nb_scores FROM scores WHERE id_joueur= :id_joueur');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $donnees = $q->fetch();
    return $donnees["nb_scores"];
  }

  public function delete_all(Joueur $joueur) //Supprime tous les scores d'un joueur (s'il supprime son compte)
  {
    $q = $this->_db_RW->prepare('DELETE FROM scores WHERE id_joueur=:id_joueur');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $q->closeCursor();
  }

  public function get_all(Joueur $joueur) //Permet de récupérer tous les scores liés à un joueur triés par num défi croissant
  {
    $q = $this->_db_RO->prepare('SELECT * FROM scores WHERE id_joueur= :id_joueur ORDER BY num_defi ASC, id ASC');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $scores = array();
    $i = 0;
    while ($donnees = $q->fetch()) {
      $score = new Score($donnees);
      if ($i != 0) {
        $score_precedent = $scores[$i - 1];
        if ($score->num_defi() == $score_precedent->num_defi() && substr($score->date_score(), 0, 10) == substr($score_precedent->date_score(), 0, 10)) //Si les scores ont eu lieu la même date on prend juste le meilleur des 2
        {
          if ($score->palier() >= $score_precedent->palier()) {
            $scores[$i - 1] = $score;
          }
        } else {
          $scores[] = $score;
          $i++;
        }
      } else {
        $i++;
        $scores[] = $score;
      }
    }
    return $scores;
  }

  public function get_all_scores()
  {
    $q = $this->_db_RO->prepare('SELECT * FROM scores ORDER BY id ASC');
    $q->execute(array());
    $scores = array();
    while ($donnees = $q->fetch()) {
      $score = new Score($donnees);
       $scores[] = $score;
    }
    return $scores;
  }

  public function get_all_scores_player($id_player)
  {
    $q = $this->_db_RO->prepare('SELECT * FROM scores WHERE id_joueur= :id_joueur ORDER BY id ASC');
    $q->execute(array(
		'id_joueur' => $id_player,
	));
    $scores = array();
    while ($donnees = $q->fetch()) {
      $score = new Score($donnees);
       $scores[] = $score;
    }
    return $scores;
  }

  public function get_non_training_scores_player(Joueur $joueur)
  {
    $q = $this->_db_RO->prepare('SELECT * FROM scores WHERE id_joueur= :id_joueur AND train= :train ORDER BY id ASC');
    $q->execute(array(
		'id_joueur' => $joueur->id(),
		'train' => 0,
	));
    $scores = array();
    while ($donnees = $q->fetch()) {
      $score = new Score($donnees);
       $scores[] = $score;
    }
    return $scores;
  }

  public function get_dernier_score(Joueur $joueur) //Permet de récupérer le dernier score effectué par un joueur
  {
    $q = $this->_db_RO->prepare('SELECT * FROM scores WHERE id_joueur= :id_joueur AND train= :train ORDER BY id DESC LIMIT 0, 1');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
				'train' => 0
    ));
    if($donnees = $q->fetch())
	{
   		return new Score($donnees);
	}
	else
	{
		return null;
	}
  }

  public function has_scored(Joueur $joueur) //Permet de savoir si le joueur à déjà scoré (fait un challenge) une fois
  {
    $dernier_defi = $this->get_dernier_score($joueur); //On récupère le dernier score réalisé par le joueur
    return ($dernier_defi != null && $dernier_defi->getId() != null && $dernier_defi->getId() > 0);
  }

  public function has_scored_today(Joueur $joueur) //Vérifie si le joueur a réalisé un défi aujourd'hui
  {
		$dernier_defi = $this->get_dernier_score($joueur); //On récupère le dernier score réalisé par le joueur
		if($dernier_defi != null && $dernier_defi->getId() != null && $dernier_defi->getId() > 0) {
			$jour_dernier_defi = substr($dernier_defi->getDate_score(), 0, 10); //On récupère la date du jour (sans les heures) du dernier défi réalisé
			$today = substr(strftime('%Y-%m-%d %H:%M:%S', time()), 0, 10); //On récupère la date d'aujourd'hui (sans les heures)
	    	//Si le dernier défi a bien été réalisé aujourd'hui
	    	return $today == $jour_dernier_defi;
		}	else {
			return false;
		}
  }

	public function count_scored_today(Joueur $joueur){ //Count the number of challenges done today
		setlocale(LC_TIME, 'fr_FR');
    date_default_timezone_set('Europe/Paris');
		$today = strftime('%Y-%m-%d', time());
		$q = $this->_db_RO->prepare('SELECT COUNT(*) AS nb_scores FROM scores WHERE (id_joueur= :id_joueur) AND (date_score LIKE :today) AND (train= :train)');
		$q->execute(array(
			'id_joueur' => $joueur->id(),
			'today' => $today." %",
			'train' => 0
	 	));
    $donnees = $q->fetch();
    return (int)$donnees["nb_scores"];
  }


  public function get_premier_score(Joueur $joueur) //Permet de récupérer le premier score effectué par un joueur
  {
    $q = $this->_db_RO->prepare('SELECT * FROM scores WHERE id_joueur= :id_joueur ORDER BY id ASC LIMIT 0, 1');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $donnees = $q->fetch();
    $score = new Score($donnees);
    return $score;
  }

  public function get($id) //Permet de récupérer un score par son id
  {
    $q = $this->_db_RO->prepare('SELECT * FROM scores WHERE id= :id');
    $q->execute(array(
        'id' => $id,
    ));
    $donnees = $q->fetch();
    $score = new Score($donnees);
    return $score;
  }

  public function get_last_score_challenge(Challenge $challenge, Joueur $joueur) //Get the last score of a given challenge done by the player (to see if he can do the mastery today)
  {
	$q = $this->_db_RO->prepare('SELECT * FROM scores WHERE id_joueur= :id_joueur AND element= :element AND challenge= :challenge AND train= :train ORDER BY id DESC LIMIT 0, 1');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
		'element' => $challenge->getElement(),
		'challenge' => $challenge->getName(),
		'train' => 0,
    ));
    if($donnees = $q->fetch())
	{
   		return new Score($donnees);
	}
	else
	{
		return null;
	}
  }

	public function globalChallengeProgress ($periodStart, $periodEnd) {
		$q = $this->_db_RO->prepare(
			'SELECT j.id AS playerId, s.id AS scoreId, s.challenge AS challengeName, s.end_level AS endLevel
			FROM scores s
				INNER JOIN joueurs j
				ON s.id_joueur = j.id
			WHERE  j.date_inscription>= :periodStart AND j.date_inscription<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4)
			ORDER BY j.id ASC, s.challenge ASC, s.id ASC');
			$q->execute(array(
				'class1' => "6°",
				'class2' => "5°",
				'class3' => "4°",
				'class4' => "3°",
				'periodStart' => $periodStart,
				'periodEnd' => $periodEnd
			));
			$result = array();
			$count = 1;
			$nameChallenge = "";
	    while ($donnees = $q->fetch()) {
				if($donnees["playerId"]."_".$donnees["challengeName"] != $nameChallenge){
					$count = 1;
					$nameChallenge = $donnees["playerId"]."_".$donnees["challengeName"];
				} else {
					$count ++;
				}
				if(!isset($result[$count])){
					$result[$count] = array();
				}
				$result[$count][] = $donnees["endLevel"];
	    }
			foreach($result as $tries => $array){
				$result[$tries] = array(
					"Number of tries" => count($array),
					"Average end level" => round(array_sum($array) / count($array), 2)
				);
			}
	    return $result;
	}


}
