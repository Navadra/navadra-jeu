<?php

class Score implements JsonSerializable
{
  protected $id,
      $element,
      $challenge,
      $id_joueur,
      $good_answers,
      $wrong_answers,
      $initial_level,
	  $end_level,
      $date_score,
	  $train,
	  $diagnosis,
	  $player_lvl,
	  $player_xp_percent,
	  $player_rank;


  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value) {
      $method = 'set' . ucfirst($key);
      if (method_exists($this, $method)) {
        $this->$method($value);
      }
    }
  }

  public function __construct(array $donnees)
  {
    $this->hydrate($donnees);
  }

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
    $this->id = $id;
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
  public function getChallenge()
  {
    return $this->challenge;
  }

  /**
   * @param mixed $challenge
   */
  public function setChallenge($challenge)
  {
    $this->challenge = $challenge;
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
    $this->id_joueur = $id_joueur;
  }

  /**
   * @return mixed
   */
  public function getGood_answers()
  {
    return $this->good_answers;
  }

  /**
   * @param mixed $good_answers
   */
  public function setGood_answers($good_answers)
  {
    $this->good_answers = $good_answers;
  }

  /**
   * @return mixed
   */
  public function getWrong_answers()
  {
    return $this->wrong_answers;
  }

  /**
   * @param mixed $wrong_answers
   */
  public function setWrong_answers($wrong_answers)
  {
    $this->wrong_answers = $wrong_answers;
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
    $this->initial_level = $initial_level;
  }

  /**
   * @return mixed
   */
  public function getEnd_level()
  {
    return $this->end_level;
  }

  /**
   * @param mixed $end_level
   */
  public function setEnd_level($end_level)
  {
    $this->end_level = $end_level;
  }

  /**
   * @return mixed
   */
  public function getDate_score()
  {
    return $this->date_score;
  }

  /**
   * @param mixed $date_score
   */
  public function setDate_score($date_score)
  {
    $this->date_score = $date_score;
  }

  /**
   * @return mixed
   */
  public function getTrain()
  {
    return $this->train;
  }

  /**
   * @param mixed $train
   */
  public function setTrain($train)
  {
    $this->train = $train;
  }
  
  /**
   * @return mixed
   */
  public function getDiagnosis()
  {
    return $this->diagnosis;
  }

  /**
   * @param mixed $diagnosis
   */
  public function setDiagnosis($diagnosis)
  {
    $this->diagnosis = $diagnosis;
  }

  public function getPlayer_lvl()
  {
    return $this->player_lvl;
  }
  
  public function setPlayer_lvl($player_lvl)
  {
    $this->player_lvl = $player_lvl;
  }
  
  public function getPlayer_xp_percent()
  {
    return $this->player_xp_percent;
  }
  
  public function setPlayer_xp_percent($player_xp_percent)
  {
    $this->player_xp_percent = $player_xp_percent;
  }
  
  public function getPlayer_rank()
  {
    return $this->player_rank;
  }
  
  public function setPlayer_rank($player_rank)
  {
    $this->player_rank = $player_rank;
  }


  //Fonctions utiles au jeu

  //Fonctions d'exportation en Json
  public function jsonSerialize()
  {
    $result = get_object_vars($this);
    return json_encode($result);
  }


}