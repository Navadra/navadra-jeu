<?php

class Abonnement implements JsonSerializable
{
  protected
      $id_player,
      $id_parents_wp,
      $dt_initial,
      $qty_total,
      $dt_expiration,
      $formule;


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


  public function getId_player() {
    return $this->id_player;
  }
	public function setId_player($id_player) {
		$this->id_player = $id_player;
	}

  public function getId_parents_wp() {
    return $this->id_parents_wp;
  }
	public function setId_parents_wp($id_parents_wp) {
		$this->id_parents_wp = $id_parents_wp;
	}

  public function getDt_initial() {
    return $this->dt_initial;
  }
	public function setDt_initial($dt_initial) {
		$this->dt_initial = $dt_initial;
	}

  public function getQty_total() {
    return $this->qty_total;
  }
	public function setQty_total($qty_total) {
		$this->qty_total = $qty_total;
	}

  public function getDt_expiration() {
    return $this->dt_expiration;
  }
	public function setDt_expiration($dt_expiration) {
		$this->dt_expiration = $dt_expiration;
	}

  public function getFormule() {
    return $this->formule;
  }
	public function setFormule($formule) {
		$this->formule = $formule;
	}


  //Class methods

  //Json exportation
  public function jsonSerialize()
  {
    $result = get_object_vars($this);
    return json_encode($result);
  }


}
