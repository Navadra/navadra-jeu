<?php

class Flow implements JsonSerializable
{
  protected $id;
  protected $joueur_id;
  protected $combat_id;
  protected $turn_player = 1;
  protected $round = 0;
  protected $spell_num;
  protected $spell_level = 1;
  protected $spell_reussite = 0;
  protected $success = 0;
  protected $hit = 0;
  protected $pm_player = 0;
  protected $pm_monster = 0;
  protected $absorb = 0;
  protected $dodge = 0;
  protected $heal = 0;
  protected $sendback = 0;
  protected $skipturn = 0;


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


  //Fonctions d'exportation en Json
  public function jsonSerialize()
  {
    $result = get_object_vars($this);
    return json_encode($result);
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
  public function getJoueur_id()
  {
    return $this->joueur_id;
  }

  /**
   * @param mixed $joueur_id
   */
  public function setJoueur_id($joueur_id)
  {
    $this->joueur_id = $joueur_id;
  }

  /**
   * @return mixed
   */
  public function getCombat_id()
  {
    return $this->combat_id;
  }

  /**
   * @param mixed $combat_id
   */
  public function setCombat_id($combat_id)
  {
    $this->combat_id = $combat_id;
  }

  /**
   * @return mixed
   */
  public function getTurn_player()
  {
    return $this->turn_player;
  }

  /**
   * @param mixed $turn_player
   */
  public function setTurn_player($turn_player)
  {
    $this->turn_player = $turn_player;
  }

  /**
   * @return mixed
   */
  public function getRound()
  {
    return $this->round;
  }

  /**
   * @param mixed $round
   */
  public function setRound($round)
  {
    $this->round = $round;
  }

  /**
   * @return mixed
   */
  public function getSpell_num()
  {
    return $this->spell_num;
  }

  /**
   * @param mixed $spell_num
   */
  public function setSpell_num($spell_num)
  {
    $this->spell_num = $spell_num;
  }

  /**
   * @return mixed
   */
  public function getSpell_level()
  {
    return $this->spell_level;
  }

  /**
   * @param mixed $spell_level
   */
  public function setSpell_level($spell_level)
  {
    $this->spell_level = $spell_level;
  }

  /**
   * @return mixed
   */
  public function getSpell_reussite()
  {
    return $this->spell_reussite;
  }

  /**
   * @param mixed $spell_reussite
   */
  public function setSpell_reussite($spell_reussite)
  {
    $this->spell_reussite = $spell_reussite;
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
  public function getHit()
  {
    return $this->hit;
  }

  /**
   * @param mixed $hit
   */
  public function setHit($hit)
  {
    if ($hit == null) $hit = 0;
    $this->hit = $hit;
  }

  /**
   * @return mixed
   */
  public function getPm_player()
  {
    return $this->pm_player;
  }

  /**
   * @param mixed $pm_player
   */
  public function setPm_player($pm_player)
  {
    if ($pm_player == null) $pm_player = 0;
    $this->pm_player = $pm_player;
  }

  /**
   * @return mixed
   */
  public function getPm_monster()
  {
    return $this->pm_monster;
  }

  /**
   * @param mixed $pm_monster
   */
  public function setPm_monster($pm_monster)
  {
    if ($pm_monster == null) $pm_monster = 0;
    $this->pm_monster = $pm_monster;
  }

  /**
   * @return mixed
   */
  public function getAbsorb()
  {
    return $this->absorb;
  }

  /**
   * @param mixed $absorb
   */
  public function setAbsorb($absorb)
  {
    if ($absorb == null) $absorb = 0;
    $this->absorb = $absorb;
  }

  /**
   * @return mixed
   */
  public function getDodge()
  {
    return $this->dodge;
  }

  /**
   * @param mixed $dodge
   */
  public function setDodge($dodge)
  {
    if ($dodge == null) $dodge = 0;
    $this->dodge = $dodge;
  }

  /**
   * @return mixed
   */
  public function getHeal()
  {
    return $this->heal;
  }

  /**
   * @param mixed $heal
   */
  public function setHeal($heal)
  {
    if ($heal == null) $heal = 0;
    $this->heal = $heal;
  }

  /**
   * @return mixed
   */
  public function getSendback()
  {
    return $this->sendback;
  }

  /**
   * @param mixed $sendback
   */
  public function setSendback($sendback)
  {
    if ($sendback == null) $sendback = 0;
    $this->sendback = $sendback;
  }

  /**
   * @return mixed
   */
  public function getSkipturn()
  {
    return $this->skipturn;
  }

  /**
   * @param mixed $skipturn
   */
  public function setSkipturn($skipturn)
  {
    if ($skipturn == null) $skipturn = 0;
    $this->skipturn = $skipturn;
  }

}