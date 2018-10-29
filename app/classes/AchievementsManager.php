<?php

class AchievementsManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }


public function count_all() {
    $q = $this->_db_RO->prepare('SELECT count(*) AS achievementCount FROM achievements');
    $q->execute();
    $donnees = $q->fetch();
    return $donnees["achievementCount"];
  }


  public function add($id_player, $id_achievement) {
    // If player doesn't have the achievement
    if ( $this->has($id_player, $id_achievement) == false ) {
      setlocale(LC_TIME, 'fr_FR');
      date_default_timezone_set('Europe/Paris');
      $obtained = strftime('%Y-%m-%d %H:%M:%S', time());

      $q = $this->_db_RW->prepare('INSERT INTO player_achievements(id_player, id_achievement, obtained) VALUES(:id_player, :id_achievement, :obtained)');
      //error_log("********** REQUEST : INSERT INTO player_achievements(id_player, id_achievement, obtained) VALUES(".$id_player.", ".$id_achievement.", ".$obtained.")");
      $q->execute(array(
          'id_player' => $id_player,
          'id_achievement' => $id_achievement,
          'obtained' => $obtained
      ));
    }
  }

  public function has($id_player, $id_achievement) {
    setlocale(LC_TIME, 'fr_FR');
    date_default_timezone_set('Europe/Paris');
    $obtained = strftime('%Y-%m-%d %H:%M:%S', time());
    $q = $this->_db_RO->prepare('select count(*) AS achievementCount from player_achievements where id_player=:id_player and id_achievement=:id_achievement;');
    $q->execute(array(
        'id_player' => $id_player,
        'id_achievement' => $id_achievement
    ));
    $donnees = $q->fetch();
    return $donnees["achievementCount"] > 0;
  }

  public function count(Joueur $joueur) {
    $q = $this->_db_RO->prepare('SELECT count(*) AS achievementCount FROM player_achievements WHERE id_player= :id_player');
    $q->execute(array(
        'id_player' => $joueur->id(),
    ));
    $donnees = $q->fetch();
    return $donnees["achievementCount"];
  }

  public function get_all(Joueur $joueur) {
    //$q = $this->_db_RO->prepare('SELECT * FROM player_achievements WHERE id_player= :id_player ORDER BY obtained DESC');
    $q = $this->_db_RO->prepare('SELECT a.id, a.name, a.desc, a.type, p.obtained FROM player_achievements p INNER JOIN achievements a ON a.id = p.id_achievement where p.id_player=:id_player order by p.obtained desc, a.type asc');
    $q->execute(array(
        'id_player' => $joueur->id(),
    ));
    $achievements = array();
    while ($donnees = $q->fetch()) {
      $achievements[] = new Achievement($donnees);
    }
    return $achievements;
  }

  public function for_won_against_xx_monster_of_elem(Joueur $joueur, $total, $elem) {
    // ID is the trophy unique ID
    $id = 0;
    switch ($elem) {
      case 'feu':
        if ($total >= 100) $id = 5;
        else if ($total >= 50) $id = 4;
        else if ($total >= 25) $id = 3;
        else if ($total >= 10) $id = 2;
        else if ($total >= 5) $id = 1;
        break;
      case 'terre':
        if ($total >= 100) $id = 10;
        else if ($total >= 50) $id = 9;
        else if ($total >= 25) $id = 8;
        else if ($total >= 10) $id = 7;
        else if ($total >= 5) $id = 6;
        break;
      case 'eau':
        if ($total >= 100) $id = 15;
        else if ($total >= 50) $id = 14;
        else if ($total >= 25) $id = 13;
        else if ($total >= 10) $id = 12;
        else if ($total >= 5) $id = 11;
        break;
      case 'vent':
        if ($total >= 100) $id = 20;
        else if ($total >= 50) $id = 19;
        else if ($total >= 25) $id = 18;
        else if ($total >= 10) $id = 17;
        else if ($total >= 5) $id = 16;
        break;
    }
    if ($id > 0) {
      $this->add( $joueur->id(), $id );
    }
  }

	public function delete_all(Joueur $joueur) {
		$params = array(
	        'id_player' => $joueur->id(),
	    );
      $q = $this->_db_RW->prepare('DELETE FROM player_achievements WHERE id_player=:id_player');
      $q->execute($params);
  }

  public function set_achievement_for_ultimate_challenge( $joueur_id, $challenge_name ) {
    $id = -1;
    switch ( $challenge_name ) {
      // FIRE
      case "decimals" : $id = 73; break;
      case "divisions" : $id = 74; break;
      case "fractions" : $id = 75; break;
      case "greatNumbers" : $id = 76; break;
      case "integers" : $id = 77; break;
      case "multiples" : $id = 78; break;
      case "problemInterpretation" : $id = 79; break;

      // WATER
      case "bars" : $id = 80; break;
      case "circulars" : $id = 81; break;
      case "graphs" : $id = 82; break;
      case "proportionality" : $id = 83; break;
      case "percentages" : $id = 84; break;
      case "radars" : $id = 85; break;
      case "tables" : $id = 86; break;

      // WIND
      case "angles" : $id = 87; break;
      case "bisectors" : $id = 88; break;
      case "circles" : $id = 89; break;
      case "lines" : $id = 90; break;
      case "quadrilaterals" : $id = 91; break;
      case "symmetries" : $id = 92; break;
      case "triangles" : $id = 93; break;

      // EARTH
      case "areas" : $id = 94; break;
      case "durations" : $id = 95; break;
      case "lengths" : $id = 96; break;
      case "perimeters" : $id = 97; break;
      case "prices" : $id = 98; break;
      case "volumes" : $id = 99; break;
      case "weights" : $id = 100; break;
    }
    
    if ( $id != -1) {
      $this->add( $joueur_id, $id );
    }
  }

  public function leverage_existing_ultimate_challenges() {
    $q = $this->_db_RO->prepare('SELECT name, id_joueur FROM `challenges` WHERE `current_level`=6 order by `id_joueur` ASC;');
    $q->execute();
    while ($donnees = $q->fetch()) {
      $id_joueur = $donnees["id_joueur"];
      $name = $donnees["name"];
      $this->set_achievement_for_ultimate_challenge( $id_joueur, $name);
    }
    return "ok";
  }
  
}
