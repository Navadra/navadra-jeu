<?php

class ImpressionsManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
	{
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  	}

	public function add(Impression $impression)
    {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = strftime('%Y-%m-%d %H:%M:%S',time());
		$q = $this->_db_RW->prepare('INSERT INTO impressions(id_player, video, quality, date_impression) VALUES(:id_player, :video, :quality, :date_impression)');
		$q->execute(array(
			'id_player' => $impression->id_player(),
			'video' => $impression->video(),
			'quality' => $impression->quality(),
			'date_impression' => $date
			));
		$impression->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),
					'date_impression' => $date,
    	));
	}

	public function get_all()
    {
		$q = $this->_db_RO->prepare('SELECT * FROM impressions ORDER BY date_impression DESC');
		$q->execute();
		$impressions = array();
		while($donnees = $q->fetch())
		{
			$impressions[] = new Impression($donnees);
		}
		return $impressions;
	}

	public function get_player($id_player) //Get all impressions of a player
	{
		$q = $this->_db_RO->prepare('SELECT * FROM impressions WHERE id_player= :id_player ORDER BY date_impression DESC');
		$q->execute(array(
 			'id_player' => $id_player,
	 		));
		$impressions = array();
		while($donnees = $q->fetch())
		{
			$impressions[] = new Impression($donnees);
		}
		return $impressions;
	}

	public function get($id)
	{
		$q = $this->_db_RO->prepare('SELECT * FROM impressions WHERE id= :id');
		$q->execute(array(
 			'id' => $id,
	 		));
		$donnees = $q->fetch();
		$impression = new Impression($donnees);
		return $impression;
	}

	public function delete_all(Joueur $joueur) {
		$q = $this->_db_RW->prepare('DELETE FROM impressions WHERE id_player= :id_player');
		$q->execute(array(
			'id_player' => $joueur->id(),
			));
	}

}
