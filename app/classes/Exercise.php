<?php

class Exercise implements JsonSerializable
{
  protected $id,
      $element,
      $challenge,
      $level,
      $number,
	  $mastery,
	  $id_joueur,
      $initial_time,
	  $answer_time,
      $success,
	  $situation,
	  $diagnosis,
	  $date_exercise;


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
  public function getLevel()
  {
    return $this->level;
  }

  /**
   * @param mixed $level
   */
  public function setLevel($level)
  {
    $this->level = $level;
  }

  /**
   * @return mixed
   */
  public function getNumber()
  {
    return $this->number;
  }

  /**
   * @param mixed $number
   */
  public function setNumber($number)
  {
    $this->number = $number;
  }
  
  /**
   * @return mixed
   */
  public function getMastery()
  {
    return $this->mastery;
  }

  /**
   * @param mixed $number
   */
  public function setMastery($mastery)
  {
    $this->mastery = $mastery;
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
  public function getInitial_time()
  {
    return $this->initial_time;
  }

  /**
   * @param mixed $initial_time
   */
  public function setInitial_time($initial_time)
  {
    $this->initial_time = $initial_time;
  }

  /**
   * @return mixed
   */
  public function getAnswer_time()
  {
    return $this->answer_time;
  }

  /**
   * @param mixed $answer_time
   */
  public function setAnswer_time($answer_time)
  {
    $this->answer_time = $answer_time;
  }

  /**
   * @return mixed
   */
  public function getSuccess()
  {
    return $this->success;
  }

  /**
   * @param mixed $success
   */
  public function setSuccess($success)
  {
    $this->success = $success;
  }
  
  /**
   * @return mixed
   */
  public function getSituation()
  {
    return $this->situation;
  }

  /**
   * @param mixed $situation
   */
  public function setSituation($situation)
  {
    $this->situation = $situation;
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
  
  /**
   * @return mixed
   */
  public function getDate_exercise()
  {
    return $this->date_exercise;
  }

  /**
   * @param mixed $date_exercise
   */
  public function setDate_exercise($date_exercise)
  {
    $this->date_exercise = $date_exercise;
  }



  //Fonctions utiles au jeu

  //Fonctions d'exportation en Json
  public function jsonSerialize()
  {
    $result = get_object_vars($this);
    return json_encode($result);
  }


}