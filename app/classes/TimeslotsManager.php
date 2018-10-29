<?php

class TimeslotsManager {

	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }

	public function add(Timeslot $timeslot) {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$dateCreation = strftime('%Y-%m-%d %H:%M:%S', time());
		$q = $this->_db_RW->prepare('INSERT INTO timeslots (idTeacher, idClassroom, startTime, endTime, notion1, notion2, notion3, dateCreation) VALUES(:idTeacher, :idClassroom, :startTime, :endTime, :notion1, :notion2, :notion3, :dateCreation)');
		$q->execute(array(
			'idTeacher' => $timeslot->idTeacher(),
			'idClassroom' => $timeslot->idClassroom(),
			'startTime' => $timeslot->startTime(),
			'endTime' => $timeslot->endTime(),
			'notion1' => $timeslot->notion1(),
			'notion2' => $timeslot->notion2(),
			'notion3' => $timeslot->notion3(),
			'dateCreation' => $dateCreation,
		));
		$timeslot->hydrate(array(
      'id' => $this->_db_RW->lastInsertId(),
    ));
	}

	public function update(Timeslot $timeslot) {
		$q = $this->_db_RW->prepare('UPDATE timeslots SET idTeacher=:idTeacher, idClassroom=:idClassroom, startTime=:startTime, endTime=:endTime, notion1=:notion1, notion2=:notion2, notion3=:notion3, dateCreation=:dateCreation WHERE id=:id');
		$q->execute(array(
			'idTeacher' => $timeslot->idTeacher(),
			'idClassroom' => $timeslot->idClassroom(),
			'startTime' => $timeslot->startTime(),
			'endTime' => $timeslot->endTime(),
			'notion1' => $timeslot->notion1(),
			'notion2' => $timeslot->notion2(),
			'notion3' => $timeslot->notion3(),
			'dateCreation' => $timeslot->dateCreation(),
			'id' => $timeslot->id()
		));
	}

	public function getAll() {
		$q = $this->_db_RO->prepare('SELECT * FROM timeslots ORDER BY id DESC');
		$q->execute();
		$timeslots = array();
		while($data = $q->fetch()) {
			$timeslots[] = new Timeslot($data);
		}
		return $timeslots;
	}

	public function getTeacher (Joueur $player) {
		$q = $this->_db_RO->prepare('SELECT * FROM timeslots WHERE idTeacher= :idTeacher ORDER BY startTime DESC, id DESC');
		$q->execute(array(
 			'idTeacher' => $player->id(),
	 	));
		$timeslots = array();
		while($data = $q->fetch()) {
			$timeslots[] = new Timeslot($data);
		}
		return $timeslots;
	}

	public function getTeacherNotPast (Joueur $player) {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$now = strftime('%Y-%m-%d %H:%M:%S', time());
		$q = $this->_db_RO->prepare('SELECT * FROM timeslots WHERE idTeacher= :idTeacher AND startTime > :now ORDER BY startTime DESC, id DESC');
		$q->execute(array(
 			'idTeacher' => $player->id(),
			'now' => $now
	 	));
		$timeslots = array();
		while($data = $q->fetch()) {
			$timeslots[] = new Timeslot($data);
		}
		return $timeslots;
	}

	public function getClassroom (Classroom $classroom) {
		$q = $this->_db_RO->prepare('SELECT * FROM timeslots WHERE idClassroom= :idClassroom');
		$q->execute(array(
 			'idClassroom' => $classroom->id(),
	 	));
		$timeslots = array();
		while($data = $q->fetch()) {
			$timeslots[] = new Timeslot($data);
		}
		if(count($timeslots) > 0) {
			return $timeslots;
		} else {
			return "NoTimeSlot";
		}
	}

	public function exists($id) {
		$q = $this->_db_RO->prepare('SELECT * FROM timeslots WHERE id= :id');
		$q->execute(array(
	 		'id' => $id,
		));
		if($data = $q->fetch()){
			return true;
		} else {
			return false;
		}
	}

	public function getById ($id) {
		$q = $this->_db_RO->prepare('SELECT * FROM timeslots WHERE id= :id');
		$q->execute(array(
 			'id' => $id,
	 	));
		$data = $q->fetch();
		$timeslot = new Timeslot($data);
		return $timeslot;
	}

	public function countAll () {
		$q = $this->_db_RO->prepare('SELECT COUNT(*) AS totalSlots FROM timeslots');
		$q->execute();
		$data = $q->fetch();
		return (int) $data["totalSlots"];
	}

	public function delete (Timeslot $timeslot) {
		$params = array(
	    'id' => $timeslot->id(),
	  );
    $q = $this->_db_RW->prepare('DELETE FROM timeslots WHERE id=:id');
    $q->execute($params);
  }

	public function deleteTeacher(Joueur $player) {
		$params = array(
	    'idTeacher' => $player->id(),
	  );
    $q = $this->_db_RW->prepare('DELETE FROM timeslots WHERE idTeacher=:idTeacher');
    $q->execute($params);
  }

	public function deleteClass(Classroom $classroom) {
		$params = array(
	    'idClassroom' => $classroom->id(),
	  );
    $q = $this->_db_RW->prepare('DELETE FROM timeslots WHERE idClassroom=:idClassroom');
    $q->execute($params);
  }

}
