<?php

class AbonnementsManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }


// Creates or Updates an abonnement of a player after payment
  // Existing limitation : only 2 != formules per player
  public function save_or_update( $player_id, $wp_id, $formule, $qty ) {
    //error_log ( "save_or_update : " . $player_id . " - ". $wp_id . " - ". $formule . " - ". $qty  );
    setlocale(LC_TIME, 'fr_FR');
    date_default_timezone_set('Europe/Paris');
    // Now
    $date_now = new DateTime();
    $date_abonnement_starts = $date_now->format('Y-m-d');

    // Find everything with $player_id ordered by dt_expiration desc limit 1
    $abonnement = $this->get_last_by_player_id( $player_id );

    // If an abonnement already exists and is valid, date_now should become last dt_expiration
    if ( $abonnement != null ) {
      //error_log ( json_encode($abonnement ) );
      // works only if expiration is not too old !!!
      // In this case date_abonnement_starts must be the max beetwen dt_expiration
      // and date now (it works because date is YYY-mm-dd)...
      $date_abonnement_starts = max( $abonnement->getDt_expiration(), $date_abonnement_starts );
    }

    // Prepare dt_expiration based on now + qty monts (used only if player never had an expiration date)
    $dt_expiration = date( 'Y-m-d', strtotime( "+$qty months", strtotime( $date_abonnement_starts ) ) );
    //$dt_expiration = $date_abonnement_starts->add( new DateInterval('P'.$qty.'M') );

    // SAVE new line for player and parent
    $params = array(
      'player_id' => $player_id,
      'id_parents_wp' => $wp_id,
      'formule' => $formule,
      'dt_initial' => $date_now->format('Y-m-d'),
      'qty_total' => $qty,
      'dt_expiration' => date( 'Y-m-d', strtotime($dt_expiration))
    );
    $q = $this->_db_RW->prepare('INSERT INTO abonnements ( id_player, id_parents_wp, formule, dt_initial, qty_total, dt_expiration) VALUES(:player_id, :id_parents_wp, :formule, :dt_initial, :qty_total, :dt_expiration );');
    $q->execute($params);

  // Player subscription must be == 1
    $this->abo_ok( $player_id );
  }

  /**
   * Gets the most recent abonnement for a given id player
   */
  public function get_last_by_player_id( $id_player ) {
    //error_log("get_last_by_player_id : " . $id_player );
    $q = $this->_db_RO->prepare('SELECT * FROM abonnements WHERE id_player=:id_player ORDER BY dt_expiration DESC LIMIT 1;');
    $q->execute(array(
        'id_player' => $id_player
    ));
    $abonnements = array();
    while ($donnees = $q->fetch()) {
      $abonnements[] = new Abonnement($donnees);
    }
    if ( count($abonnements) > 0 ) {
      return $abonnements[0];
    }
    return null;
  }

  /**
   * @param $id_player the id of the player
   * @return true if abonnement is still valid, false otherwise
   */
  public function is_valid( $id_player ) {
    setlocale(LC_TIME, 'fr_FR');
    date_default_timezone_set('Europe/Paris');
    // Now
    $date_now = new DateTime();
    $now = $date_now->format('Y-m-d');
    $abo = $this->get_last_by_player_id( $id_player );
    // Date abonnement must be equals (0) or bigger (1) than now
    return ($abo != null && strcmp( $abo->getDt_expiration() , $now ) != -1 );
  }


    /**
   * Gets the most recent abonnement for a given tuple id wordpress and id player
   */
  public function get_last_by_wp_id_and_player_id( $id_parents_wp, $id_player ) {
    //error_log("get_last_by_wp_id_and_player_id : " . $id_parents_wp . " - " . $id_player );
    $q = $this->_db_RO->prepare('SELECT * FROM abonnements WHERE id_parents_wp=:id_parents_wp AND id_player=:id_player ORDER BY dt_expiration DESC LIMIT 1;');
    $q->execute(array(
        'id_parents_wp' => $id_parents_wp,
        'id_player' => $id_player
    ));
    $abonnements = array();
    while ($donnees = $q->fetch()) {
      $abonnements[] = new Abonnement($donnees);
    }
    if ( count($abonnements) > 0 ) {
      return $abonnements[0];
    }
    return null;
  }

  public function get_by_wp_id( $id_parents_wp ) {
    //$q = $this->_db_RO->prepare('SELECT a.id_player, a.id_parents_wp, a.dt_expiration, a.formule, j.email, j.pseudo FROM joueurs j INNER JOIN abonnements a ON a.id_player = j.id WHERE a.id_parents_wp=:id_parents_wp ORDER BY a.dt_expiration ASC;');
    $q = $this->_db_RO->prepare('SELECT * FROM abonnements WHERE id_parents_wp=:id_parents_wp ORDER BY dt_expiration ASC;');
    $q->execute(array(
        'id_parents_wp' => $id_parents_wp
    ));
    $abonnements = array();
    while ($donnees = $q->fetch()) {
      $abonnements[] = new Abonnement($donnees);
    }
    return $abonnements;
  }

  public function get_by_player( $id_player ) {
    $q = $this->_db_RO->prepare('SELECT * FROM abonnements WHERE id_player=:id_player;');
    $q->execute(array(
        'id_player' => $id_player
    ));
		$donnees = $q->fetch();
    if ( $donnees != null ) return new Abonnement($donnees);
    return null;
  }

	public function get_last_by_player( $id_player ) {
    $q = $this->_db_RO->prepare('SELECT * FROM abonnements WHERE id_player=:id_player ORDER BY dt_initial DESC LIMIT 0, 1;');
    $q->execute(array(
        'id_player' => $id_player
    ));
		$donnees = $q->fetch();
    if ( $donnees != null ) return new Abonnement($donnees);
    return null;
  }

  public function get_by_email( $email ) {
    $q = $this->_db_RO->prepare('SELECT id, email, pseudo FROM joueurs WHERE email=:email;');
    $q->execute(array(
      'email' => $email
    ));
    $donnees = $q->fetch();
    return $donnees;
  }

  public function get_by_pseurriel( $pseurriel ) {
    // Check email
    if(preg_match("#^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9_.-]{2,}\.[a-zA-Z]{2,4}$#", $pseurriel)) {
      //$q = $this->_db_RO->prepare('SELECT a.id_player, a.dt_expiration, a.formule, j.id, j.email, j.pseudo FROM joueurs j INNER JOIN abonnements a ON a.id_player=j.id WHERE j.email=:email;');
      $q = $this->_db_RO->prepare('SELECT id, email, pseudo FROM joueurs WHERE email=:email;');
      $q->execute(array(
        'email' => $pseurriel
      ));
      $donnees = $q->fetch();
      return $donnees;
    }
    //Sinon on fait la recherche sur le pseudo
    else {
      //$q = $this->_db_RO->prepare('SELECT a.id_player, a.dt_expiration, a.formule, j.id, j.email, j.pseudo FROM joueurs j INNER JOIN abonnements a ON a.id_player=j.id WHERE j.pseudo=:pseudo;');
      $q = $this->_db_RO->prepare('SELECT id, email, pseudo FROM joueurs WHERE pseudo=:pseudo;');
      $q->execute(array(
          'pseudo' => $pseurriel,
      ));
      $donnees = $q->fetch();
      return $donnees;
    }
  }

  public function abo_ok($id_player) {
		$q = $this->_db_RW->prepare('UPDATE joueurs SET abonnement_ok=1 WHERE id=:id_player');
		$q->execute(array(
			'id_player' => $id_player
		));
	}

	public function purchasedBetween($periodStart, $periodEnd) {
    $q = $this->_db_RO->prepare('SELECT COUNT(*) AS purchased
		FROM joueurs j
		LEFT OUTER JOIN abonnements a
		ON j.id = a.id_player
		WHERE a.dt_initial>=:periodStart AND a.dt_initial<=:periodEnd AND j.id!= :michel AND j.id!= :jeremie AND j.id!= :julien ');
    $q->execute(array(
			'michel' => 49,
			'jeremie' => 47,
			'julien' => 48,
			'periodStart' => $periodStart,
			'periodEnd' => $periodEnd
    ));
		$donnees = $q->fetch();
		return (int) $donnees["purchased"];
  }

	public function delete_all(Joueur $joueur) {
		$params = array(
	        'id_player' => $joueur->id(),
	    );
      $q = $this->_db_RW->prepare('DELETE FROM abonnements WHERE id_player=:id_player');
      $q->execute($params);
  }

}
