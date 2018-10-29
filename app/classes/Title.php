<?php

class Title implements JsonSerializable
{
  protected $id,
      $player_id,
      $name,
	  $date_obtention;


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

  //GETTERS AND SETTERS
  /**
   * @return mixed
   */
  public function id()
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
  public function player_id()
  {
    return $this->player_id;
  }

  /**
   * @param mixed $player_id
   */
  public function setPlayer_id($player_id)
  {
    $this->player_id = $player_id;
  }

  /**
   * @return mixed
   */
  public function name()
  {
    return $this->name;
  }

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
  public function date_obtention()
  {
    return $this->date_obtention;
  }

  /**
   * @param mixed $date_obtention
   */
  public function setDate_obtention($date_obtention)
  {
    $this->date_obtention = $date_obtention;
  }

  //Class methods

  //Json exportation
  public function jsonSerialize()
  {
    $result = get_object_vars($this);
    return json_encode($result);
  }


}