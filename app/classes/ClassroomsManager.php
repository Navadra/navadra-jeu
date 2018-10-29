<?php

class ClassroomsManager {

	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
	}

	public function add(Classroom $classroom) {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$dateCreation = strftime('%Y-%m-%d %H:%M:%S', time());
		$classroom = $this->generateCode($classroom);
		$q = $this->_db_RW->prepare('INSERT INTO classrooms (idTeacher, idStudents, name, level, nbStudents, maxStudents, code, dateCreation) VALUES(:idTeacher, :idStudents, :name, :level, :nbStudents, :maxStudents, :code, :dateCreation)');
		$q->execute(array(
			'idTeacher' => $classroom->idTeacher(),
			'idStudents' => "",
			'name' => $classroom->name(),
			'level' => $classroom->level(),
			'nbStudents' => 0,
			'maxStudents' => $classroom->maxStudents(),
			'code' => $classroom->code(),
			'dateCreation' => $dateCreation,
		));
		$classroom->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),
    ));
	}

	public function generateCode(Classroom $classroom) {
		$code = "";
		while($code == "" || $this->exists($code)) { //Verify that the code does not already exist
			$nbRandomCar = 4;
			$possibilities = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			for($i=1;$i<=$nbRandomCar;$i++) { //We add random caracters
				$choice = rand(1, 26);
				$code .= substr($possibilities, $choice-1, 1);
			}
		}
		$classroom->setCode($code);
		return $classroom;
	}

	public function update(Classroom $classroom) {
		if($classroom->idStudents()){$idStudents = serialize($classroom->idStudents());}
		else{$idStudents = "";}
		$q = $this->_db_RW->prepare('UPDATE classrooms SET idTeacher=:idTeacher, idStudents=:idStudents, name=:name, level=:level, nbStudents=:nbStudents, maxStudents=:maxStudents, code=:code, dateCreation=:dateCreation WHERE id=:id');
		$q->execute(array(
			'idTeacher' => $classroom->idTeacher(),
			'idStudents' => $idStudents,
			'name' => $classroom->name(),
			'level' => $classroom->level(),
			'nbStudents' => $classroom->nbStudents(),
			'maxStudents' => $classroom->maxStudents(),
			'code' => $classroom->code(),
			'dateCreation' => $classroom->dateCreation(),
			'id' => $classroom->id()
		));
	}

	public function getAll() {
		$q = $this->_db_RO->prepare('SELECT * FROM classrooms ORDER BY id DESC');
		$q->execute();
		$classrooms = array();
		while($data = $q->fetch()) {
			$data["idStudents"] = unserialize($data["idStudents"]);
			$classrooms[] = new Classroom($data);
		}
		return $classrooms;
	}

	public function getClassroomsTeacher (Joueur $player) {
		$q = $this->_db_RO->prepare('SELECT * FROM classrooms WHERE idTeacher= :idTeacher ORDER BY name ASC, nbStudents DESC');
		$q->execute(array(
 			'idTeacher' => $player->id(),
	 	));
		$classrooms = array();
		while($data = $q->fetch()) {
			$data["idStudents"] = unserialize($data["idStudents"]);
			$classrooms[] = new Classroom($data);
		}
		return $classrooms;
	}

	public function getClassroomStudent (Joueur $player) {
		$q = $this->_db_RO->prepare('SELECT * FROM classrooms WHERE idStudents LIKE :idSerialize');
		$q->execute(array(
 			'idSerialize' => '%i:'.$player->id().';%',
	 	));
		if($data = $q->fetch()) {
			$data["idStudents"] = unserialize($data["idStudents"]);
			$classroom  = new Classroom($data);
			return $classroom;
		} else {
			return "NoClassroom";
		}
	}

	public function exists($info) {
		if(is_int($info)){
			$q = $this->_db_RO->prepare('SELECT * FROM classrooms WHERE id= :id');
			$q->execute(array(
	 			'id' => $info,
		 	));
		} else {
			$q = $this->_db_RO->prepare('SELECT * FROM classrooms WHERE code= :code');
			$q->execute(array(
	 			'code' => $info,
		 	));
		}
		if($data = $q->fetch()){
			return true;
		} else {
			return false;
		}
	}

	public function getByCode ($code)	{
		$q = $this->_db_RO->prepare('SELECT * FROM classrooms WHERE code= :code');
		$q->execute(array(
 			'code' => $code,
	 	));
		$data = $q->fetch();
		$data["idStudents"] = unserialize($data["idStudents"]);
		$classroom = new Classroom($data);
		return $classroom;
	}

	public function getById ($id) {
		$q = $this->_db_RO->prepare('SELECT * FROM classrooms WHERE id= :id');
		$q->execute(array(
 			'id' => $id,
	 	));
		$data = $q->fetch();
		$data["idStudents"] = unserialize($data["idStudents"]);
		$classroom = new Classroom($data);
		return $classroom;
	}

	public function hasTimeSlot(Joueur $player) {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$now = strftime('%Y-%m-%d %H:%M:%S',time());
    $q = $this->_db_RO->prepare(
			'SELECT t.notion1, t.notion2, t.notion3
			FROM classrooms c
			INNER JOIN timeslots t
				ON c.id = t.idClassroom
			WHERE  t.startTime <= :now AND t.endTime >= :now AND c.idStudents LIKE :idSerialize');
    $q->execute(array(
			'now' => $now,
			'idSerialize' => '%i:'.$player->id().';%',
		));
		$data = $q->fetch();
		if($data){
			return array($data["notion1"], $data["notion2"], $data["notion3"]);
		} else {
			return "NoTimeSlot";
		}
  }

	public function classroomStudents(Classroom $classroom) {
    $q = $this->_db_RO->prepare(
			'SELECT j.pseudo, j.prenom, j.nom, j.niveau, j.id
			FROM classrooms c
			INNER JOIN joueurs j
				ON c.idStudents LIKE CONCAT("%:", j.id ,";%")
			WHERE  c.id= :idClassroom
			ORDER BY j.niveau DESC, j.id ASC');
    $q->execute(array(
			'idClassroom' => $classroom->id(),
		));
		$names = array();
    while ($donnees = $q->fetch()) {
			if($donnees["prenom"] != "" && $donnees["nom"] != ""){
				$studentName = $donnees["id"].";".$donnees["prenom"]." ".substr($donnees["nom"], 0, 1).". (niv.".$donnees["niveau"].")";
			} else {
				$studentName = $donnees["id"].";".$donnees["pseudo"]." (niv.".$donnees["niveau"].")";
			}
			$names[] = $studentName;
    }
    return $names;
  }

	public function classroomProgressByChallenge(Classroom $classroom) {
    $q = $this->_db_RO->prepare(
			'SELECT ch.id, ch.element, ch.name, ch.id_joueur, ch.current_level, ch.stock, ch.tries, ch.date_last_try, j.pseudo, j.prenom, j.nom, j.niveau
			FROM classrooms c
			INNER JOIN challenges ch
				ON c.idStudents LIKE CONCAT("%:", ch.id_joueur ,";%")
			INNER JOIN joueurs j
				ON ch.id_joueur = j.id
			WHERE  c.id= :idClassroom
			ORDER BY j.niveau DESC, ch.id_joueur ASC');
    $q->execute(array(
			'idClassroom' => $classroom->id(),
		));
		$challenges = array();
    while ($donnees = $q->fetch()) {
			if($donnees["prenom"] != "" && $donnees["nom"] != ""){
				$studentName = $donnees["prenom"]." ".substr($donnees["nom"], 0, 1).". (niv.".$donnees["niveau"].")";
			} else {
				$studentName = $donnees["pseudo"]." (niv.".$donnees["niveau"].")";
			}
			unset($donnees['prenom'], $donnees['nom'], $donnees['pseudo'], $donnees["niveau"]);
			$donnees['additionalData'] = $studentName;
			$challenge = new Challenge($donnees);
      $challenges[$challenge->getAdditionalData()."_".$challenge->getName()] = $challenge->getCurrent_level()."_".$challenge->daysLastPractice()."_".$challenge->getTries();
    }
    return $challenges;
  }

	public function challengesClassroom(Classroom $classroom) {
    $q = $this->_db_RO->prepare(
			'SELECT ch.id, ch.element, ch.name, ch.id_joueur, ch.current_level, ch.stock, ch.tries, ch.date_last_try
			FROM classrooms c
			INNER JOIN challenges ch
				ON c.idStudents LIKE CONCAT("%:", ch.id_joueur ,";%")
			INNER JOIN joueurs j
				ON ch.id_joueur = j.id
			WHERE  c.id= :idClassroom
			ORDER BY ch.id_joueur ASC');
    $q->execute(array(
			'idClassroom' => $classroom->id(),
		));
		$challenges = array();
    while ($donnees = $q->fetch()) {
			$challenge = new Challenge($donnees);
      $challenges[] = $challenge;
    }
    return $challenges;
  }

	public function delete (Classroom $classroom) {
		$params = array(
	    'id' => $classroom->id(),
	  );
    $q = $this->_db_RW->prepare('DELETE FROM classrooms WHERE id=:id');
    $q->execute($params);
  }

	public function deleteAll(Joueur $player) {
		$params = array(
	    'idTeacher' => $player->id(),
	  );
    $q = $this->_db_RW->prepare('DELETE FROM classrooms WHERE idTeacher=:idTeacher');
    $q->execute($params);
  }

}
