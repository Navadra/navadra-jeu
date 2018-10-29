<?php

class Achievement implements JsonSerializable
{
  protected
      $id,
      $code,
      $type,
      $name,
      $desc,
      $obtained;


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
  public function getType()
  {
    return $this->type;
  }

  /**
   * @param mixed $type
   */
  public function setType($type)
  {
    $this->type = $type;
  }

  /**
   * @return mixed
   */
  public function getName()
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
  public function getDesc()
  {
    return $this->desc;
  }

  /**
   * @param mixed $desc
   */
  public function setDesc($desc)
  {
    $this->desc = $desc;
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
  public function getCode()
  {
    return $this->code;
  }

  /**
   * @param mixed $code
   */
  public function setCode($code)
  {
    $this->code = $code;
  }

  /**
   * @return mixed
   */
  public function getObtained()
  {
    return $this->obtained;
  }

  /**
   * @param mixed $obtained
   */
  public function setObtained($obtained)
  {
    $this->obtained = $obtained;
  }



  //Class methods
	public function icon()  {
		if($this->getType() == "DEFIS"){
			return "/webroot/img/achievements/challenge.png";
		} else if($this->getType() == "DATE"){
			return "/webroot/img/achievements/date.png";
		} else if($this->getType() == "COMBAT"){
			return "/webroot/img/achievements/fight.png";
		} else if($this->getType() == "PROGRESSION"){
			return "/webroot/img/achievements/progress.png";
		} else if($this->getType() == "SOCIAL"){
			return "/webroot/img/achievements/social.png";
		}
	}

	public function iconTitle()  {
		if($this->getType() == "DEFIS"){
			return "Défis";
		} else if($this->getType() == "DATE"){
			return "Date";
		} else if($this->getType() == "COMBAT"){
			return "Combat";
		} else if($this->getType() == "PROGRESSION"){
			return "Progression";
		} else if($this->getType() == "SOCIAL"){
			return "Social";
		}
	}

	public function dateObtention()  {
		$obtained = strtotime($this->getObtained());
		$jour = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi");
		$mois = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre");
		return "Obtenu le ".date("d", $obtained)." ".$mois[date("n", $obtained)]." ".date("Y", $obtained);
		//return $jour[date("w", $log)]." ".date("d", $log)." ".$mois[date("n", $log)]." ".date("Y", $log)." à ".date("H", $log)."h".date("i", $log);
	}


  //Json exportation
  public function jsonSerialize()  {
    $result = get_object_vars($this);
    return json_encode($result);
  }


}
