<?php

class ExercisesManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }


public function add(Exercise $exercise) //Ajoute un exercise dans la bdd
  {
    setlocale(LC_TIME, 'fr_FR');
    date_default_timezone_set('Europe/Paris');
    $date_exercise = strftime('%Y-%m-%d %H:%M:%S', time());
    $q = $this->_db_RW->prepare('INSERT INTO exercises(element, challenge, level, number, mastery, id_joueur, initial_time, answer_time, success, situation, diagnosis, date_exercise) VALUES(:element, :challenge, :level, :number, :mastery, :id_joueur, :initial_time, :answer_time, :success, :situation, :diagnosis, :date_exercise)');
    $q->execute(array(
		'element' => $exercise->getElement(),
		'challenge' => $exercise->getChallenge(),
        'level' => $exercise->getLevel(),
        'number' => $exercise->getNumber(),
		'mastery' => $exercise->getMastery(),
		'id_joueur' => $exercise->getId_joueur(),
        'initial_time' => $exercise->getInitial_time(),
		'answer_time' => $exercise->getAnswer_time(),
        'success' => $exercise->getSuccess(),
		'situation' => $exercise->getSituation(),
		'diagnosis' => $exercise->getDiagnosis(),
		'date_exercise' => $date_exercise,
    ));
    $exercise->hydrate(array(
        'id' => $this->_db_RW->lastInsertId(),
        'date_exercise' => $date_exercise,
    ));
  }

  public function update(Exercise $exercise) //Modifie un défi (tous les paramètres au cas où modifs par admin)
  {
    $q = $this->_db_RW->prepare('UPDATE exercises SET element=:element, challenge=:challenge, level=:level, number=:number, mastery=:mastery, id_joueur=:id_joueur, initial_time=:initial_time, answer_time=:answer_time, success=:success, situation=:situation, diagnosis=:diagnosis, date_exercise=:date_exercise WHERE id=:id');
    $q->execute(array(
        'id' => $exercise->getId(),
		'element' => $exercise->getElement(),
		'challenge' => $exercise->getChallenge(),
        'level' => $exercise->getLevel(),
        'number' => $exercise->getNumber(),
		'mastery' => $exercise->getMastery(),
		'id_joueur' => $exercise->getId_joueur(),
        'initial_time' => $exercise->getInitial_time(),
		'answer_time' => $exercise->getAnswer_time(),
        'success' => $exercise->getSuccess(),
		'situation' => $exercise->getSituation(),
		'diagnosis' => $exercise->getDiagnosis(),
		'date_exercise' => $exercise->getDate_exercise(),
    ));
  }

  public function nb_exercises(Joueur $joueur) //Permet de compter le nombre de exercises du joueur
  {
    $q = $this->_db_RW->prepare('SELECT COUNT(*) AS nb_exercises FROM exercises WHERE id_joueur= :id_joueur');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $donnees = $q->fetch();
    return $donnees["nb_exercises"];
  }

  public function delete_all(Joueur $joueur) //Supprime tous les exercises d'un joueur (s'il supprime son compte)
  {
    $q = $this->_db_RW->prepare('DELETE FROM exercises WHERE id_joueur=:id_joueur');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $q->closeCursor();
  }

  public function get_all(Joueur $joueur) //Permet de récupérer les 3 derniers exercices de chaque type
  {
    $q = $this->_db_RW->prepare('SELECT * FROM exercises WHERE id_joueur= :id_joueur ORDER BY element ASC, challenge ASC, level ASC, number ASC, id DESC');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $exercises = array();
    $i = 0;
	$sameExercises = 0;
    while ($donnees = $q->fetch()) {
      $exercise = new Exercise($donnees);
	  $codeChallenge = $exercise->getElement()."_".$exercise->getChallenge()."_".$exercise->getLevel()."_".$exercise->getNumber();
      if ($i != 0)
	  {
        $previousExercise = $exercises[$i - 1];
		$previousCodeChallenge = $previousExercise->getElement()."_".$previousExercise->getChallenge()."_".$previousExercise->getLevel()."_".$previousExercise->getNumber();
        if ($codeChallenge == $previousCodeChallenge)
        {
          	$sameExercises++;
			if ($sameExercises<=2)
			{
				$exercises[] = $exercise;
          		$i++;
          	}
        }
		else
		{
          	$sameExercises = 0;
			$exercises[] = $exercise;
          	$i++;
        }
      }
	  else
	  {
        $i++;
        $exercises[] = $exercise;
      }
    }
    return $exercises;
  }

  public function get_all_exercises() {
    $q = $this->_db_RW->prepare('SELECT * FROM exercises ORDER BY id ASC');
    $q->execute(array());
    $exercises = array();
    while ($donnees = $q->fetch()) {
      $exercise = new Exercise($donnees);
      $exercises[] = $exercise;
    }
    return $exercises;
  }

	public function playersActivity($periodStart, $periodEnd) {
    $q = $this->_db_RO->prepare(
			'SELECT DISTINCT SUBSTR(e.date_exercise, 1, 10) AS dateEx, e.id_joueur AS playerId, SUBSTR(j.date_inscription, 1, 10) as dateSignUp
			FROM exercises e
			INNER JOIN joueurs j
			ON j.id = e.id_joueur
			WHERE  date_exercise>= :periodStart AND date_exercise<= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4)
			ORDER BY e.id ASC');
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

	public function playersActivityBySignUp($periodStart, $periodEnd) {
    $q = $this->_db_RO->prepare(
			'SELECT DISTINCT SUBSTR(e.date_exercise, 1, 10) AS dateEx, e.id_joueur AS playerId, SUBSTR(j.date_inscription, 1, 10) as dateSignUp
			FROM exercises e
			INNER JOIN joueurs j
			ON j.id = e.id_joueur
			WHERE  date_exercise>= :periodStart AND date_inscription >= :periodStart AND date_inscription <= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4)
			ORDER BY e.id ASC');
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

	public function median($numbers=array()) {
		if (!is_array($numbers))
			$numbers = func_get_args();

		rsort($numbers);
		$mid = (count($numbers) / 2);
		return ($mid % 2 != 0) ? $numbers{$mid-1} : (($numbers{$mid-1}) + $numbers{$mid}) / 2;
	}

	public function medianLengthSession($periodStart, $periodEnd) {
    $q = $this->_db_RO->prepare(
			'SELECT e.date_exercise AS dateEx, e.id_joueur AS playerId, SUBSTR(j.date_inscription, 1, 10) as dateSignUp
			FROM exercises e
			INNER JOIN joueurs j
			ON j.id = e.id_joueur
			WHERE  date_exercise>= :periodStart AND date_exercise <= :periodEnd AND (j.classe= :class1 OR j.classe= :class2 OR j.classe= :class3 OR j.classe= :class4)
			ORDER BY j.id ASC, e.id ASC');
    $q->execute(array(
			'class1' => "6°",
			'class2' => "5°",
			'class3' => "4°",
			'class4' => "3°",
			'periodStart' => $periodStart,
			'periodEnd' => $periodEnd
		));
		$lengthSessions = array();
		$startSession = "";
		$endSession = "";
		$playerId = 0;
    while ($donnees = $q->fetch()) {
			if($playerId == 0){
				$playerId = $donnees["playerId"];
			}
			if($startSession == ""){
				$startSession = $donnees["dateEx"];
			}
			if($donnees["playerId"] != $playerId){
				if($endSession == ""){
					$endSession = $startSession;
				}
				$lengthSessions[] = round((strtotime($endSession) - strtotime($startSession))/60, 1);
				$playerId = $donnees["playerId"];
				$startSession = $donnees["dateEx"];
				$endSession = "";
			} else {
				if(substr($donnees["dateEx"], 0, 10) != substr($startSession, 0, 10)){
					if($endSession == ""){
						$endSession = $startSession;
					}
					$lengthSessions[] = round((strtotime($endSession) - strtotime($startSession))/60, 1);
					$startSession = $donnees["dateEx"];
					$endSession = "";
				} else {
					$endSession = $donnees["dateEx"];
				}
			}
    }
		$median = $this->median($lengthSessions);
		$minutes = floor($median);
		$seconds = ($median - $minutes)*60;
    return $minutes."min ".$seconds."s";
  }

  public function get_all_exercises_player($id_player) {
    $q = $this->_db_RW->prepare('SELECT * FROM exercises WHERE id_joueur= :id_joueur ORDER BY id ASC');
    $q->execute(array(
		'id_joueur' => $id_player,
	));
    $exercises = array();
    while ($donnees = $q->fetch()) {
      $exercise = new Exercise($donnees);
      $exercises[] = $exercise;
    }
    return $exercises;
  }

  public function get_average_answer_time(Joueur $joueur) //Get the average answer time for each exercise
  {
    $q = $this->_db_RO->prepare('SELECT * FROM exercises WHERE id_joueur= :id_joueur ORDER BY element DESC, challenge DESC, level DESC, number DESC, id DESC');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $exercises = array();
	$sameExercises = 0;
    while ($donnees = $q->fetch())
	{
      $exercise = new Exercise($donnees);
	  $codeChallenge = $exercise->getElement()."_".$exercise->getChallenge()."_".$exercise->getLevel()."_".$exercise->getNumber();
      if (isset($exercises[$codeChallenge]))
	  {
      		$sameExercises++;
		    if ($sameExercises<=2 && $exercise->getSuccess() == 1)
			{
				$exercises[$codeChallenge] = ($exercises[$codeChallenge]*$sameExercises + (int)$exercise->getAnswer_time())/($sameExercises+1);
          	}
			else if ($sameExercises<=2 && $exercise->getSuccess() == 0)
			{
				$exercises[$codeChallenge] = ($exercises[$codeChallenge]*$sameExercises + (int)$exercise->getInitial_time())/($sameExercises+1);
          	}
      }
	  else
	  {
      		if ($exercise->getSuccess() == 1)
			{
				$sameExercises = 0;
				$exercises[$codeChallenge] = (int)$exercise->getAnswer_time();
			}
			else if ($exercise->getSuccess() == 1)
			{
				$sameExercises = 0;
				$exercises[$codeChallenge] = (int)$exercise->getInitial_time();
			}
      }
    }
    return $exercises;
  }

  public function get_dernier_exercise(Joueur $joueur) //Permet de récupérer le dernier exercise effectué par un joueur
  {
    $q = $this->_db_RW->prepare('SELECT * FROM exercises WHERE id_joueur= :id_joueur ORDER BY id DESC LIMIT 0, 1');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $donnees = $q->fetch();
    $exercise = new Exercise($donnees);
    return $exercise;
  }

  public function has_exercised(Joueur $joueur) //Permet de savoir si le joueur à déjà scoré (fait un challenge) une fois
  {
    $dernier_defi = $this->get_dernier_exercise($joueur); //On récupère le dernier exercise réalisé par le joueur
    return ($dernier_defi != null && $dernier_defi->getId() != null && $dernier_defi->getId() > 0);
  }

  public function has_exercised_today(Joueur $joueur) //Vérifie si le joueur a réalisé un défi aujourd'hui
  {
    $dernier_defi = $this->get_dernier_exercise($joueur); //On récupère le dernier exercise réalisé par le joueur
    $jour_dernier_defi = substr($dernier_defi->getDate_exercise(), 0, 10); //On récupère la date du jour (sans les heures) du dernier défi réalisé
    $today = substr(strftime('%Y-%m-%d %H:%M:%S', time()), 0, 10); //On récupère la date d'aujourd'hui (sans les heures)

    //Si le dernier défi a bien été réalisé aujourd'hui
    return $today == $jour_dernier_defi;
  }


  public function get_premier_exercise(Joueur $joueur) //Permet de récupérer le premier exercise effectué par un joueur
  {
    $q = $this->_db_RW->prepare('SELECT * FROM exercises WHERE id_joueur= :id_joueur ORDER BY id ASC LIMIT 0, 1');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $donnees = $q->fetch();
    $exercise = new Exercise($donnees);
    return $exercise;
  }

  public function get($id) //Permet de récupérer un exercise par son id
  {
    $q = $this->_db_RW->prepare('SELECT * FROM exercises WHERE id= :id');
    $q->execute(array(
        'id' => $id,
    ));
    $donnees = $q->fetch();
    $exercise = new Exercise($donnees);
    return $exercise;
  }


}
