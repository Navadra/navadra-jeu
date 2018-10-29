<?php

class TitlesManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }

  public function add(Title $title)
  {
    setlocale(LC_TIME, 'fr_FR');
    date_default_timezone_set('Europe/Paris');
    $date_obtention = strftime('%Y-%m-%d %H:%M:%S', time());
    $q = $this->_db_RW->prepare('INSERT INTO titles(player_id, name, date_obtention) VALUES(:player_id, :name, :date_obtention)');
    $q->execute(array(
		'player_id' => $title->player_id(),
		'name' => $title->name(),
		'date_obtention' => $date_obtention,
    ));
    $title->hydrate(array(
        'id' => $this->_db_RW->lastInsertId(),
        'date_obtention' => $date_obtention,
    ));
  }

  public function update(Title $title)
  {
    $q = $this->_db_RW->prepare('UPDATE titles SET player_id=:player_id, name=:name, date_obtention=:date_obtention WHERE id=:id');
    $q->execute(array(
        'id' => $title->id(),
		'player_id' => $title->player_id(),
		'name' => $title->name(),
        'date_obtention' => $title->date_obtention()
    ));
  }

  public function titlesCount(Joueur $joueur)
  {
    $q = $this->_db_RO->prepare('SELECT COUNT(*) AS titlesCount FROM titles WHERE player_id= :player_id;');
    $q->execute(array(
        'player_id' => $joueur->id()
    ));
    $donnees = $q->fetch();
    return $donnees["titlesCount"];
  }

  public function delete_all(Joueur $joueur)
  {
    $q = $this->_db_RW->prepare('DELETE FROM titles WHERE player_id=:player_id');
    $q->execute(array(
        'player_id' => $joueur->id(),
    ));
    $q->closeCursor();
  }

  public function get_all(Joueur $joueur)
  {
    $q = $this->_db_RO->prepare('SELECT * FROM titles WHERE player_id= :player_id ORDER BY date_obtention DESC');
    $q->execute(array(
        'player_id' => $joueur->id(),
    ));
    $titles = array();
    while ($donnees = $q->fetch()) {
      $title = new Title($donnees);
	  $titles[] = $title;
    }
    return $titles;
  }

  public function get($id)
  {
    $q = $this->_db_RO->prepare('SELECT * FROM titles WHERE id= :id');
    $q->execute(array(
        'id' => $id,
    ));
    $donnees = $q->fetch();
    $title = new Title($donnees);
    return $title;
  }

}
